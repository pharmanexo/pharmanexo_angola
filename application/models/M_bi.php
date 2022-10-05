<?php

class M_bi extends MY_Model
{
    protected $DB_COTACAO;

    public function __construct()
    {

        parent::__construct();

        $this->load->model('m_estados', 'estado');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_configAnaliseMercado', 'analiseMercado');

        $this->DB_COTACAO = $this->load->database('sintese', TRUE);
    }

    /**
     * Ajusta a condição WHERE das funções de acordo com o POST do filtro
     *
     * @param - POST filtro
     * @param - Array (INT id do comprador, INt id do estado)
     * @return array
     */
    public function manipulaWhere($post, $params)
    {

        $new_where = "";

        if (!empty($post['id_cliente']) && empty($post['uf_cotacao'])) {

            $new_where = "AND {$params['cliente']} = {$post['id_cliente']}";

        } else if (empty($post['id_cliente']) && !empty($post['uf_cotacao'])) {

            $new_where = "AND {$params['estado']} = '{$post['uf_cotacao']}'";

        } else if (!empty($post['id_cliente']) && !empty($post['uf_cotacao'])) {

            $new_where = "AND {$params['cliente']} = {$post['id_cliente']} AND {$params['estado']} = '{$post['uf_cotacao']}'";
        }

        return $new_where;
    }

    /**
     * Obtem a lista de fornecedores da filial
     *
     * @param - bool
     * @param - bool
     * @param - INT ID do fornecedor
     * @return array
     */
    public function matrizFilial($trueArray = true, $notMatriz = false, $id_fornecedor = 0)
    {

        $where_ = "";

        if ($id_fornecedor !== 0)
            $where_ = "AND f1.id = {$id_fornecedor}";

        $arr = [];

        $query = "
            SELECT 
                x.filiais,
                x.nome_fantasia,
                IF(x.matriz IS NULL, x.nome_fantasia, x.matriz) matriz
            FROM (SELECT 
                    (SELECT CASE WHEN GROUP_CONCAT(f2.id) IS NULL
                    THEN f1.id ELSE GROUP_CONCAT(f2.id) END
                    FROM pharmanexo.fornecedores f2
                    WHERE f2.id_matriz = f1.id_matriz) filiais,
                IF(f1.id_matriz, 'S', 'N') sn_filiais,
                (SELECT mtz.nome
                    FROM pharmanexo.fornecedores_matriz mtz
                    WHERE mtz.id = f1.id_matriz) matriz,
                f1.nome_fantasia
                FROM pharmanexo.fornecedores f1
                WHERE f1.sintese = 1 {$where_}
                GROUP BY filiais, sn_filiais, matriz, f1.nome_fantasia) x
            GROUP BY x.filiais, x.nome_fantasia, matriz
            ORDER BY matriz ASC
        ";

        //var_dump($query); exit();

        $getFiliais = $this->db->query($query)->result_array();

        if (!$trueArray) {

            if ($notMatriz && $id_fornecedor !== 0) {

                $arr[$getFiliais[0]['nome_fantasia']] = $id_fornecedor;

                return $arr;

            }

            foreach ($getFiliais as $getFilial)
                $arr[$getFilial['matriz']] = $getFilial['filiais'];

            return $arr;
        } else {

            foreach ($getFiliais as $getFilial)
                $arr[$getFilial['matriz']] = explode(',', $getFilial['filiais']);

            function myInt($n)
            {
                return intval($n);
            }

            foreach ($arr as $key => $ar)
                $newArr[$key] = array_map("myInt", $ar);

            return $newArr;
        }
    }

