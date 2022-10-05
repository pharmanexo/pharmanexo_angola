<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Correio extends MY_Controller
{
    protected $page_title;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/correio');
        $this->views = 'fornecedor/correio';

        $this->load->model('m_correio', 'correio');
    }

    
    public function index()
    {
        $page_title = "Correio";

        $data['datatable_src'] = "{$this->route}/datatables";
        $data['url_open_message'] = "{$this->route}/bodyMessage";
        $data['url_delete'] = "{$this->route}/deletar"; //criar funcao deletar
        $data['url_delete_multiplo'] = "{$this->route}/deletar_mais"; //criar funcao deletar mais
        $data['header'] = $this->template->header([ 'title' => 'Correio']);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type'  => 'a',
                    'id'    => 'btnInsert',
                    'url'   => "",//"{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-envelope-open-text',
                    'label' => 'Nova Mensagem'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ]   
            ]
        ]);

        $data['mensagens'] = $this->correio->getMensagens();

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function bodyMessage($id) 
    {
        $page_title = "Correio";

        $data['header'] = $this->template->header([ 'title' => 'Correio']);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type'  => 'a',
                    'id'    => 'btnInsert',
                    'url'   => "",//"{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-envelope-open-text',
                    'label' => 'Nova Mensagem'
                ],
                [
                    'type' => 'button',
                    'id' => '',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => ''
                ]   
            ]
        ]);
        
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
               THIRD_PARTY.'\plugins\ckeditor\ckeditor.js'
            ]
        ]);
        
        $data['row'] = $this->correio->getMessageId($id);
        $this->load->view("{$this->views}/bodyMessage", $data);
    }

    public function datatables($type = 'inbox')
    {
        $where = "id_user_destinatario = {$this->session->id_usuario}";
        if($type == 'sendbox') {
            $where = "id_user_remetente = {$this->session->id_usuario}";
        } elseif($type == 'trashbox') {

            $where = '';
        }

        $datatables = $this->datatable->exec(
            $this->input->post(),
            'correio',
            [
                [
                    'db' => 'correio.id',
                    'dt' => 'id'

                ],
                [
                    'db' => 'correio.dt_registro',
                    'dt' => 'dt_registro'
                ],
                [
                    'db' => 'correio.assunto',
                    'dt' => 'assunto'
                ],
                [
                    'db' => 'correio.prioridade',
                    'dt' => 'prioridade',
                    'formatter' => function($row) {

                        $prioridade = '';

                        switch(intval($row)) {
                            case 0: $prioridade = '<i class="fas fa-long-arrow-alt-down"></i>'; break;
                            case 1: $prioridade = '<i class="fas fa-exchange-alt"></i>'; break;
                            case 2: $prioridade = '<i class="fas fa-long-arrow-alt-up"></i>'; break;
                            default: '<i class="fas fa-long-arrow-alt-down"></i>'; break;

                        }

                        return $prioridade;

                    }   
                ],
                [
                    'db' => 'correio.id',
                    'dt' => 'action',
                    'formatter' => function($value, $row) {
                        return '<a data-toggle="tooltip" href="' . "{$this->route}/delete/{$value}" . '" class="btn btn-link btn_delete text-danger" title="Excluir"><i class="fas fa-trash"></i></a>';
                    }
                ]
            ],
            null,
            // 'vendas_diferenciadas.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
            $where
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

}

/* End of file: correio.php */
