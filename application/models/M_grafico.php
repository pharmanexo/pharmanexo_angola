<?php

class M_grafico extends MY_Model
{
    protected $DB_COTACAO;

    public function __construct()
    {
        parent::__construct();

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Obtem a lista de fornecedores da filial
     *
     * @param - bool
     * @return array
     */
    public function matrizFilial($trueArray = FALSE)
    {

        $arr = [];

        $query = "
			SELECT 
      			x.filiais,
          		IF(x.matriz IS NULL, x.nome_fantasia, x.matriz) matriz
        	FROM (
        		SELECT 
          			(
          				SELECT 
          					CASE WHEN GROUP_CONCAT(f2.id) IS NULL THEN f1.id ELSE GROUP_CONCAT(f2.id) END 
          				FROM pharmanexo.fornecedores f2
          				WHERE f2.id_matriz = f1.id_matriz
          			) filiais,
            		IF(f1.id_matriz, 'S', 'N') sn_filiais,
            		(SELECT mtz.nome FROM pharmanexo.fornecedores_matriz mtz WHERE mtz.id = f1.id_matriz) matriz,
           			f1.nome_fantasia
          		FROM pharmanexo.fornecedores f1
          		WHERE f1.sintese = 1
          		GROUP BY filiais, sn_filiais, matriz, f1.nome_fantasia) x
       		GROUP BY x.filiais, matriz
        	ORDER BY matriz ASC
		";

        $getFiliais = $this->db->query($query)->result_array();

        if ($trueArray) {

            foreach ($getFiliais as $getFilial) {

                $arr[$getFilial['matriz']] = $getFilial['filiais'];
            }

            return $arr;
        } else {

            foreach ($getFiliais as $getFilial) {

                $arr[$getFilial['matriz']] = explode(',', $getFilial['filiais']);
            }

            function myInt($n)
            {
                return intval($n);
            }

            foreach ($arr as $key => $ar) {

                $newArr[$key] = array_map("myInt", $ar);
            }

            return $newArr;
        }
    }

    /**
     * Obtem a quantidade de cotações, cot com produto e cot enviada por periodo
     *
     * @param - String nome do integrador
     * @param - String tipo de periodo
     * @return array
     */
    public function getDadosCotacao($integrador, $periodo)
    {

        switch ($periodo) {
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());
                $where_periodo_sintese = "MONTH(sint.dt_inicio_cotacao) = '{$mes}' AND YEAR(dt_inicio_cotacao) = '{$ano}'";
                $where_periodo_bionexo = "MONTH(bio.dt_inicio_cotacao) = '{$mes}' AND YEAR(dt_inicio_cotacao) = '{$ano}'";
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());
                $where_periodo_sintese = "DATE(sint.dt_inicio_cotacao) BETWEEN '{$inicio}' AND '{$fim}'";
                $where_periodo_bionexo = "DATE(bio.dt_inicio_cotacao) BETWEEN '{$inicio}' AND '{$fim}'";
                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());
                $where_periodo_sintese = "DATE(sint.dt_inicio_cotacao) BETWEEN '{$inicio}' AND '{$fim}'";
                $where_periodo_bionexo = "DATE(bio.dt_inicio_cotacao) BETWEEN '{$inicio}' AND '{$fim}'";
                break;
        }

        $result = [];

        $matriz = $this->matrizFilial(TRUE);

        foreach ($matriz as $key => $filiais) {

            if (strtoupper($integrador) == 'SINTESE') {

                $query = "
            		SELECT 
            			x.competencia,
						x.ano,
						x.mes,
						x.cd_cotacao,
              			(CASE WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S'ELSE IF(x.depara = 1, 'S', 'N') END) depara, x.oferta,
              			x.nivel
            		FROM (
              			SELECT 
			                DATE_FORMAT(sint.dt_inicio_cotacao, '%Y-%m') competencia,
			                DATE_FORMAT(sint.dt_inicio_cotacao, '%Y') ano,
			                DATE_FORMAT(sint.dt_inicio_cotacao, '%m') mes,
			                sint.cd_cotacao,
                			(
                				SELECT DISTINCT sint.oferta 
                				FROM cotacoes_sintese.cotacoes sint2 
                				WHERE sint2.cd_cotacao = sint.cd_cotacao AND sint.id_fornecedor IN ({$filiais})
                			) depara,
                			IF(
                				(
                					SELECT COUNT(DISTINCT ofer.cd_cotacao)
                  					FROM pharmanexo.cotacoes_produtos ofer
                  					WHERE ofer.id_fornecedor IN ({$filiais})
					                    AND ofer.integrador = 'SINTESE'
					                    AND ofer.cd_cotacao = sint.cd_cotacao
					                    AND ofer.submetido = 1
					            ) > 0, 'S', 'N'
					        ) oferta,
                			IF(
                				(
                					SELECT GROUP_CONCAT(DISTINCT ofer.nivel ORDER BY ofer.nivel ASC)
					                FROM pharmanexo.cotacoes_produtos ofer
					                WHERE ofer.id_fornecedor IN ({$filiais})
										AND ofer.integrador = 'SINTESE'
					                    AND ofer.cd_cotacao = sint.cd_cotacao
					                    AND ofer.submetido = 1
					            ) = '1,2', 'S', 'N'
					        ) nivel
              			FROM cotacoes_sintese.cotacoes sint
              			WHERE {$where_periodo_sintese} AND sint.id_fornecedor IN ({$filiais})
              			GROUP BY competencia, ano, mes, sint.cd_cotacao
              		) x 
          		";
            } else {

                $query = "
	            	SELECT
						x.competencia,
		                x.ano,
		                x.mes,
		                x.cd_cotacao,
	                	(CASE WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S'ELSE IF(x.depara = 1, 'S', 'N') END) depara, x.oferta,
	                	x.nivel
	              	FROM (
	                	SELECT 
		                	DATE_FORMAT(bio.dt_inicio_cotacao, '%Y-%m') competencia,
		                  	DATE_FORMAT(bio.dt_inicio_cotacao, '%Y') ano,
							DATE_FORMAT(bio.dt_inicio_cotacao, '%m') mes,
							bio.cd_cotacao,
	                  		(SELECT DISTINCT bio.oferta FROM cotacoes_bionexo.cotacoes bio2 WHERE bio2.cd_cotacao = bio.cd_cotacao AND bio.id_fornecedor IN ({$filiais})) depara,
	                  		IF(
	                  			(
	                  				SELECT COUNT(DISTINCT ofer.cd_cotacao)
	                   				FROM pharmanexo.cotacoes_produtos ofer
	                    			WHERE ofer.id_fornecedor IN ({$filiais})
				                    	AND ofer.integrador = 'BIONEXO'
				                    	AND ofer.cd_cotacao = bio.cd_cotacao
				                    	AND ofer.submetido = 1
				                ) > 0, 'S', 'N'
				            ) oferta,
	                  		IF(
	                  			(
	                  				SELECT GROUP_CONCAT(DISTINCT ofer.nivel ORDER BY ofer.nivel ASC)
				                    FROM pharmanexo.cotacoes_produtos ofer
				                    WHERE ofer.id_fornecedor IN ({$filiais})
										AND ofer.integrador = 'BIONEXO'
										AND ofer.cd_cotacao = bio.cd_cotacao
										AND ofer.submetido = 1
								) = '1,2', 'S', 'N'
							) nivel
	        			FROM cotacoes_bionexo.cotacoes bio FORCE INDEX (IDX_GSYS_COTACOES_01)
		                WHERE {$where_periodo_bionexo} AND bio.id_fornecedor IN ({$filiais})
		                GROUP BY competencia, ano, mes, bio.cd_cotacao) x 
	            ";
            }

            $result[$key] = $this->db->query($query)->result_array();
        }

        $totalCot = [];
        $cotProd = [];
        $cotEnviada = [];

        foreach ($result as $key => $dados) {

            $arrDepara = [];
            $arrOferta = [];

            array_push($totalCot, count($dados));

            foreach ($dados as $dado) {

                if ($dado['depara'] == "S") {

                    $arrDepara[] = $dado;
                }

                if ($dado['depara'] == "S" && $dado['oferta'] == "S") {

                    if ($dado['nivel'] == 'S') {

                        $arrOferta[] = $dado;
                        $arrOferta[] = $dado;
                    } else {

                        $arrOferta[] = $dado;
                    }
                }
            }

            array_push($cotProd, count($arrDepara));
            array_push($cotEnviada, count($arrOferta));
        }

        $data['totalCot'] = $totalCot;
        $data['cotProd'] = $cotProd;
        $data['cotEnviada'] = $cotEnviada;
        $data['labels'] = array_keys($result);

        return $data;
    }

    /**
     * Obtem a quantidade de cotações, cot com produto e cot enviada por periodo
     *
     * @param - String nome do integrador
     * @param - String tipo de periodo
     * @return array
     */
    public function getDadosCotacaoMensal($id_fornecedor, $ano, $integrador)
    {

        $query = "
                SELECT 
		            x.competencia,
		            x.ano,
		            x.mes,
		            x.cd_cotacao,
		            (CASE WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S' ELSE IF(x.depara = 1, 'S', 'N') END) depara,
		            x.oferta,
		            x.nivel
          		FROM 
          			(
	            		SELECT 
							DATE_FORMAT(sint.dt_inicio_cotacao, '%Y-%m') competencia,
							DATE_FORMAT(sint.dt_inicio_cotacao, '%Y') ano,
							DATE_FORMAT(sint.dt_inicio_cotacao, '%m') mes,
							sint.cd_cotacao,
	              			(SELECT DISTINCT sint.oferta FROM cotacoes_sintese.cotacoes sint2 WHERE sint2.cd_cotacao = sint.cd_cotacao AND sint.id_fornecedor = {$id_fornecedor} ) depara,
	              			IF((SELECT COUNT(DISTINCT ofer.cd_cotacao) FROM pharmanexo.cotacoes_produtos ofer WHERE ofer.id_fornecedor = {$id_fornecedor} AND ofer.cd_cotacao = sint.cd_cotacao) > 0, 'S', 'N') oferta,
	              			IF(
	              				(
	              					SELECT 
	              						GROUP_CONCAT(DISTINCT ofer.nivel ORDER BY ofer.nivel ASC)
	                				FROM pharmanexo.cotacoes_produtos ofer
	                				WHERE ofer.id_fornecedor = {$id_fornecedor}
										AND ofer.cd_cotacao = sint.cd_cotacao
										AND ofer.submetido = 1
								) = '1,2', 'S', 'N'
							) nivel
	    				FROM cotacoes_sintese.cotacoes sint
	            		WHERE YEAR(sint.dt_inicio_cotacao) = '{$ano}'
	              			AND sint.id_fornecedor = {$id_fornecedor}
	            		GROUP BY competencia, ano, mes, sint.cd_cotacao 
	            	) x
				union
          		SELECT 
		            x.competencia,
		            x.ano,
		            x.mes,
		            x.cd_cotacao,
		            (CASE WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S'ELSE IF(x.depara = 1, 'S', 'N') END) depara,
		            x.oferta,
		            x.nivel
          		FROM 
          			(
            			SELECT 
							DATE_FORMAT(bio.dt_inicio_cotacao, '%Y-%m') competencia,
							DATE_FORMAT(bio.dt_inicio_cotacao, '%Y') ano,
							DATE_FORMAT(bio.dt_inicio_cotacao, '%m') mes,
							bio.cd_cotacao,
          					(
                				SELECT DISTINCT bio.oferta
                				FROM cotacoes_bionexo.cotacoes bio2
                				WHERE bio2.cd_cotacao = bio.cd_cotacao
                				AND bio.id_fornecedor = {$id_fornecedor}) depara,
                				IF(
                					(
                               			SELECT COUNT(DISTINCT ofer.cd_cotacao)
                               			FROM pharmanexo.cotacoes_produtos ofer
                               			WHERE ofer.id_fornecedor = {$id_fornecedor}
                                			AND ofer.cd_cotacao = CAST(bio.cd_cotacao AS CHAR(50))
                                			AND ofer.submetido = 1
                                	) > 0,
                       			'S', 'N') oferta,
                        	IF(
                        		(
                               		SELECT GROUP_CONCAT(DISTINCT ofer.nivel ORDER BY ofer.nivel ASC)
                               		FROM pharmanexo.cotacoes_produtos ofer
                               		WHERE ofer.id_fornecedor = {$id_fornecedor}
                                		AND ofer.cd_cotacao = CAST(bio.cd_cotacao AS CHAR(50))
                                		AND ofer.submetido = 1
                                ) = '1,2', 
                            'S', 'N') nivel
                 		FROM cotacoes_bionexo.cotacoes bio FORCE INDEX (IDX_GSYS_COTACOES_01)
            			WHERE YEAR(bio.dt_inicio_cotacao) = '{$ano}'
                   			AND bio.id_fornecedor = {$id_fornecedor}
                 		GROUP BY competencia, ano, mes, bio.cd_cotacao
                 	) x
        	";


        return $this->db->query($query)->result_array();
    }

    public function getDadosCotacaoMensalPorAnoMes($id_fornecedor, $ano, $mes, $integrador)
    {

        $query = "
                SELECT 
		            x.competencia,
		            x.ano,
		            x.mes,
		            x.cd_cotacao,
		            (CASE WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S' ELSE IF(x.depara = 1, 'S', 'N') END) depara,
		            x.oferta,
		            x.nivel
          		FROM 
          			(
	            		SELECT 
							DATE_FORMAT(sint.dt_inicio_cotacao, '%Y-%m') competencia,
							DATE_FORMAT(sint.dt_inicio_cotacao, '%Y') ano,
							DATE_FORMAT(sint.dt_inicio_cotacao, '%m') mes,
							sint.cd_cotacao,
	              			(SELECT DISTINCT sint.oferta FROM cotacoes_sintese.cotacoes sint2 WHERE sint2.cd_cotacao = sint.cd_cotacao AND sint.id_fornecedor = {$id_fornecedor} ) depara,
	              			IF((SELECT COUNT(DISTINCT ofer.cd_cotacao) FROM pharmanexo.cotacoes_produtos ofer WHERE ofer.id_fornecedor = {$id_fornecedor} AND ofer.cd_cotacao = sint.cd_cotacao) > 0, 'S', 'N') oferta,
	              			IF(
	              				(
	              					SELECT 
	              						GROUP_CONCAT(DISTINCT ofer.nivel ORDER BY ofer.nivel ASC)
	                				FROM pharmanexo.cotacoes_produtos ofer
	                				WHERE ofer.id_fornecedor = {$id_fornecedor}
										AND ofer.cd_cotacao = sint.cd_cotacao
										AND ofer.submetido = 1
								) = '1,2', 'S', 'N'
							) nivel
	    				FROM cotacoes_sintese.cotacoes sint
	            		WHERE YEAR(sint.dt_inicio_cotacao) = '{$ano}'
							AND MONTH(sint.dt_inicio_cotacao) = '{$mes}'
	              			AND sint.id_fornecedor = {$id_fornecedor}
	            		GROUP BY competencia, ano, mes, sint.cd_cotacao 
	            	) x
				union
          		SELECT 
		            x.competencia,
		            x.ano,
		            x.mes,
		            x.cd_cotacao,
		            (CASE WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S'ELSE IF(x.depara = 1, 'S', 'N') END) depara,
		            x.oferta,
		            x.nivel
          		FROM 
          			(
            			SELECT 
							DATE_FORMAT(bio.dt_inicio_cotacao, '%Y-%m') competencia,
							DATE_FORMAT(bio.dt_inicio_cotacao, '%Y') ano,
							DATE_FORMAT(bio.dt_inicio_cotacao, '%m') mes,
							bio.cd_cotacao,
          					(
                				SELECT DISTINCT bio.oferta
                				FROM cotacoes_bionexo.cotacoes bio2
                				WHERE bio2.cd_cotacao = bio.cd_cotacao
                				AND bio.id_fornecedor = {$id_fornecedor}) depara,
                				IF(
                					(
                               			SELECT COUNT(DISTINCT ofer.cd_cotacao)
                               			FROM pharmanexo.cotacoes_produtos ofer
                               			WHERE ofer.id_fornecedor = {$id_fornecedor}
                                			AND ofer.cd_cotacao = CAST(bio.cd_cotacao AS CHAR(50))
                                			AND ofer.submetido = 1
                                	) > 0,
                       			'S', 'N') oferta,
                        	IF(
                        		(
                               		SELECT GROUP_CONCAT(DISTINCT ofer.nivel ORDER BY ofer.nivel ASC)
                               		FROM pharmanexo.cotacoes_produtos ofer
                               		WHERE ofer.id_fornecedor = {$id_fornecedor}
                                		AND ofer.cd_cotacao = CAST(bio.cd_cotacao AS CHAR(50))
                                		AND ofer.submetido = 1
                                ) = '1,2', 
                            'S', 'N') nivel
                 		FROM cotacoes_bionexo.cotacoes bio FORCE INDEX (IDX_GSYS_COTACOES_01)
            			WHERE YEAR(bio.dt_inicio_cotacao) = '{$ano}'
							AND MONTH(bio.dt_inicio_cotacao) = '{$mes}'
                   			AND bio.id_fornecedor = {$id_fornecedor}
                 		GROUP BY competencia, ano, mes, bio.cd_cotacao
                 	) x
        	";


        return $this->db->query($query)->result_array();
    }

    public function getDadosCotacaoMensalCalculadaPorAnoMes($id_fornecedor, $ano, $mes)
    {

        $query = "SELECT ano, mes, total_cot, cot_com_prod, cot_enviada, porcentagem
		FROM pharmanexo.grafico_fornecedores
		where id_fornecedor = {$id_fornecedor}
		and ano= {$ano}
		and mes ={$mes}";

        return $this->db->query($query)->result_array();
    }

    /**
     * Obtem o total de cotações de acordo com o nivel por fornecedor
     *
     * @param - String nome do integrador
     * @param - String tipo do periodo
     * @param - INT ID do fornecedor
     * @param - INT nivel da oferta
     * @return array
     */
    public function cotacoesPorFornecedor($integrador, $periodo, $id_fornecedor, $nivel)
    {

        $this->db->select('count(DISTINCT cd_cotacao) as total');

        switch ($periodo) {
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());
                $this->db->where("month(data_cotacao) = '{$mes}' and year(data_cotacao) = '{$ano}'");
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());
                $this->db->where("date(data_cotacao) between '{$inicio}' and '{$fim}'");
                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());
                $this->db->where("date(data_cotacao) between '{$inicio}' and '{$fim}'");
                break;
        }

        $this->db->where('submetido', 1);
        $this->db->where('nivel', $nivel);
        $this->db->where('integrador', $integrador);
        $this->db->where("id_fornecedor IN ({$id_fornecedor})");

        $result = $this->db->get('cotacoes_produtos')->row_array();

        return (isset($result) && !empty($result['total'])) ? $result['total'] : 0;
    }

    /**
     * Obtem o valor total cotado por periodo
     *
     * @param - String nome do integrador
     * @param - String tipo do periodo
     * @return array
     */
    public function getQuotePriceSent($integrador, $periodo)
    {

        switch ($periodo) {
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());
                $temp = "MONTH(cp.data_cotacao) = '{$mes}' AND YEAR(data_cotacao) = '{$ano}'";
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());
                $temp = "DATE(cp.data_cotacao) BETWEEN '{$inicio}' AND '{$fim}'";
                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());
                $temp = "DATE(cp.data_cotacao) BETWEEN '{$inicio}' AND '{$fim}'";
                break;
        }

        $query = "
        	SELECT 
          		SUM(sub.total) AS total,
				(CASE WHEN sub.matriz IS NULL THEN sub.nome_fantasia ELSE sub.matriz END) AS matriz
        	FROM (
          		SELECT 
            		SUM(cp.qtd_solicitada * cp.preco_marca) AS total,
        			f.nome_fantasia,
            		(SELECT mtz.nome FROM pharmanexo.fornecedores_matriz mtz WHERE mtz.id = IF(f.id_matriz IS NOT NULL, f.id_matriz, 0)) AS matriz
          		FROM pharmanexo.cotacoes_produtos cp
          		JOIN pharmanexo.fornecedores f ON f.id = cp.id_fornecedor
          		WHERE {$temp}
    				AND cp.submetido = 1
            		AND cp.integrador = '{$integrador}'
          		GROUP BY f.nome_fantasia
          		ORDER BY f.nome_fantasia ASC) sub
        	GROUP BY (CASE WHEN sub.matriz IS NULL THEN sub.nome_fantasia ELSE sub.matriz END)
		";

        return $this->db->query($query)->result_array();
    }
}