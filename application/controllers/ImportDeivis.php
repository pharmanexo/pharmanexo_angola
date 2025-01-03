<?php

class ImportDeivis extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
        $this->mix = $this->load->database('mix', true);
        $this->hmg = $this->load->database('homolog', true);
    }

    public function corrigirCatalogoOncoprod()
    {
        $produtos = $this->db
            ->where('quantidade_unidade is not null')
            ->where_in('id_fornecedor', [12, 112, 115, 123, 125, 126, 127])
            ->group_by('codigo')
            ->get('produtos_catalogo')
            ->result_array();

        foreach ($produtos as $produto) {

            $update = [
                'quantidade_unidade' => $produto['quantidade_unidade']
            ];

            $this->db
                ->where('codigo', $produto['codigo'])
                ->where_in('id_fornecedor', [12, 112, 115, 123, 125, 126, 127])
                ->update('produtos_catalogo', $update);

        }

    }

    public function corrigirocs()
    {
        $ocsProds = $this->hmg->get('ocs_sintese_produtos')->result_array();

        foreach ($ocsProds as $item) {
            $id = $item['id'];
            unset($item['id']);

            $getProds = $this->db
                ->where('id_ordem_compra', $item['id_ordem_compra'])
                ->where('Cd_Produto_Comprador', $item['Cd_Produto_Comprador'])
                ->get('ocs_sintese_produtos');

            if ($getProds->num_rows() == 0) {
                $this->db->insert('ocs_sintese_produtos', $item);
            }

        }

    }

    public function index()
    {

        $forns = [5042, 5043, 5044];

        $dbSint = $this->load->database('sintese', true);

        $cotacoes = $dbSint
            ->select('c.cd_cotacao, c.motivo_recusa, c.usuario_recusa, c.data_recusa, c.obs_recusa, cp.cnpj, cp.nome_fantasia, cp.razao_social, cp.estado as estado_comprador')
            ->from('cotacoes c')
            ->join('pharmanexo.compradores cp', 'cp.id = c.id_cliente')
            // ->where('c.cd_cotacao', 'COT9621-3314')
            ->where("dt_inicio_cotacao between '2022-12-01 00:00:00' and '2022-12-31 23:59:59'")
            ->where_in('c.id_fornecedor', $forns)
            ->group_by('c.cd_cotacao')
            ->get()
            ->result_array();

        foreach ($cotacoes as $cotacao) {


            $produtosCotacao = $dbSint
                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                ->where_in('id_fornecedor', $forns)
                ->group_by('id_produto_sintese, cd_produto_comprador')
                ->get('cotacoes_produtos')
                ->result_array();


            $prodsRespondidos = $this->db
                ->select('cp.*, u.nickname, u.email, cat.codigo, cat.nome_comercial')
                ->from('cotacoes_produtos cp')
                ->join('usuarios u', 'u.id = cp.id_usuario')
                ->join('produtos_catalogo cat', 'cat.codigo = cp.id_pfv and cat.id_fornecedor = cp.id_fornecedor')
                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                ->where('submetido', 1)
                ->where_in('cp.id_fornecedor', $forns)
                ->get()
                ->result_array();


            $prodsGanhadores = $this->db
                ->select('osp.*, os.Cd_Ordem_Compra, os.id_fornecedor')
                ->from('ocs_sintese_produtos osp')
                ->join('ocs_sintese os', 'osp.id_ordem_compra = os.id')
                ->where('os.Cd_Cotacao', $cotacao['cd_cotacao'])
                ->where_in('os.id_fornecedor', $forns)
                ->get()
                ->result_array();


            foreach ($produtosCotacao as $k => $prodCot) {

                unset($produtosCotacao[$k]['sn_item_contrato']);
                unset($produtosCotacao[$k]['sn_permite_exibir']);

                $produtosCotacao[$k]['forn'] = '4BIO';
                $produtosCotacao[$k]['cnpj_comprador'] = $cotacao['cnpj'];
                $produtosCotacao[$k]['nome_fantasia'] = $cotacao['nome_fantasia'];
                $produtosCotacao[$k]['razao_social'] = $cotacao['razao_social'];
                $produtosCotacao[$k]['estado_comprador'] = $cotacao['estado_comprador'];
                $produtosCotacao[$k]['motivo_recusa'] = $cotacao['motivo_recusa'];
                $produtosCotacao[$k]['usuario_recusa'] = $cotacao['usuario_recusa'];
                $produtosCotacao[$k]['data_recusa'] = $cotacao['data_recusa'];
                $produtosCotacao[$k]['obs_recusa'] = $cotacao['obs_recusa'];

                if (!empty($prodsRespondidos)) {
                    foreach ($prodsRespondidos as $prodResp) {

                        if (($prodCot['cd_produto_comprador'] == $prodResp['cd_produto_comprador']) && ($prodCot['id_produto_sintese'] == $prodResp['id_produto'])) {
                            $produtosCotacao[$k]['respondido'] = 'SIM';
                            $produtosCotacao[$k]['codigo'] = $prodResp['codigo'];
                            $produtosCotacao[$k]['descricao_catalogo'] = $prodResp['nome_comercial'];
                            $produtosCotacao[$k]['respondido_por'] = $prodResp['nickname'];
                            $produtosCotacao[$k]['preco_oferta'] = $prodResp['preco_marca'];
                            $produtosCotacao[$k]['id_forn_oferta'] = $prodResp['id_fornecedor'];
                        }

                    }
                }

                if (!empty($prodsGanhadores)) {
                    foreach ($prodsGanhadores as $prodG) {
                        if (($prodCot['cd_produto_comprador'] == $prodG['Cd_Produto_Comprador']) && ($prodCot['id_produto_sintese'] == $prodG['Id_Produto_Sintese'])) {
                            $produtosCotacao[$k]['ganhou'] = 'SIM';
                            $produtosCotacao[$k]['cd_pedido'] = $prodG['Cd_Ordem_Compra'];
                            $produtosCotacao[$k]['preco_ganhador'] = $prodG['Vl_Preco_Produto'];
                            $produtosCotacao[$k]['loja'] = $prodG['id_fornecedor'];
                        }
                    }
                }

                $this->db->insert('temp_rel_oncoprod', $produtosCotacao[$k]);

            }


        }

    }

    public function importglobal()
    {
        $file = fopen('condicao_global.csv', 'r');
        $log = fopen('log_cond_global.txt', 'a+');
        $linhas = [];

        $depara = [
            '14 dias' => 23,
            '15 e 28 dias' => 41,
            '21 dias' => 24,
            '28 dias' => 20,
            '28, 35, 42, 56 dias' => 122,
            '28, 42, e 56 dias' => 130,
            '30 e 45 dias' => 33,
            '30 e 60 dias' => 10,
            '30, 60 e 90 dias' => 12,
            '40 e 60 dias' => 541,
            '45 dias' => 4,
            '7 dias' => 22,
            'Á combinar' => 79
        ];

        while (($line = fgetcsv($file, null, ';')) !== false) {

            $cnpj = mask(soNumero($line[0]), '##.###.###/####-##');
            $cliente = $this->db->where('cnpj', $cnpj)->get('compradores')->row_array();
            $valor = dbNumberFormat(trim($line[5]));


            if (!empty($cliente)) {
                $linhas[] = [
                    "id_cliente" => $cliente['id'],
                    'id_fornecedor' => '5038',
                    'valor_minimo' => $valor,
                    'id_tipo_venda' => '2'
                ];
            } else {
                fwrite($log, "Cliente {$cnpj} não encontrado \n");
            }


        }

        $this->db->insert_batch('valor_minimo_cliente', $linhas);

        fclose($file);
    }

    public function importRest()
    {
        exit();
        $file = fopen('itens_hosp.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $linhas[] = $line;
        }
        fclose($file);

        unset($linhas[0]);
        foreach ($linhas as $line) {
            $insert[] = [
                "codigo" => intval($line[0]),
                "descricao" => trim($line[1]),
                "unidade" => trim($line[2]),
                "marca" => trim($line[3]),
                "quantidade" => intval($line[4]),
                "lote" => trim($line[5]),
                "validade" => date("Y-m-d", strtotime($line[6])),
                "preco" => dbNumberFormat(trim($line[7])),
            ];
        }

        $this->db->insert_batch("promocoes_convidados", $insert);
    }

    public function importProds()
    {
        $file = fopen('preco_fec.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            if ($line[0] == 'codigo') {
                continue;
            }

            $insert = [
                "codigo" => intval($line[0]),
                "produto" => trim($line[1]),
                "unidade" => trim($line[2]),
                "marca" => trim($line[3]),
                "valor" => intval($line[4]),
                "qtd_embalagem" => trim($line[5]),
                "cotacao" => trim($line[5]),
                "ordemcompra" => trim($line[5]),
                "fornecedor" => trim($line[5]),
                "data_cotacao" => $line[5],
                "hospital" => trim($line[5]),
                "qtd_comprador" => trim($line[5]),
            ];

            $i = $this->db->insert("temp_produtos_ofertas", $insert);

            if (!$i) {
                var_dump($this->db->error());
                exit();
            }
        }
        fclose($file);


    }

    public function importPontamed()
    {
        $file = fopen('compradores_pontamed.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {
            $linhas[] = $line;
        }
        fclose($file);

        unset($linhas[0]);
        foreach ($linhas as $line) {

            $cnpj = $line[0];
            $comprador = $this->db->select('id')->where('cnpj', $cnpj)->get('compradores')->row_array();

            $insert[] = [
                "id_fornecedor" => intval(5018),
                "id_cliente" => $comprador['id'],
                'consultor' => $line[5],
                'alerta_abertura' => 1
            ];

        }

        $this->db->insert_batch("email_notificacao", $insert);
    }

    public function importProme()
    {
        $file = fopen('usuarios_promefarma.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $cliente = $this->db->where('cnpj', $line[1])->get('compradores')->row_array();
            $usuario = $this->db->where('email', $line[5])->get('usuarios')->row_array();

            if (!empty($cliente) && !empty($usuario)) {

                $linhas[] = [
                    "id_fornecedor" => 5007,
                    "id_cliente" => $cliente['id'],
                    "id_usuario" => $usuario['id']
                ];
            }
        }
        fclose($file);

        $this->db->insert_batch("usuarios_rede_atendimento", $linhas);
    }

    public function importCatalogo()
    {
        $file = fopen('material_angola.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {


            if ($line[0] == "codprod") {
                continue;
            }


            $linhas[] = [
                'codprod' => $line[0],
                //'substancia' => $line[1],
                'nome' => "$line[6]",
                /* 'forma_farmaceutica' => $line[3],
                 'dosagem' => $line[4],
                 'embalagem' => $line[5],
                 'cnpem' => $line[6],*/
                'tipo' => $line[1],
                'fabricante' => $line[2],
                'referencia' => $line[3],
                'modelo' => $line[4],
                'marca' => $line[5],
                'descricao' => $line[7],
                'distribuidor' => $line[8]
            ];

        }
        fclose($file);


        $this->db->insert_batch("catalogo", $linhas);
    }

    public function relatorioGeral()
    {
        $cotacoesAbertas = $this->db->query("select c.estado, count(cot.cd_cotacao) as total
                                                from cotacoes_sintese.cotacoes cot
                                                         join pharmanexo.compradores c on c.id = cot.id_cliente
                                                where cot.id_fornecedor = 5018
                                                  and cot.dt_inicio_cotacao between '2023-03-01 00:00:00' and '2023-03-31 23:59:59'
                                                group by c.estado
                                                order by c.estado ASC;
                                                ")->result_array();

        $ufs = [];

        foreach ($cotacoesAbertas as $k => $cotacoes) {
            $ufs[$cotacoes['estado']]['cotacoesAbertas'] = $cotacoes['total'];
        }

        $cotacoesRespondidas = $this->db->query("select e.uf,
                                                       count(distinct cd_cotacao)                as total,
                                                       (sum(cp.preco_marca * cp.qtd_solicitada)) as total_cotacao,
                                                       count(cp.id)                              as itens_cotados
                                                from pharmanexo.cotacoes_produtos cp
                                                         join pharmanexo.compradores c on c.id = cp.id_cliente
                                                         right join estados e on e.uf = c.estado
                                                where cp.id_fornecedor = 5018
                                                  and cp.data_criacao between '2023-03-01 00:00:00' and '2023-03-31 23:59:59'
                                                group by c.estado
                                                order by c.estado ASC;")->result_array();


        foreach ($cotacoesRespondidas as $cotR) {
            $ufs[$cotR['uf']]['cotacoesRespondidas'] = $cotR['total'];
            $ufs[$cotR['uf']]['cotacoesRespondidasTotal'] = $cotR['total_cotacao'];
            $ufs[$cotR['uf']]['cotacoesRespondidasItens'] = $cotR['itens_cotados'];
        }

        $ocsEmitidas = $this->db->query("select c.estado, count(oc.Cd_Ordem_Compra) as total
                                            from pharmanexo.ocs_sintese oc
                                                     join pharmanexo.compradores c on c.id = oc.id_comprador
                                            where oc.id_fornecedor = 5018
                                              and oc.Dt_Gravacao between '2023-03-01 00:00:00' and '2023-03-31 23:59:59'
                                            group by c.estado
                                            order by c.estado ASC;")->result_array();

        foreach ($ocsEmitidas as $oc) {
            $ufs[$oc['estado']]['ocs'] = $oc['total'];
        }


        $ocsProdutos = $this->db->query("select c.estado, count(ocp.id) as total_itens, sum(ocp.Vl_Preco_Produto * ocp.Qt_Produto) as total
                                            from pharmanexo.ocs_sintese oc
                                                     join pharmanexo.ocs_sintese_produtos ocp on ocp.id_ordem_compra = oc.id
                                                     join pharmanexo.compradores c on c.id = oc.id_comprador
                                            where oc.id_fornecedor = 5018
                                              and oc.Dt_Gravacao between '2023-03-01 00:00:00' and '2023-03-31 23:59:59'
                                            group by c.estado
                                            order by c.estado ASC;")->result_array();


        foreach ($ocsProdutos as $ocp) {
            $ufs[$ocp['estado']]['ocsItens'] = $ocp['total_itens'];
            $ufs[$ocp['estado']]['ocsTotal'] = $ocp['total'];
        }


        var_dump($ufs);
        exit();

    }

    public function regra4bio()
    {
        $file = fopen('REGRAS.csv', 'r');
        $linhas = [];
        $lojas = [
            'PE' => 5042,
            'TO' => 5043,
            'SP' => 5044
        ];

        while (($line = fgetcsv($file, null, ';')) !== false) {
            $estado = $this->db->where('uf', $line[0])->get('estados')->row_array();

            $linhas[] = [
                'icms' => 'Isento',
                'classe' => $line[1],
                'id_estado' => $estado['id'],
                'id_fornecedor' => $lojas[$line[2]],
                'loja1' => $lojas[$line[3]],
                'loja2' => $lojas[$line[4]]
            ];
        }


        $this->db->insert_batch('mapa_logistico', $linhas);
    }

    public function importMapa()
    {
        $file = fopen('mapa_oncoprod_2023.csv', 'r');
        $insert = [];

        $lojas = [
            'DF' => 126,
            'ES' => 112,
            'PE' => 123,
            'RJ' => 127,
            'RS' => 12,
            'SP14' => 125,
            'SP15' => 115,
        ];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $estado = $this->db
                ->where('uf', $line[3])
                ->get('estados')
                ->row_array();


            $insert[] = [
                'icms' => $line[0],
                'classe' => $line[1],
                'origem' => $line[2],
                'id_estado' => $estado['id'],
                'id_fornecedor' => $lojas[$line[4]],
                'loja1' => (!empty($line[5])) ? $lojas[$line[5]] : NULL,
                'loja2' => (!empty($line[6])) ? $lojas[$line[6]] : NULL,
                'uf' => $line[4]
            ];
        }
        fclose($file);

        $this->db->insert_batch("mapa_logistico", $insert);
    }

    function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i])) $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

}
