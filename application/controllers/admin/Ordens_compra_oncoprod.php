<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ordens_compra_oncoprod extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/ordens_compra_oncoprod');
        $this->views = "admin/ordens_compra_oncoprod/";
    }

    /**
     * Exibe a view admin/logs/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = "Ordens de compra Oncoprod";
        $data['datasource'] = "{$this->route}/datatables";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
        ]);
        $data['scripts'] = $this->template->scripts();


        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o datatables de logs
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'vw_oc_sintese',
            [
                ['db' => 'cliente', 'dt' => 'cliente'],
                ['db' => 'cnpj', 'dt' => 'cnpj'],
                ['db' => 'uf', 'dt' => 'uf'],
                ['db' => 'oc', 'dt' => 'oc'],
                ['db' => 'data_oc', 'dt' => 'data_oc', 'formatter' => function($d){
                    return date("d/m/Y H:i:s", strtotime($d));
                }],
                ['db' => 'fornecedor', 'dt' => 'fornecedor'],
                ['db' => 'cnpj_fornecedor', 'dt' => 'cnpj_fornecedor'],
                ['db' => 'cor_erp', 'dt' => 'cor_erp'],
                ['db' => 'produto', 'dt' => 'produto'],
                ['db' => 'unidade', 'dt' => 'unidade'],
                ['db' => 'qtd', 'dt' => 'qtd'],
                ['db' => 'embalagem', 'dt' => 'embalagem'],
                ['db' => 'preco', 'dt' => 'preco', 'formatter' => function($d){
                    return number_format($d, 2, ',', '.');
                }],
                ['db' => 'marca', 'dt' => 'marca'],

            ]
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

}

/* End of file: logs.php */