    /**
     * Obtem a quantiade de registros de venda diferenciada por regra de venda
     *
     * @param - POST filtros
     * @return array
     */
    public function getValuesRegraVenda($post)
    {
        $params = [
            'cliente' => 'id_cliente',
            'estado' => 'id_estado'
        ];

        $new_where = $this->manipulaWhere($post, $params);

        var_dump($new_where);
        exit();

        return $this->db->query("SELECT (SELECT COUNT(DISTINCT codigo) FROM vendas_diferenciadas where id_fornecedor = {$this->session->id_fornecedor} and desconto_percentual > 0 {$new_where}) AS DESCONTO,
                                    (SELECT COUNT(DISTINCT codigo) FROM vendas_diferenciadas where id_fornecedor = {$this->session->id_fornecedor} and regra_venda in (2,3,6) {$new_where}) AS AUTOMATICO,
                                    (SELECT COUNT(DISTINCT codigo) FROM vendas_diferenciadas where id_fornecedor = {$this->session->id_fornecedor} and regra_venda in (4,5,6) {$new_where}) AS DISTRIBUIDOR,
                                    (SELECT COUNT(DISTINCT codigo) FROM vendas_diferenciadas where id_fornecedor = {$this->session->id_fornecedor} and promocao = 1 {$new_where}) AS PROMOCAO")->row_array();
    }

    /**
     * Obtem a quantiade total de cotações
     *
     * @param - POST filtros
     * @return array
     */
    public function totalCotacoes($array)
    {

        $params = [
            'cliente' => 'cot.id_cliente',
            'estado' => 'cot.uf_cotacao'
        ];

        $new_where = $this->manipulaWhere($array, $params);

        $new_where2 = str_replace('cot.', 'cot1.', $new_where);

        $query = "
            SELECT COUNT(DISTINCT cot.cd_cotacao)     qtd_cotacao_total,

            (SELECT COUNT(DISTINCT cot1.cd_cotacao)
                FROM cotacoes_sintese.cotacoes cot1
                WHERE DATE(cot1.dt_inicio_cotacao) 
                    BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
                  AND cot1.id_fornecedor IN ({$array['fornecedor']})
                  {$new_where2}) qtd_cot_logado

                        FROM cotacoes_sintese.cotacoes cot

                        WHERE DATE(cot.dt_inicio_cotacao) 
                                BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
                                
                                {$new_where}
        ";


        $result = $this->db->query($query)->row_array();

        return $result;
    }

    /**
     * Obtem as informações das cotações do fornecedor por periodo
     *
     * @param - POST filtros
     * @param - GET - filtros datatable
     * @return array
     */
    public function dadosCotacao($array, $obj)
    {
        $params = [
            'cliente' => 'cot.id_cliente',
            'estado' => 'cot.uf_cotacao'
        ];

        $new_where = $this->manipulaWhere($array, $params);

        $this->DB_COTACAO->start_cache();

        $query = "
            SELECT 
                x.cd_cotacao,
                x.cnpj,
                x.razao_social,
                x.dt_inicio_cotacao,
                x.dt_fim_cotacao,
                x.uf_cotacao,
                (CASE
                    WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S'
                    ELSE IF(x.depara = 1, 'S', 'N') END) depara,
                x.oferta
            FROM (SELECT cot.cd_cotacao,
                comp.cnpj,
                comp.razao_social,
                cot.dt_inicio_cotacao,
                cot.dt_fim_cotacao,
                cot.uf_cotacao,
                (SELECT DISTINCT cot.oferta
                    FROM cotacoes_sintese.cotacoes sint2
                    WHERE sint2.cd_cotacao = cot.cd_cotacao
                        AND cot.id_fornecedor IN ({$array['fornecedor']}))            depara,
                IF((SELECT COUNT(DISTINCT ofer.cd_cotacao)
                    FROM pharmanexo.cotacoes_produtos ofer
                    WHERE ofer.id_fornecedor IN ({$array['fornecedor']}) 
                       AND ofer.cd_cotacao = cot.cd_cotacao
                       AND ofer.submetido = 1) > 0, 'S', 'N') oferta
                FROM cotacoes_sintese.cotacoes cot
                JOIN pharmanexo.compradores comp
                    ON comp.id = cot.id_cliente

                WHERE DATE(cot.dt_inicio_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
            AND cot.id_fornecedor IN ({$array['fornecedor']})
            {$new_where}
            GROUP BY cot.cd_cotacao,
                comp.cnpj,
                comp.razao_social,
                cot.dt_inicio_cotacao,
                cot.dt_fim_cotacao,
                cot.uf_cotacao
            ORDER BY cot.dt_inicio_cotacao ASC, cot.cd_cotacao) x
            GROUP BY 
                x.cd_cotacao,
                x.cnpj,
                x.razao_social,
                x.uf_cotacao
        ";

        # Order by
        if (isset($obj['order'])) {

            $order_by = " ORDER BY ";

            foreach ($obj['order'] as $order) {

                if (isset($obj['columns'][$order['column']])) {

                    $columnName = $obj['columns'][$order['column']]['name'];

                    $order_by .= "{$columnName} {$order['dir']}, ";
                }
            }

            $order_by = trim($order_by, ', ');

            $query = $query . $order_by;
        }

        $result = $this->DB_COTACAO->query($query);

        $totalResulted = $result->num_rows();

        $this->DB_COTACAO->stop_cache();

        if (isset($obj['start']) && $obj['length'] && $obj['length'] > 0) {

            $limit = " LIMIT {$obj['length']} OFFSET {$obj['start']}";
            $query = $query . $limit;
        }

        $datatableResultado = $this->DB_COTACAO->query($query)->result_array();

        foreach ($datatableResultado as $kk => $row) {
            $comprador = trim(substr($row['razao_social'], 0, 15));

            $datatableResultado[$kk]['id_fornecedor'] = $array['id_fornecedor'];
            $datatableResultado[$kk]['comprador'] = "<small>{$comprador}...</small>";
            $datatableResultado[$kk]['dt_inicio_cotacao'] = date("d/m/Y H:i", strtotime($row['dt_inicio_cotacao']));
            $datatableResultado[$kk]['dt_fim_cotacao'] = date("d/m/Y H:i", strtotime($row['dt_fim_cotacao']));

            $this->DB_COTACAO->where("cd_cotacao", $row['cd_cotacao']);
            $this->DB_COTACAO->where("id_fornecedor", $array['id_fornecedor']);
            $datatableResultado[$kk]["qtd_itens"] = $this->DB_COTACAO->count_all_results('cotacoes_produtos');
        }

        $data = [
            "draw" => isset ($obj['draw']) ? intval($obj['draw']) : 0,
            "recordsTotal" => $totalResulted,
            "recordsFiltered" => $totalResulted,
            "data" => $datatableResultado
        ];

        return $data;
    }

    /**
     * Obtem as informações das cotações que nao pertence ao fornecedor por periodo
     *
     * @param - POST filtros
     * @param - GET - filtros datatable
     * @return array
     */
    public function dadosCotacaoNot($array, $obj)
    {
        $params =
            [
                'cliente' => 'cot.id_cliente',
                'estado' => 'cot.uf_cotacao'
            ];

        $new_where = $this->manipulaWhere($array, $params);

        $this->DB_COTACAO->start_cache();

        $query = "
            SELECT 
                cot.cd_cotacao,
                comp.cnpj,
                comp.razao_social,
                cot.dt_inicio_cotacao,
                cot.dt_fim_cotacao,
                cot.uf_cotacao,
               
                (SELECT COUNT(prods.id_produto_sintese)
                    FROM cotacoes_sintese.cotacoes_produtos prods
                    WHERE prods.cd_cotacao = cot.cd_cotacao
                        AND prods.id_fornecedor = cot.id_fornecedor)           qtd_itens
                FROM cotacoes_sintese.cotacoes cot
                JOIN pharmanexo.compradores comp
                    ON comp.id = cot.id_cliente

                WHERE DATE(cot.dt_inicio_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
            
            {$new_where}

            AND cot.cd_cotacao NOT IN (SELECT cot.cd_cotacao

                             FROM cotacoes_sintese.cotacoes cot
                                      JOIN pharmanexo.compradores comp ON comp.id = cot.id_cliente
                             WHERE DATE(cot.dt_inicio_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
                               AND cot.id_fornecedor IN  ({$array['fornecedor']})
                                {$new_where}
                             GROUP BY cot.cd_cotacao
                             ORDER BY cot.dt_inicio_cotacao ASC, cot.cd_cotacao)
            GROUP BY cot.cd_cotacao,
                comp.cnpj,
                comp.razao_social,
                cot.uf_cotacao
        ";

        # Order by
        if (isset($obj['order'])) {

            $order_by = " ORDER BY ";

            foreach ($obj['order'] as $order) {

                if (isset($obj['columns'][$order['column']])) {

                    $columnName = $obj['columns'][$order['column']]['name'];

                    $order_by .= "{$columnName} {$order['dir']}, ";
                }
            }

            $order_by = trim($order_by, ', ');

            $query = $query . $order_by;
        }

        $result = $this->DB_COTACAO->query($query);

        $totalResulted = $result->num_rows();

        $this->DB_COTACAO->stop_cache();

        if (isset($obj['start']) && $obj['length'] && $obj['length'] > 0) {

            $limit = " LIMIT {$obj['length']} OFFSET {$obj['start']}";
            $query = $query . $limit;
        }

        $datatableResultado = $this->DB_COTACAO->query($query)->result_array();

        foreach ($datatableResultado as $kk => $row) {
            $comprador = trim(substr($row['razao_social'], 0, 15));

            $datatableResultado[$kk]['id_fornecedor'] = $array['id_fornecedor'];
            $datatableResultado[$kk]['comprador'] = "<small>{$comprador}...</small>";
            $datatableResultado[$kk]['dt_inicio_cotacao'] = date("d/m/Y H:i", strtotime($row['dt_inicio_cotacao']));
            $datatableResultado[$kk]['dt_fim_cotacao'] = date("d/m/Y H:i", strtotime($row['dt_fim_cotacao']));
        }

        $data = [
            "draw" => isset ($obj['draw']) ? intval($obj['draw']) : 0,
            "recordsTotal" => $totalResulted,
            "recordsFiltered" => $totalResulted,
            "data" => $datatableResultado
        ];

        return $data;
    }

    /**
     * Obtem as informações para os indicadores do BI
     *
     * @param - POST filtros
     * @return array
     */
    public function indicadoresCotacao($array)
    {

        $params = [
            'cliente' => 'cot.id_cliente',
            'estado' => 'cot.uf_cotacao'
        ];

        $new_where = $this->manipulaWhere($array, $params);

        $new_where2 = str_replace('cot.', 'sint.', $new_where);

        $query = "
            SELECT COUNT(cd_cotacao)            total_cotacao,
                SUM(IF(y.depara = 'S', 1, 0)) total_depara,
                SUM(IF(y.oferta = 'S', 1, 0)) total_oferta

                FROM (SELECT x.competencia,
                         x.ano,
                         x.mes,
                         x.cd_cotacao,
                         x.id_cliente,
                         x.comprador,
                         x.uf_cotacao,
                         (CASE
                              WHEN IF(x.depara = 1, 'S', 'N') = 'N' AND x.oferta = 'S' THEN 'S'
                              ELSE IF(x.depara = 1, 'S', 'N') END) depara,
                         x.oferta,
                         x.nivel

                FROM (SELECT DATE_FORMAT(sint.dt_inicio_cotacao, '%Y-%m')                                                competencia,
                               DATE_FORMAT(sint.dt_inicio_cotacao, '%Y')                                                   ano,
                               DATE_FORMAT(sint.dt_inicio_cotacao, '%m')                                                   mes,
                               sint.cd_cotacao,
                               sint.id_cliente,
                               (SELECT comp.razao_social
                                FROM pharmanexo.compradores comp
                                WHERE comp.id = sint.id_cliente)                                                           comprador,
                               sint.uf_cotacao,

                               (SELECT DISTINCT sint.oferta
                                FROM cotacoes_sintese.cotacoes sint2
                                WHERE sint2.cd_cotacao = sint.cd_cotacao
                                  AND sint.id_fornecedor IN ({$array['fornecedor']}))                            depara,


                               IF((SELECT COUNT(DISTINCT ofer.cd_cotacao)
                                   FROM pharmanexo.cotacoes_produtos ofer
                                   WHERE ofer.id_fornecedor IN ({$array['fornecedor']})
                                     AND ofer.cd_cotacao = sint.cd_cotacao
                                     AND ofer.submetido = 1) > 0, 'S', 'N')                                                oferta,

                               IF((SELECT GROUP_CONCAT(DISTINCT ofer.nivel ORDER BY ofer.nivel ASC)
                                   FROM pharmanexo.cotacoes_produtos ofer
                                   WHERE ofer.id_fornecedor IN ({$array['fornecedor']})
                                     AND ofer.cd_cotacao = sint.cd_cotacao
                                     AND ofer.submetido = 1) = '1,2', 'S', 'N')                                            nivel


                        FROM cotacoes_sintese.cotacoes sint

                        WHERE DATE(sint.dt_inicio_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
                        
                        {$new_where2}

                          AND sint.id_fornecedor IN ({$array['fornecedor']})

                        GROUP BY competencia,
                            ano,
                            mes,
                            sint.cd_cotacao,
                            sint.id_cliente,
                            sint.uf_cotacao) x) y";

        return $this->db->query($query)->row_array();
    }

    /**
     * Obtem o total ofertado
     *
     * @param - POST filtros
     * @return array
     */
    public function getTotalOferta($array)
    {

        $params = [
            'cliente' => 'cot.id_cliente',
            'estado' => 'cot.uf_cotacao'
        ];

        $new_where = $this->manipulaWhere($array, $params);

        $new_where = str_replace('cot.uf_cotacao', 'comp.estado', $new_where);

        $new_where2 = str_replace('cot.', 'oferta.', $new_where);

        $query = "
            SELECT
                SUM(x.total_oferta) total_ofertado
                FROM (SELECT oferta.cd_cotacao,
                             oferta.id_sintese,
                             oferta.qtd_solicitada,
                             oferta.preco_marca,
                             (oferta.qtd_solicitada * oferta.preco_marca) total_oferta
        FROM pharmanexo.cotacoes_produtos oferta
        JOIN pharmanexo.compradores comp
            ON comp.id = oferta.id_cliente

        WHERE oferta.id_fornecedor IN ({$array['fornecedor']})

            AND DATE(oferta.data_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'

            AND oferta.submetido = 1
        
            {$new_where2}
            GROUP BY oferta.cd_cotacao,
                oferta.id_sintese,
                oferta.qtd_solicitada,
                oferta.preco_marca) x";

        return $this->db->query($query)->row_array();
    }

    /**
     * Obtem o valor total dos produtos por periodo
     *
     * @param - INT ID do fornecedor
     * @param - INT ID do estado do comprador
     * @param - String Periodo inicial
     * @param - String Periodo final
     * @return array
     */
    public function valorTotalProdutosPorValidade($id_fornecedor, $id_estado, $periodoInicial, $periodoFinal = null)
    {
        $forns = [12, 104, 111, 115, 123, 120, 126, 15, 180, 25, 5002, 1002, 5039, 5038, 5018];
        if (in_array($id_fornecedor, $forns)) {
            $estado = " = {$id_estado}";
        } elseif ($id_fornecedor == 112) {
            $estado = " = 9";
        } elseif ($id_fornecedor == 20) {
            $estado = " = 8";
        } else {
            $estado = " is null";
        }

        if ($this->session->id_fornecedor == 104) {

            $condicao_nestle_biohosp = "  AND ( pc.id_marca != 201 AND pc.marca not like '%nestle%' ) ";
        } else {

            $condicao_nestle_biohosp = "";
        }


        if (!isset($periodoFinal)) {

            $where_periodo = " DATE_FORMAT(pl.validade, '%Y-%m-%d') > '{$periodoInicial}' ";
        } else {

            $where_periodo = " DATE_FORMAT(pl.validade, '%Y-%m-%d') BETWEEN '{$periodoInicial}' AND '{$periodoFinal}' ";
        }

        $query = "
            SELECT SUM(y.preco_unitario * (y.estoque * y.qtd_unidade) ) preco_total FROM
                (SELECT 
                    x.codigo,
                    x.nome_comercial,
                    x.marca,
                    x.lote,
                    x.estoque,
                    x.qtd_unidade,
                    x.estoque_total,
                    x.validade,
                    x.preco,
                    (CASE WHEN x.id_fornecedor IN (12, 104, 111, 115, 123, 120, 126, 15, 180, 25, 5002, 1002, 5039, 5038, 5010, 5018) THEN (x.preco / x.qtd_unidade) ELSE (x.preco) END) preco_unitario
                FROM (
                    SELECT 
                        pl.id_fornecedor,
                        pl.codigo,
                        pc.nome_comercial,
                        pc.marca,
                        pl.lote,
                        pl.estoque,
                        IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) qtd_unidade,
                        (pl.estoque * IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade)) estoque_total,
                        pl.validade,
                        (SELECT pp.preco_unitario
                            FROM pharmanexo.produtos_preco_max pp
                            WHERE pp.id_fornecedor = pl.id_fornecedor
                                AND pp.codigo = pl.codigo
                                AND pp.id_estado {$estado} LIMIT 1) preco
                    FROM pharmanexo.produtos_lote pl
                    JOIN pharmanexo.produtos_catalogo pc on pc.codigo = pl.codigo AND pc.id_fornecedor = pl.id_fornecedor AND pc.ativo = 1 AND pc.bloqueado = 0
                    WHERE pl.id_fornecedor = {$id_fornecedor}
                        AND {$where_periodo}
                        AND pl.fixo != 1
                    GROUP BY 
                        pl.id_fornecedor,
                        pl.codigo,
                        pl.lote,
                        pl.estoque,
                        pl.validade,
                        pc.nome_comercial,
                        pc.marca
                    HAVING estoque_total > 0
                ) x  
                WHERE  x.preco is not null) y
        ";

        return $this->db->query($query)->row_array()['preco_total'];
    }

    /**
     * Obtem a lista dos produtos por periodo
     *
     * @param - INT ID do fornecedor
     * @param - INT ID do estado do comprador
     * @param - String Periodo inicial
     * @param - String Periodo final
     * @return array
     */
    public function produtosPorValidade($obj, $id_fornecedor, $id_estado, $periodoInicial, $periodoFinal = null)
    {

        $this->db->start_cache();

        if (in_array($id_fornecedor, [12, 20, 104, 111, 115, 123, 120, 126, 15, 180, 25, 5032, 5033, 5010])) {
            $estado = " = {$id_estado}";
        } elseif ($id_fornecedor == 112) {

            $estado = " = 8";
        } else {

            $estado = " is null";
        }

        if ($this->session->id_fornecedor == 104) {

            $condicao_nestle_biohosp = "  AND ( pc.id_marca != 201 AND pc.marca not like '%nestle%' ) ";
        } else {

            $condicao_nestle_biohosp = "";
        }


        if (!isset($periodoFinal)) {

            $where_periodo = " DATE_FORMAT(pl.validade, '%Y-%m-%d') > '{$periodoInicial}' ";
        } else {

            $where_periodo = " DATE_FORMAT(pl.validade, '%Y-%m-%d') BETWEEN '{$periodoInicial}' AND '{$periodoFinal}' ";
        }

        $query = "
            SELECT 
                x.codigo,
                x.nome_comercial,
                x.marca,
                x.lote,
                x.estoque,
                x.qtd_unidade,
                x.estoque_total,
                x.validade,
                x.preco,
                (CASE WHEN x.id_fornecedor IN (12, 111, 112, 115, 120, 123, 126, 104, 20) THEN (x.preco / x.qtd_unidade) ELSE (x.preco) END) preco_unitario
            FROM (
                SELECT 
                    pl.id_fornecedor,
                    pl.codigo,
                    pc.nome_comercial,
                    pc.marca,
                    pl.lote,
                    pl.estoque,
                    IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) qtd_unidade,
                    (pl.estoque * IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade)) estoque_total,
                    pl.validade,
                    (SELECT pp.preco_unitario
                        FROM pharmanexo.produtos_preco pp
                        WHERE pp.id_fornecedor = pl.id_fornecedor
                            AND pp.codigo = pl.codigo
                            AND pp.id_estado {$estado}
                            AND pp.data_criacao = (SELECT 
                                                        MAX(p.data_criacao)
                                                   FROM pharmanexo.produtos_preco p
                                                   WHERE p.id_fornecedor = pp.id_fornecedor
                                                        AND p.codigo = pp.codigo
                                                        AND p.id_estado {$estado}) LIMIT 1) preco
                FROM pharmanexo.produtos_lote pl
                JOIN pharmanexo.produtos_catalogo pc on pc.codigo = pl.codigo AND pc.id_fornecedor = pl.id_fornecedor AND pc.ativo = 1 AND pc.bloqueado = 0
                WHERE pl.id_fornecedor = {$id_fornecedor}
                    AND {$where_periodo}
                    {$condicao_nestle_biohosp}
                    AND pl.fixo != 1
                GROUP BY 
                    pl.id_fornecedor,
                    pl.codigo,
                    pl.lote,
                    pl.estoque,
                    pl.validade,
                    pc.nome_comercial,
                    pc.marca
                HAVING estoque_total > 0
            ) x  
            WHERE  x.preco is not null
        ";

        $countResult = $this->db->query($query);

        $totalResulted = $countResult->num_rows();

        $this->db->stop_cache();

        # Order by
        if (isset($obj['order'])) {

            $order_by = " ORDER BY ";

            foreach ($obj['order'] as $order) {

                if (isset($obj['columns'][$order['column']])) {

                    $columnName = $obj['columns'][$order['column']]['data'];

                    $order_by .= "{$columnName} {$order['dir']}, ";
                }
            }

            $order_by = trim($order_by, ', ');

            $query = $query . $order_by;
        }

        # Paginate
        if (isset($obj['start']) && $obj['length'] && $obj['length'] > 0) {

            $limit = " LIMIT {$obj['length']} OFFSET {$obj['start']}";
            $query = $query . $limit;
        }

        $result = $this->db->query($query)->result_array();

        foreach ($result as $key => $row) {

            $result[$key]['preco_unitario'] = number_format($row['preco_unitario'], 4, ',', '.');
        }

        $data = [
            "draw" => isset ($obj['draw']) ? intval($obj['draw']) : 0,
            "recordsTotal" => $totalResulted,
            "recordsFiltered" => $totalResulted,
            "data" => $result
        ];

        return $data;
    }

    /**
     * Obtem a lista das ultimas ofertas agrupado por comprador e produto
     *
     * @param - POST filtros
     * @return array
     */
    public function produtosPreco($array)
    {
        $data = [];

        if (isset($array['id_cliente']) && !empty($array['id_cliente'])) {

            $where_cliente = " AND prods.id_cliente = {$array['id_cliente']} AND ";
        } else {

            if (isset($array['notPage'])) {

                $limitQuery = "";
            } else {

                $limit = 5;
                $offset = ($array['page'] > 0) ? $array['page'] * $limit : 0;

                $limitQuery = "LIMIT {$limit} OFFSET {$offset} ";
            }

            $getClientes = $this->db->query("
                SELECT DISTINCT cp.id_cliente
                FROM cotacoes_produtos cp
                WHERE cp.id_fornecedor IN ({$array['fornecedor']}) 
                    AND DATE(cp.data_cotacao) BETWEEN '{$array['dt_inicio']}' 
                    AND '{$array['dt_fim']}'
                    AND cp.submetido = 1
                    AND cp.controle = 1
                    AND cp.ocultar = 0
                    AND cp.preco_marca > 0
            ")->result_array();

            $ids = [];

            foreach ($getClientes as $key => $value) {

                $ids[] = $value['id_cliente'];
            }

            $ids = implode(',', $ids);

            $where_cliente = " AND prods.id_cliente in ({$ids}) AND ";
        }

        if (isset($array['cd_produto']) && !empty($array['cd_produto'])) {
            $where_cliente .= "pc.codigo = {$array['cd_produto']} AND ";
        }

        if (!empty($where_cliente)) {
            $where_cliente = rtrim($where_cliente, 'AND ');
        }

        if (isset($ids) && empty($ids)) {

            return $data;
        }

        $query = "
            SELECT 
               prods.id_fornecedor,
               prods.id_cliente,
               comp.cnpj,
               comp.razao_social,
               pc.codigo,
               pc.nome_comercial,
               pc.descricao,
               pc.apresentacao
            FROM pharmanexo.compradores comp
            JOIN pharmanexo.cotacoes_produtos prods ON comp.id = prods.id_cliente
            JOIN pharmanexo.produtos_catalogo pc ON pc.codigo = prods.id_pfv AND pc.id_fornecedor = prods.id_fornecedor
            WHERE prods.id_fornecedor IN ({$array['fornecedor']})
                {$where_cliente}
                AND prods.submetido = 1
                AND prods.controle = 1
                AND prods.ocultar = 0
                AND prods.preco_marca > 0
                AND DATE(prods.data_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
            GROUP BY prods.id_pfv, prods.id_cliente
            ORDER BY comp.razao_social ASC
        ";

        $result = $this->db->query($query)->result_array();

        # Para cada produto encontrado, obtem o preço
        foreach ($result as $kk => $row) {

            $queryPreco = "
                SELECT cp.preco_marca, cp.id_fornecedor, cp.id_pfv, cp.id_cliente, cp.data_cotacao
                FROM pharmanexo.cotacoes_produtos cp
                WHERE cp.id_fornecedor = {$row['id_fornecedor']}
                    AND cp.id_cliente = {$row['id_cliente']}
                    AND cp.id_pfv = {$row['codigo']}
                    AND cp.submetido = 1
                    AND cp.controle = 1
                    AND cp.ocultar = 0
                    AND DATE(cp.data_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
                ORDER BY cp.data_cotacao DESC
                LIMIT 5
            ";

            $result[$kk]['ultimos_precos'] = $this->db->query($queryPreco)->result_array();
        }

        # Agrupa os produtos por comprador
        foreach ($result as $kk => $row) {

            $ultimos_precos = [];

            foreach ($row['ultimos_precos'] as $precos) {

                if ($precos['preco_marca'] != 0) {

                    $ultimos_precos[] = [
                        'value' => $precos['preco_marca'],
                        'data' => date('d/m/Y H:i', strtotime($precos['data_cotacao'])),
                        'dataOriginal' => $precos['data_cotacao'],
                        'format' => number_format($precos['preco_marca'], 4, ',', '.')
                    ];
                }
            }

            # Ordena os preços
            array_multisort(array_column($ultimos_precos, 'dataOriginal'), SORT_ASC, $ultimos_precos);

            $listaPrecos = array_column($ultimos_precos, 'value');

            $media = array_sum($listaPrecos) / count($listaPrecos);

            if (isset($data[$row['id_cliente']])) {

                $data[$row['id_cliente']]['produtos'][] = [
                    'nome_comercial' => $row['nome_comercial'],
                    'id_fornecedor' => $row['id_fornecedor'],
                    'codigo' => $row['codigo'],
                    'descricao' => $row['descricao'],
                    'ultimos_precos' => $ultimos_precos,
                    'mediaFormatada' => number_format($media, 4, ',', '.'),
                    'media' => $media,
                    'min' => min($listaPrecos),
                    'max' => max($listaPrecos)
                ];
            } else {

                $data[$row['id_cliente']]['cnpj'] = $row['cnpj'];
                $data[$row['id_cliente']]['razao_social'] = $row['razao_social'];
                $data[$row['id_cliente']]['produtos'][] = [
                    'nome_comercial' => $row['nome_comercial'],
                    'id_fornecedor' => $row['id_fornecedor'],
                    'codigo' => $row['codigo'],
                    'descricao' => $row['descricao'],
                    'ultimos_precos' => $ultimos_precos,
                    'mediaFormatada' => number_format($media, 4, ',', '.'),
                    'media' => $media,
                    'min' => min($listaPrecos),
                    'max' => max($listaPrecos)
                ];
            }
        }

        # Ordena os compradores
        array_multisort(array_column($data, 'razao_social'), SORT_ASC, $data);

        # Ordena os produtos
        foreach ($data as $key => $rowComprador) {

            array_multisort(array_column($rowComprador['produtos'], 'nome_comercial'), SORT_ASC, $rowComprador['produtos']);

            $data[$key]['produtos'] = $rowComprador['produtos'];
        }

        return $data;
    }

    /**
     * Obtem a lista das ultimas ofertas agrupado por comprador e produto. E faz o calculo de IA
     *
     * @param - POST filtros
     * @return array
     */
    public function produtosPrecoIA($array)
    {
        $data = [];

        if (isset($array['id_cliente']) && !empty($array['id_cliente'])) {

            $where_cliente = " AND prods.id_cliente = {$array['id_cliente']} AND ";
        } else {

            if (isset($array['notPage'])) {

                $limitQuery = "";
            } else {

                $limit = 5;
                $offset = ($array['page'] > 0) ? $array['page'] * $limit : 0;

                $limitQuery = "LIMIT {$limit} OFFSET {$offset} ";
            }

            $getClientes = $this->db->query("
                SELECT DISTINCT cp.id_cliente
                FROM cotacoes_produtos cp
                WHERE cp.id_fornecedor IN ({$array['fornecedor']}) 
                    AND DATE(cp.data_cotacao) BETWEEN '{$array['dt_inicio']}' 
                    AND '{$array['dt_fim']}'
            ")->result_array();

            $ids = [];

            foreach ($getClientes as $key => $value) {

                $ids[] = $value['id_cliente'];
            }

            $ids = implode(',', $ids);

            $where_cliente = " AND prods.id_cliente in ({$ids}) AND ";
        }

        if (isset($array['cd_produto']) && !empty($array['cd_produto'])) {
            $where_cliente .= "pc.codigo = {$array['cd_produto']} AND ";
        }

        if (!empty($where_cliente)) {
            $where_cliente = rtrim($where_cliente, 'AND ');
        }

        if (isset($ids) && empty($ids)) {

            return $data;
        }

        $query = "
            SELECT 
               prods.id_fornecedor,
               prods.id_cliente,
               comp.cnpj,
               comp.razao_social,
               pc.codigo,
               pc.nome_comercial,
               pc.descricao,
               pc.apresentacao
            FROM pharmanexo.compradores comp
            JOIN pharmanexo.cotacoes_produtos prods ON comp.id = prods.id_cliente
            JOIN pharmanexo.produtos_catalogo pc ON pc.codigo = prods.id_pfv AND pc.id_fornecedor = prods.id_fornecedor
            WHERE prods.id_fornecedor IN ({$array['fornecedor']})
                {$where_cliente}
                AND prods.submetido = 1
                AND prods.controle = 1
                AND prods.ocultar = 0
                AND prods.preco_marca > 0
                AND DATE(prods.data_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
            GROUP BY prods.id_pfv, prods.id_cliente
            ORDER BY comp.razao_social ASC
        ";

        $result = $this->db->query($query)->result_array();

        # Para cada produto encontrado, obtem o preço
        foreach ($result as $kk => $row) {

            $queryPreco = "
                SELECT cp.preco_marca, cp.id_fornecedor, cp.cd_cotacao, cp.id_pfv, cp.id_cliente, cp.data_cotacao, cp.id_cotacao, cp.cd_produto_comprador
                FROM pharmanexo.cotacoes_produtos cp
                WHERE cp.id_fornecedor = {$row['id_fornecedor']}
                    AND cp.id_cliente = {$row['id_cliente']}
                    AND cp.id_pfv = {$row['codigo']}
                    AND cp.submetido = 1
                    AND cp.controle = 1
                    AND cp.ocultar = 0
                    AND DATE(cp.data_cotacao) BETWEEN '{$array['dt_inicio']}' AND '{$array['dt_fim']}'
                ORDER BY cp.data_cotacao DESC
                LIMIT 5
            ";

            $f = $this->fornecedor->findById($row['id_fornecedor']);
            $estado = $this->estado->find("id", "uf = '{$f['estado']}' ", true);

            $result[$kk]['preco_catalogo'] = $this->price->getPrice([
                'id_fornecedor' => $row['id_fornecedor'],
                'codigo' => $row['codigo'],
                'id_estado' => $estado['id']
            ]);

            $ultimos_precos = $this->db->query($queryPreco)->result_array();

            foreach ($ultimos_precos as $up) {
                $g = $this->db->select('os.Cd_Cotacao, osp.Vl_Preco_Produto')
                    ->from('ocs_sintese os')
                    ->join('ocs_sintese_produtos osp', 'osp.id_ordem_compra = os.id')
                    ->where('os.Cd_Cotacao', $up['cd_cotacao'])
                    ->where('osp.Cd_Produto_Comprador', $up['cd_produto_comprador'])
                    ->get();


            }

            $result[$kk]['ultimos_precos'] = $ultimos_precos;

        }

        # Agrupa os produtos por comprador
        foreach ($result as $kk => $row) {

            $ultimos_precos = [];

            foreach ($row['ultimos_precos'] as $precos) {

                if ($precos['preco_marca'] != 0) {

                    $ultimos_precos[] = [
                        'value' => $precos['preco_marca'],
                        'data' => date('d/m/Y H:i', strtotime($precos['data_cotacao'])),
                        'dataOriginal' => $precos['data_cotacao'],
                        'format' => number_format($precos['preco_marca'], 4, ',', '.')
                    ];
                }
            }

            # Ordena os preços
            array_multisort(array_column($ultimos_precos, 'dataOriginal'), SORT_ASC, $ultimos_precos);

            $listaPrecos = array_column($ultimos_precos, 'value');

            $media = array_sum($listaPrecos) / count($listaPrecos);

            if (isset($data[$row['id_cliente']])) {

                $data[$row['id_cliente']]['produtos'][] = [
                    'nome_comercial' => $row['nome_comercial'],
                    'id_fornecedor' => $row['id_fornecedor'],
                    'codigo' => $row['codigo'],
                    'descricao' => $row['descricao'],
                    'ultimos_precos' => $ultimos_precos,
                    'preco_catalogo' => $row['preco_catalogo'],
                    'preco_catalogo_format' => number_format($row['preco_catalogo'], 4, ',', '.'),
                    'mediaFormatada' => number_format($media, 4, ',', '.'),
                    'media' => $media,
                    'min' => min($listaPrecos),
                    'max' => max($listaPrecos)
                ];
            } else {

                $data[$row['id_cliente']]['cnpj'] = $row['cnpj'];
                $data[$row['id_cliente']]['razao_social'] = $row['razao_social'];
                $data[$row['id_cliente']]['produtos'][] = [
                    'nome_comercial' => $row['nome_comercial'],
                    'id_fornecedor' => $row['id_fornecedor'],
                    'codigo' => $row['codigo'],
                    'descricao' => $row['descricao'],
                    'ultimos_precos' => $ultimos_precos,
                    'preco_catalogo' => $row['preco_catalogo'],
                    'preco_catalogo_format' => number_format($row['preco_catalogo'], 4, ',', '.'),
                    'mediaFormatada' => number_format($media, 4, ',', '.'),
                    'media' => $media,
                    'min' => min($listaPrecos),
                    'max' => max($listaPrecos)
                ];
            }
        }

        # Ordena os compradores
        array_multisort(array_column($data, 'razao_social'), SORT_ASC, $data);

        # Ordena os produtos
        foreach ($data as $key => $rowComprador) {

            array_multisort(array_column($rowComprador['produtos'], 'nome_comercial'), SORT_ASC, $rowComprador['produtos']);

            $data[$key]['produtos'] = $rowComprador['produtos'];
        }

        return $data;
    }

    /**
     * Obtem a lista  de vendas diferenciadas do fornecedor
     *
     * @param - POST filtros
     * @param - GET - filtros datatable
     * @return array
     */
    public function getVendasDiferenciadas($array, $obj)
    {
        $wherePromo = '';
        $params = ['cliente' => 'vd.id_cliente', 'estado' => 'vd.id_estado'];

        $new_where = $this->manipulaWhere($array, $params);

        if ($array['promocao'] == 'SIM') {

            $wherePromo .= 'vd.promocao = 1 AND ';
        }

        if ($array['desconto'] == 'SIM') {

            $wherePromo .= 'vd.desconto_percentual > 0 AND ';
        }

        $wherePromo = rtrim($wherePromo, 'AND ');

        $wherePromo = (!empty($wherePromo)) ? "AND {$wherePromo}" : '';

        $query = "
            SELECT 
                vd.codigo,
                (SELECT DISTINCT CONCAT(pc.nome_comercial, ' - ', pc.descricao) FROM pharmanexo.produtos_catalogo pc WHERE pc.codigo = vd.codigo AND pc.id_fornecedor IN ({$array['fornecedor']})) produto,
                vd.id_estado,
                (SELECT est.uf FROM pharmanexo.estados est WHERE est.id = vd.id_estado) uf_estado,
                vd.id_cliente,
                comp.cnpj,
                comp.razao_social,
                vd.regra_venda,
                vd.promocao,
                vd.desconto_percentual
            FROM pharmanexo.vendas_diferenciadas vd
            LEFT JOIN pharmanexo.compradores comp ON comp.id = vd.id_cliente
            WHERE vd.id_fornecedor IN ({$array['fornecedor']}) {$wherePromo} {$new_where}
            GROUP BY 
                vd.codigo,
                vd.id_estado,
                vd.id_cliente,
                comp.cnpj,
                comp.razao_social,
                vd.regra_venda,
                vd.promocao
        ";

        # Order by
        if (isset($obj['order'])) {

            $order_by = " ORDER BY ";

            foreach ($obj['order'] as $order) {

                if (isset($obj['columns'][$order['column']])) {

                    $columnName = $obj['columns'][$order['column']]['name'];

                    $order_by .= "{$columnName} {$order['dir']}, ";
                }
            }

            $order_by = trim($order_by, ', ');

            $query = $query . $order_by;
        }

        $a = $this->db->query($query);

        $totalResulted = $a->num_rows();

        $this->db->stop_cache();


        if (isset($obj['start']) && $obj['length'] && $obj['length'] > 0) {

            $limit = " LIMIT {$obj['length']} OFFSET {$obj['start']}";
            $query = $query . $limit;
        }

        $datatableResultado = $this->db->query($query)->result_array();

        foreach ($datatableResultado as $kk => $row) {


            if (isset($row['id_estado'])) {

                $tipoVenda = $row['uf_estado'];
            } else {

                $tipoVenda = trim(substr($row['razao_social'], 0, 30));
            }

            $datatableResultado[$kk]['tipo'] = $tipoVenda;
            $datatableResultado[$kk]['promocao'] = (isset($row['promocao']) && $row['promocao'] == '1') ? 'SIM' : 'NÃO';
            $datatableResultado[$kk]['status_regra_venda'] = status_regra_venda($row['regra_venda']);

            $preco_unitario = $this->price->getPrice([
                'id_fornecedor' => $this->session->id_fornecedor,
                'codigo' => $row['codigo'],
                'id_estado' => $this->session->id_estado
            ]);

            $preco_desconto = $preco_unitario - ($preco_unitario * (floatval($row['desconto_percentual']) / 100));

            $datatableResultado[$kk]['preco_unitario'] = number_format($preco_unitario, 4, ',', '.');
            $datatableResultado[$kk]['preco_desconto'] = number_format($preco_desconto, 4, ',', '.');
            $datatableResultado[$kk]['desconto_percentual'] = number_format($row['desconto_percentual'], 2, ',', '.');
        }

        $data = [
            "draw" => isset ($obj['draw']) ? intval($obj['draw']) : 0,
            "recordsTotal" => $totalResulted,
            "recordsFiltered" => $totalResulted,
            "data" => $datatableResultado
        ];

        return $data;
    }
}