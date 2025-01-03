<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Compradores extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/compras_coletivas/compradores');
        $this->views = "admin/compras_coletivas/compradores/";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_tipos_venda', 'tipos_venda');
    }

    /**
     * Exibe a view admin/forncedores/main.php
     *
     * @param int $id
     * @return  view
     */
    public function index()
    {
        $page_title = "Fornecedores";
        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/atualizar";
        $data['url_status'] = "{$this->route}/updateStatus/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnInsert',
                    'url' => "{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }
}

