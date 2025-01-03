<?php

class Ordens_compra extends MY_Controller
{

    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();
        $this->views = "marketplace/";
        $this->views = "marketplace/ordem_compra/";
    }

    public function index()
    {
        $page_title = "Ordens de Compra";

        $data['url_datatable'] = "{$this->route}to_datatable/";
        $data['url_update'] = "{$this->route}update/";
        $data['url_detalhes'] = "{$this->route}detalhes/";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => 'Pedido',
            'styles' => [
                THIRD_PARTY . 'plugins/DataTables-1.10.18/css/dataTables.bootstrap4.min.css',
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/DataTables-1.10.18/datatables.min.js'
            ]
        ]);

        $this->load->view("{$this->views}pedidos_em_analise", $data);
    }

    public function to_datatable(): void
    {
        if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode($this->datatable->exec(
                        $this->input->post(),
                        "vw_pedidos",
                        [
                            ['dt' => 'id', 'db' => 'id'],
                            ['dt' => 'razao_social', 'db' => 'razao_social'],
                            ['dt' => 'total_itens', 'db' => 'total_itens'],
                            ['dt' => 'status', 'db' => 'status'],
                            ['dt' => 'data_criacao', 'db' => 'data_criacao', 'formatter' => function ($row) {
                                return date("d/m/Y", strtotime($row));
                            }],
                            ['dt' => 'total', 'db' => 'total', 'formatter' => function ($row) {
                                return number_format($row, 2, ',', '.');
                            }],

                        ]
                    )
                    )
                );
        }
    }

}