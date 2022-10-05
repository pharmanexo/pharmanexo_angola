<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notificacoes extends Admin_controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/admin/notificacoes/notificacoes');
        $this->views = 'admin/notificacoes';
    }

    /**
     * Exibe a tela inicial
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Configurações de Notificação";

        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/openModal";
        $data['url_status'] = "{$this->route}/setStatus";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple";

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title, 
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/openModal",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    public function openModal($id = null)
    {
        if ( isset($id) ) {

            $data['title'] = "Atualizar notificação";
            $data['form_action'] = "{$this->route}/update/{$id}";
            $data['modulo'] = $this->db->where('id', $id)->get('modulo_notificacoes')->row_array();
        } else {

            $data['title'] = "Nova notificação";
            $data['form_action'] = "{$this->route}/save";
        }

        $this->load->view("{$this->views}/modal", $data);
    }

    public function save()
    {
        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            $post['tipo'] = 0;
            
            if ( $this->db->insert('modulo_notificacoes', $post) ) {
                
                $output = ['type' => 'success', 'message' => notify_create];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function update($id)
    {
        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            if ( $this->db->where('id', $id)->update('modulo_notificacoes', $post) ) {
                    
                $output = ['type' => 'success', 'message' => 'Registro salvo com sucesso.'];
            } else {

                $output = ['type' => 'warning', 'message' => 'Erro ao salvar registro, tente novamente!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
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

    /**
     * Exibe a lista de notifiacoes
     *
     * @return  json
     */
    public function datatables()
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
            "tipo = 0"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }
}