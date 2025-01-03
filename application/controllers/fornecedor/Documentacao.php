<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documentacao extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/documentacao');
        $this->views = 'fornecedor/documentacao';

        $this->load->model('m_logistica', 'ordem_compra');
        $this->load->model('m_status_ordem_compra', 'status');
    }

    public function index()
    {
        $page_title = 'Documentação Compradores';

        $data['dataTable'] = "{$this->route}/getDatasource";
        $data['options'] = $this->status->getStatus();
        $data['url_detalhes'] = "{$this->route}/detalhes";

        $data['header'] = $this->template->header([
            'title' => $page_title
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

        $data['heading'] = $this->template->heading([
            'page_title' => $page_title
        ]);

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function getDatasource()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_clientes_fornecedores',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'cnpj', 'dt' => 'cnpj'],
                ['db' => 'razao_social', 'dt' => 'razao_social'],
                ['db' => 'alvara', 'dt' => 'alvara', 'formatter' => function ($d, $row) {
                    return base_url(PUBLIC_PATH . "clientes/{$row['id']}/{$d}");
                }],
                ['db' => 'responsabilidade_tecnica', 'dt' => 'responsabilidade_tecnica', 'formatter' => function ($d, $row) {
                    return base_url(PUBLIC_PATH . "clientes/{$row['id']}/{$d}");
                }],
                ['db' => 'validade_alvara', 'dt' => 'validade_alvara', 'formatter' => function ($d) {
                    return date("d/m/Y", strtotime($d));
                }],
                ['db' => 'cartao_cnpj', 'dt' => 'cartao_cnpj', 'formatter' => function ($d, $row) {
                    return base_url(PUBLIC_PATH . "clientes/{$row['id']}/{$d}");
                }],
            ], NULL,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }
}