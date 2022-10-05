<?php

class Pedidos_produtos extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = "/admin/pedidos_produtos/";
        $this->views = "admin/pedidos/";
    }

    public function update($id)
    {
        $page_title = "Avaliar Pedido";

        $data['to_datatable'] = "{$this->route}to_datatable/";
        $data['url_update'] = "{$this->route}update/";

        // TEMPLATE
        $data['header'] = $this->template2->header([
            'title' => 'Avaliar Pedido',
            'styles' => [
                THIRD_PARTY . 'plugins/DataTables-1.10.18/css/dataTables.bootstrap4.min.css',
            ]
        ]);
        $data['navbar'] = $this->template2->navbar();
        $data['sidebar'] = $this->template2->sidebar();
        $data['heading'] = $this->template2->heading([
            'page_title' => $page_title,
            'buttons' => [
                ['type' => 'a',
                    'url' => $this->route,
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar']
            ]
        ]);
        $data['scripts'] = $this->template2->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/DataTables-1.10.18/datatables.min.js'
            ]
        ]);

        $this->load->view("{$this->route}form", $data);
    }

    public function index()
    {
        $page_title = "Pedidos";

        $data['to_datatable'] = "{$this->route}to_datatable/";
        $data['url_update'] = "{$this->route}update/";
        $data['header'] = $this->template->header([
            'title' => 'Pedido',
            'styles' => [
                THIRD_PARTY . 'plugins/DataTables-1.10.18/css/dataTables.bootstrap4.min.css',
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/DataTables-1.10.18/datatables.min.js'
            ]
        ]);

        $this->load->view("{$this->route}main", $data);
    }

    public function to_datatable($id_pedido)
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_pedidos_produtos',
            [
                ['db' => 'vw_pedidos_produtos.id', 'dt' => 'id'],
                ['db' => 'vw_pedidos_produtos.codigo', 'dt' => 'codigo'],
                ['db' => 'vw_pedidos_produtos.produto_descricao', 'dt' => 'produto_descricao'],
                ['db' => 'vw_pedidos_produtos.marca', 'dt' => 'marca'],
                ['db' => 'vw_pedidos_produtos.quantidade', 'dt' => 'quantidade'],
                ['db' => 'vw_pedidos_produtos.preco_unidade', 'dt' => 'preco_unidade'],
                ['db' => 'vw_pedidos_produtos.total', 'dt' => 'total'],
                ['db' => 'vw_pedidos_produtos.status', 'dt' => 'status'],
                [
                    'db' => 'fornecedores.razao_social',
                    'dt' => 'fornecedor',
                    'formatter' => function($value, $row) {
                        return $value;
                    }
                ],
            ], 
            [
                ['fornecedores', 'vw_pedidos_produtos.id_fornecedor = fornecedores.id'],
            ]
            ,
            "id_pedido={$id_pedido}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }
}
