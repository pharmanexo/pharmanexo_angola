<?php
date_default_timezone_set('America/Sao_Paulo');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class ProcessCotacao extends CI_Controller
{

    private $route;
    private $views;
    private $DB_SINTESE;
    private $DB_BIONEXO;
    private $DB_APOIO;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_catalogo', 'catalogo');

        $this->load->model('m_marca', 'marca');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('produto_marca_sintese', 'pms');
        $this->load->model('m_cotacoes', 'cotacoes');
        $this->load->model('m_cotacaoManual', 'COTACAO_MANUAL');
        $this->load->model('m_restricao_produto_cotacao2', 'restricao_cotacao');

        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        //error_reporting(0);
        //ini_set('display_errors', 0);

        $this->route = base_url('/fornecedor/cotacoes/');
        $this->views = 'fornecedor/cotacoes/';

        $this->DB_SINTESE = $this->load->database('sintese', TRUE);
        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);
        $this->DB_APOIO = $this->load->database('apoio', TRUE);
    }

    /**
     * Função que verifica se o fornecedor logado possui filial
     *
     * @return  bool
     */
    public function checkFilial()
    {

        return $this->session->has_userdata('id_matriz') ? true : false;
    }


    /**
     * Exibe a view dos produtos da cotação
     *
     * @return view
     */
    public function select()
    {

        $cotacoes = $this->db
            ->select('cd_cotacao, id_fornecedor, integrador')
            ->where("dt_fim_cotacao > now()")
            ->where("integrador", 'SINTESE')
            ->where("id_fornecedor", '1002')
            ->order_by('dt_fim_cotacao ASC')
            ->limit(5)
            ->get('vw_cotacoes_integrador')
            ->result_array();


        foreach ($cotacoes as $cot) {
            $cd_cotacao = $cot['cd_cotacao'];
            $integrador = $cot['integrador'];
            $id_fornecedor = $cot['id_fornecedor'];

            # Verifica se o fornecedor logado tem filial
            $data['checkFilial'] = $this->checkFilial();

            $page_title = "Cotação #{$cd_cotacao}";

            $data['integrador'] = $integrador;
            $data['cd_cotacao'] = $cd_cotacao;
            $data['id_fornecedor'] = $id_fornecedor;

            # Array com os dados da cotação, comprador e seus produtos
            $data['cotacao'] = $this->COTACAO_MANUAL->getItem($cd_cotacao, $id_fornecedor, $data['integrador']);

            # Verifica se a cotação não existe
            if ($data['cotacao'] == false) {
                continue;
            }

            # Obtem o comprador
            $cliente = $data['cotacao']['cliente'];

            # Obtem o  estado do comprador
            $estado = $data['cotacao']['estado'];

            # Prazo entrega
            $data['prazo_entrega'] = $this->COTACAO_MANUAL->getPrazoEntrega('', $cliente['id'], $id_fornecedor, $estado['id']);

            # Condição pagamento
            $data['forma_pagamento'] = $this->COTACAO_MANUAL->getFormaPagamento($data['integrador'], $cliente['id'], $id_fornecedor, $estado['id']);

            # Verifica se acotação ja foi respondida
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->order_by('data_criacao DESC');
            $cotacao_respondida = $this->db->get('cotacoes_produtos')->row_array();

            if (isset($cotacao_respondida) && !empty($cotacao_respondida)) {

                $data['prazo_entrega'] = $cotacao_respondida['prazo_entrega'];
                $data['observacao'] = $cotacao_respondida['obs'];
                $data['forma_pagamento'] = $cotacao_respondida['id_forma_pagamento'];
            } else {

                $this->db->where("id_fornecedor", $id_fornecedor);
                $this->db->where_in("tipo", [2, 3]);
                $this->db->group_start();
                $this->db->where("id_estado", $estado['id']);
                $this->db->or_where("id_estado", 0);
                $this->db->group_end();

                $obsConfig = $this->db->get("configuracoes_envio")->row_array();

                if (isset($obsConfig) && !empty($obsConfig)) {

                    $data['observacao'] = $obsConfig['observacao'];
                }
            }

            if ($data['checkFilial']) {

                # Select de fornecedores ONCOPROD
                $data['options_fornecedores'] = $this->COTACAO_MANUAL->selectFornecedores($data['integrador'], $cd_cotacao);
            }

            $this->gravar( json_encode($data));


        }


    }

    private function gravar($json)
    {
        $base_url = "http://10.101.70.3:8080/api/";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $base_url . "cotacao");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return false;
        }
        curl_close($ch);

        return true;

    }

}
