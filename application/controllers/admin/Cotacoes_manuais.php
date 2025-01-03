<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cotacoes_manuais extends MY_Controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/cotacoes_manuais');
        $this->views = "admin/cotacoes_manuais";
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_compradores', 'compradores');
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
        $data['heading'] = $this->template->heading([ 'page_title' => $page_title ]);
        $data['scripts'] = $this->template->scripts();

        $data['fornecedores'] = $this->fornecedor->find();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view admin/cotacoes_manuais/detail.php
     *
     * @param   int  $id_cotacao
     * @return  view
     */
    public function detalhes($id_cotacao)
    {
        $data = [];

        //Obtem a cotação
        $this->db->where('id_cotacao', $id_cotacao);
        $data['cotacao'] = $this->db->get('vw_cotacoes', '1')->row_array();

        //Obtem o cliente(comprador)
        $data['comprador'] = $this->compradores->find('*', ['cnpj' => $data['cotacao']['cnpj_comprador']], true);

        // Obtem Valor total de todos os produtos
        $valor_total = $this->db
            ->select('(SELECT SUM( (CP.preco_marca * CP.qtd_solicitada) ) FROM cotacoes_produtos as CP WHERE CP.id_cotacao=' . $id_cotacao . ') AS valor_total', FALSE)
            ->get('cotacoes_produtos')
            ->row_array();

        $data['valor_total_produtos'] = $valor_total['valor_total'];
        
        // Obtem a qnt de produtos de cada cotação
        $this->db->where('id_cotacao', $data['cotacao']['id_cotacao']);
        $data['total_itens'] = $this->db->count_all_results('cotacoes_produtos');

        $page_title = "Produtos da cotação {$id_cotacao}";

        $data['dataTable'] = "{$this->route}/getDatasource/{$id_cotacao}";
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
    public function getDataSource($id = null)
    {
        if (isset($id)) {
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
                "id_cotacao = {$id}"
            );
            
        } else {
            $data = $this->datatable->exec(
                $this->input->get(),
                'vw_cotacoes',
                [
                    ['db' => 'id', 'dt' => 'id'],
                    ['db' => 'id_cotacao', 'dt' => 'id_cotacao'],
                    ['db' => 'data_cotacao', 'dt' => 'data_cotacao', 'formatter' => function ($d) {
                        return date('d/m/Y', strtotime($d));
                    }],
                    ['db' => 'cnpj_comprador', 'dt' => 'cnpj_comprador'],
                    ['db' => 'uf_comprador', 'dt' => 'uf_comprador'],
                    ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                    ['db' => 'id_cotacao', 'dt' => 'total_itens', 'formatter' => function ($d) {

                        $this->db->where('id_cotacao', $d);
                        return $total_itens = $this->db->count_all_results('cotacoes_produtos');
                    }],
                ],
                null
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}