<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_manuais_oncoprod extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/relatorios/cotacoes_manuais_oncoprod');
        $this->views = "admin/relatorios/cotacoes_manuais";
        $this->load->model('admin/m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_integracao', 'integracao');
    }

    /**
     * Exibe a view admin/cotacoes_manuais/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Relatório de Cotações Manuais';

        $data['dataTable'] = "{$this->route}/getDatasource";
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([ 
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $data['fornecedores'] = $this->fornecedor->find('*', "id in (" .ONCOPROD. ")");

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view admin/cotacoes_manuais/detail.php
     *
     * @param   string  codigo da cotacao
     * @param   int  id do fornecedor
     * @return  view
     */
    public function detalhes($id_fornecedor, $cd_cotacao)
    {
        $data = [];

        //Obtem a cotação
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('nivel', 1);
        $data['cotacao'] = $this->db->get('vw_cotacoes', '1')->row_array();

        //Obtem o cliente(comprador)
        $data['comprador'] = $this->compradores->find('*', ['cnpj' => $data['cotacao']['cnpj_comprador']], true);

        // Obtem Valor total de todos os produtos
        $valor_total = $this->db
            ->select("SUM( (preco_marca * qtd_solicitada) ) AS valor_total")
            ->where('id_fornecedor', $id_fornecedor)
            ->where('cd_cotacao', $cd_cotacao)
            ->where('nivel', 1)
            ->get('cotacoes_produtos')
            ->row_array();

        $data['valor_total_produtos'] = $valor_total['valor_total'];
        
        // Obtem a qnt de produtos de cada cotação
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('nivel', 1);
        $data['total_itens'] = $this->db->count_all_results('cotacoes_produtos');

        $page_title = "Produtos da cotação {$cd_cotacao}";

        $data['dataTable'] = "{$this->route}/getDatasource/{$cd_cotacao}/{$id_fornecedor}";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_detalhes/{$cd_cotacao}/{$id_fornecedor}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/Chart.js/Chart.bundle.js',
                THIRD_PARTY . 'plugins/Chart.js/plugins/chartjs-plugin-labels.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/detail", $data);
    }

    /**
     * Obtem dados para os datatables de Cotação manuais
     *
     * @param = int id produto
     * @return  json
     */
    public function getDataSource($cd_cotacao = null, $id_fornecedor = null)
    {
        if (isset($cd_cotacao) && isset($id_fornecedor)) {
            $data = $this->datatable->exec(
                $this->input->get(),
                'cotacoes_produtos',
                [
                    ['db' => 'id', 'dt' => 'id'],
                    ['db' => 'produto', 'dt' => 'produto'],
                    ['db' => 'marca_solicitada', 'dt' => 'marca_solicitada'],
                    ['db' => 'preco_marca', 'dt' => 'preco_marca'],
                    ['db' => 'qtd_solicitada', 'dt' => 'qtd_solicitada'],
                    ['db' => 'submetido', 'dt' => 'submetido'],
                ],
                null,
                "cd_cotacao = '{$cd_cotacao}' AND id_fornecedor = {$id_fornecedor} AND nivel = 1 AND submetido = 1"
            );    
        } else {
            $data = $this->datatable->exec(
                $this->input->get(),
                'vw_cotacoes',
                [
                    ['db' => 'id', 'dt' => 'id'],
                    ['db' => 'id_cotacao', 'dt' => 'id_cotacao'],
                    ['db' => 'cd_cotacao', 'dt' => 'cd_cotacao'],
                    ['db' => 'data_cotacao', 'dt' => 'data_cotacao', 'formatter' => function ($d) {
                        return date('d/m/Y', strtotime($d));
                    }],
                    ['db' => 'razao_social', 'dt' => 'razao_social', 'formatter' => function($value, $row) {
                        return "<small>{$value}</small>";
                    }],
                    ['db' => 'cnpj_comprador', 'dt' => 'cnpj_comprador'],
                    ['db' => 'submetido', 'dt' => 'submetido'],
                    ['db' => 'uf_comprador', 'dt' => 'uf_comprador'],
                    ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                    ['db' => 'total_itens', 'dt' => 'total_itens'],
                    ['db' => 'valor_total', 'dt' => 'valor_total', 'formatter' => function ($d) {

                        return number_format($d, 4, ",", ".");
                    }]
                ],
                null,
                'nivel = 1 and submetido = 1 and id_fornecedor in (' . ONCOPROD . ')'
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar()
    {

        $this->db->select("
            cd_cotacao AS cotacao,
            DATE_FORMAT(data_cotacao, '%d/%m/%Y') AS data_cotacao,
            cnpj_comprador,
            razao_social AS fornecedor,
            uf_comprador,
            total_itens,
            FORMAT(valor_total, 4, 'de_DE') AS valor_total");
        $this->db->from("vw_cotacoes");
        $this->db->where("nivel", 1);
        $this->db->where("submetido", 1);
        $this->db->where("id_fornecedor in (" . ONCOPROD . ")");
        $this->db->order_by("cotacao asc");

        $query = $this->db->get()->result_array();
       
        if ( count($query) < 1 ) {
            $query[] = [
                'cotacao' => '',
                'data_cotacao' => '',
                'cnpj_comprador' => '',
                'fornecedor' => '',
                'uf_comprador' => '',
                'total_itens' => '',
                'valor_total' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Cotacoes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    /**
     * Gera arquivo excel do datatable de detalhes
     *
     * @return  downlaod file
     */
    public function exportar_detalhes($cd_cotacao, $id_fornecedor)
    {
        $this->db->select("
            produto,
            marca_solicitada,
            FORMAT(preco_marca, 4, 'de_DE') AS preco_marca,
            qtd_solicitada AS quantidade
        ");
        $this->db->from("cotacoes_produtos");
        $this->db->where("cd_cotacao", $cd_cotacao);
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where("nivel", 1);
        $this->db->where("submetido", 1);
        $this->db->order_by("produto asc");

        $query = $this->db->get()->result_array();
       
        if ( count($query) < 1 ) {
            $query[] = [
                'produto' => '',
                'marca_solicitada' => '',
                'preco_marca' => '',
                'quantidade' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}