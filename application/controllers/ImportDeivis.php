<?php

class ImportDeivis extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
        $this->mix = $this->load->database('mix', true);
    }


    public function index()
    {

        $dbSint = $this->load->database('sintese', true);

        $cotacoes = $dbSint
            ->select('c.cd_cotacao, c.motivo_recusa, c.usuario_recusa, c.data_recusa, c.obs_recusa, cp.cnpj, cp.nome_fantasia, cp.razao_social, cp.estado as estado_comprador')
            ->from('cotacoes c')
            ->join('pharmanexo.compradores cp', 'cp.id = c.id_cliente')
            // ->where('c.cd_cotacao', 'COT19393-10')
            ->where("dt_inicio_cotacao between '2022-12-01' and '2022-12-31'")
            ->where_in('c.id_fornecedor', [12, 112, 115, 120, 123, 125, 126, 127])
            ->group_by('c.cd_cotacao')
            ->get()
            ->result_array();


        foreach ($cotacoes as $cotacao) {

            $produtosCotacao = $dbSint
                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                ->group_by('id_produto_sintese, cd_produto_comprador')
                ->get('cotacoes_produtos')
                ->result_array();

            $prodsRespondidos = $this->db
                ->select('cp.*, u.nickname, u.email, cat.codigo, cat.nome_comercial')
                ->from('cotacoes_produtos cp')
                ->join('usuarios u', 'u.id = cp.id_usuario')
                ->join('produtos_catalogo cat', 'cat.codigo = cp.id_pfv and cat.id_fornecedor = cp.id_fornecedor')
                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                ->where_in('cp.id_fornecedor', [12, 112, 115, 120, 123, 125, 126, 127])
                ->get()
                ->result_array();

            $prodsGanhadores = $this->db
                ->select('osp.*, os.Cd_Ordem_Compra, os.id_fornecedor')
                ->from('ocs_sintese_produtos osp')
                ->join('ocs_sintese os', 'osp.id_ordem_compra = os.id')
                ->where('os.Cd_Cotacao', $cotacao['cd_cotacao'])
                ->where_in('os.id_fornecedor', [12, 112, 115, 120, 123, 125, 126, 127])
                ->get()
                ->result_array();


            if (empty($prodsRespondidos)) {
                continue;
            }


            foreach ($produtosCotacao as $k => $prodCot) {

                unset($produtosCotacao[$k]['sn_item_contrato']);
                unset($produtosCotacao[$k]['sn_permite_exibir']);

                $produtosCotacao[$k]['cnpj_comprador'] = $cotacao['cnpj'];
                $produtosCotacao[$k]['nome_fantasia'] = $cotacao['nome_fantasia'];
                $produtosCotacao[$k]['razao_social'] = $cotacao['razao_social'];
                $produtosCotacao[$k]['estado_comprador'] = $cotacao['estado_comprador'];
                $produtosCotacao[$k]['motivo_recusa'] = $cotacao['motivo_recusa'];
                $produtosCotacao[$k]['usuario_recusa'] = $cotacao['usuario_recusa'];
                $produtosCotacao[$k]['data_recusa'] = $cotacao['data_recusa'];
                $produtosCotacao[$k]['obs_recusa'] = $cotacao['obs_recusa'];

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

                foreach ($prodsGanhadores as $prodG) {
                    if (($prodCot['cd_produto_comprador'] == $prodG['Cd_Produto_Comprador']) && ($prodCot['id_produto_sintese'] == $prodG['Id_Produto_Sintese'])) {
                        $produtosCotacao[$k]['ganhou'] = 'SIM';
                        $produtosCotacao[$k]['cd_pedido'] = $prodG['Cd_Ordem_Compra'];
                        $produtosCotacao[$k]['preco_ganhador'] = $prodG['Vl_Preco_Produto'];
                        $produtosCotacao[$k]['loja'] = $prodG['id_fornecedor'];

                    }
                }

                $this->db->insert('temp_rel_oncoprod', $produtosCotacao[$k]);

            }


        }

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
        $file = fopen('sint_dez.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {
            var_dump($line);
            exit();
            $linhas[] = $line;
        }
        fclose($file);

        unset($linhas[0]);
        foreach ($linhas as $line) {
            var_dump($line);
            exit();

            $insert[] = [
                "codigo" => intval($line[0]),
                "produtos" => trim($line[1]),
                "unidade" => trim($line[2]),
                "marca" => trim($line[3]),
                "valor" => intval($line[4]),
                "qtd_embalagem" => trim($line[5]),
                "qtd_solicitada" => date("Y-m-d", strtotime($line[6])),
                "cotacao" => dbNumberFormat(trim($line[7])),
                "cotacao" => dbNumberFormat(trim($line[7])),
                "cotacao" => dbNumberFormat(trim($line[7])),
                "cotacao" => dbNumberFormat(trim($line[7])),
            ];
        }

        $this->db->insert_batch("promocoes_convidados", $insert);
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

    public function importCatalogo()
    {
        $file = fopen('catalogo_promepharma.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {

            $cod = intval($line[0]);
            if ($cod > 0) {
                $linhas[] = [
                    'codigo' => $line[0],
                    'nome_comercial' => $line[1],
                    'marca' => $line[3],
                    'quantidade_unidade' => intval($line[4]),
                    'unidade' => $line[5],
                    'rms' => $line[6],
                    'ean' => $line[7],
                    'ncm' => $line[8],
                    'id_fornecedor' => 5007,
                    'ativo' => 1,
                    'bloqueado' => 0
                ];
            }


        }
        fclose($file);


        $this->db->insert_batch("produtos_catalogo", $linhas);
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

}
