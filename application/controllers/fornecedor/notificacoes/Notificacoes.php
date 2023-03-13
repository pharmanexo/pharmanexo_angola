<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notificacoes extends MY_Controller
{
    private $route;
    private $views;
    private $oncoprod;
    private $oncoexo;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/notificacoes/notificacoes');
        $this->views = 'fornecedor/notificacoes';

        $this->load->model('m_compradores', 'comprador');

        $this->oncoprod = explode(',', ONCOPROD); 
        $this->oncoexo = explode(',', ONCOEXO); 
    }

    public function index()
    {
        $page_title = "Configurações de notificação";

        $data['to_datatable'] = "{$this->route}/to_datatable";
        $data['url_update'] = "{$this->route}/detail";
        $data['url_status'] = "{$this->route}/setStatus";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                'type' => 'a',
                'id' => 'btnVoltar',
               'url' => "javascript:history.back(1)",
                'class' => 'btn-secondary',
                'icone' => 'fa-arrow-left',
                'label' => 'Retornar'
            ],
                [
                    'type'  => 'a',
                    'id'    => 'btnInsert',
                    'url'   => base_url('fornecedor/notificacoes/Email'),
                    'class' => 'btn-primary',
                    'icone' => 'fa-edit',
                    'label' => 'Configurar E-mails' 
                ]
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    public function to_datatable()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'modulo_notificacoes',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'titulo', 'dt' => 'titulo'],
                ['db' => 'modulo', 'dt' => 'modulo', 'formatter' => function($value, $row) {

                    return "<small>{$value}</small>";
                }],
                ['db' => 'mensagem', 'dt' => 'mensagem', 'formatter' => function($value, $row) {

                    return "<small>{$value}</small>";
                }],
                ['db' => 'ativo', 'dt' => 'ativo'],
                ['db' => 'data_criacao', 'dt' => 'data_criacao', 'formatter' => function($value, $row) {

                    return date('d/m/Y H:i', strtotime($value));
                }]
            ],
            null,
            "tipo = 1"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function setStatus($id, $ativar = null)
    {
        if ( $this->input->is_ajax_request() ) {

            $ativo = ( isset($ativar) ) ? 1 : 0;
            
            $updt = $this->db->where('id', $id)->update('modulo_notificacoes', ['ativo' => $ativo]);

            if ( $updt ) { 
               
                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = $this->notify->errorMessage();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }
}