<?php

class Catalogo extends MY_Controller{

    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = "catalogo/";
        $this->views = "marketplace/";

        $this->load->model("m_estoque");
    }

    public function index()
    {
        $page_title = "Pedidos";

        $data['to_datatable'] = "{$this->route}to_datatable/";
        $data['url_update'] = "{$this->route}update/";

        # DESTAQUES

        $data['destaques'] = $this->m_estoque->get_rows("*", "destaque = 1");

        var_dump($data['destaques']);exit();

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => 'Pedido',
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}produtos", $data);
    }


    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos',
            [
                ['dt' => 'id', 'db' => 'id'],
                ['dt' => 'produto_descricao', 'db' => 'produto_descricao'],
                ['dt' => 'marca', 'db' => 'marca'],
                ['dt' => 'preco', 'db' => 'preco', 'formatter' => function($d){
                return number_format($d, 2, ',','.');
                }],
                ['dt' => 'quantidade', 'db' => 'quantidade'],
                ['dt' => 'validade', 'db' => 'validade', 'formatter' => function($d){
                return date("d/m/Y", strtotime($d));
                }],
            ], NULL, "id_estado={$this->session->id_estado} AND ativo = 1 AND quantidade > 0 AND APROVADO = 1 AND validade > '" . date('Y-m-d') ."'"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }


}