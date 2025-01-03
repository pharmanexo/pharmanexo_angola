<?php

class Pedidos extends MY_Controller
{

    private $views;
    private $routes;

    public function __construct()
    {
        parent::__construct();

        $this->views = "marketplace/pedidos/";
        $this->route = "marketplace/pedidos/";
    }

    public function oracle()
    {

        $this->load->database("oracle", true);

    }

    public function index()
    {
        $page_title = "Pedidos";

        $data['url_datatable'] = "{$this->route}to_datatable/";
        $data['url_update'] = "{$this->route}update/";
        $data['url_detalhes'] = "{$this->route}detalhes/";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => 'Pedidos',
            'styles' => [

            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [

            ]
        ]);

        $this->load->view("{$this->views}pedidos_em_analise", $data);
    }

    public function detalhes($id)
    {
        $page_title = "Pedidos";


        $this->load->model("m_estoque");
        $this->load->model("m_pedido");
        $data['marcas'] = $this->m_estoque->marcasFornecedor();

        $data['pedido'] = $this->m_pedido->get_row($id);

        # URL'S
        $data['url_datatable'] = base_url("{$this->route}to_datatable_produtos/{$id}");
        $data['url_detalhes'] = "{$this->route}/detalhes/";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => 'Pedidos',
            'styles' => [

            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [

            ]
        ]);

        $this->load->view("{$this->views}pedidos_detalhes", $data);
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
                )));
        }
    }


    public function to_datatable_produtos($id): void
    {
        if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode($this->datatable->exec(
                    $this->input->post(),
                    "vw_pedidos_produtos",
                    [
                        ['dt' => 'id', 'db' => 'id'],
                        ['dt' => 'id_pedido', 'db' => 'id_pedido'],
                        ['dt' => 'produto_descricao', 'db' => 'produto_descricao'],
                        ['dt' => 'marca', 'db' => 'marca'],
                        ['dt' => 'quantidade', 'db' => 'quantidade'],
                        ['dt' => 'status', 'db' => 'status'],
                        ['dt' => 'valor', 'db' => 'valor', "formatter" => function ($row) {
                            return number_format($row, 2, ',', '.');
                        }],
                        ['dt' => 'total', 'db' => 'total', "formatter" => function ($row) {
                            return number_format($row, 2, ',', '.');
                        }]

                    ], NULL, [
                        "id_pedido = {$id}"
                    ]
                )));
        }
    }
}