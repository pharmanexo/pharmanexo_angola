<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Correio extends Admin_controller
{
    protected $page_title;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/correio');
        $this->views = 'admin/correio';
        $this->load->model('m_correio', 'correio');
    }

    public function index()
    {
        $page_title = "Correio";

        $data['datatable_src'] = "{$this->route}/datatables";
        $data['url_open_message'] = "{$this->route}/bodyMessage";
        $data['url_delete_multiplo'] = "{$this->route}/deletar_mais"; 

        $data['header'] = $this->template->header([ 'title' => 'Correio']);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type' => 'button',
                    'id' => 'btnDeleteMultiple',
                    'url' => "",
                    'class' => 'btn-danger',
                    'icone' => 'fa-trash',
                    'label' => 'Excluir Selecionados'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnAdicionar',
                    'url'   => "{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-envelope-open-text',
                    'label' => 'Nova Mensagem'
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

    /**
     * Função para deletar varios registros
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {
                $this->correio->excluir($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = [
                    'type'    => 'warning',
                    'message' => 'Erro ao excluir'
                ];
            }
            else {
                $this->db->trans_commit();

                 // Log
                $this->auditor->setlog('update', 'admin/correio', ['ids' => $post['el']]);

                $output = [
                    'type'    => 'success',
                    'message' => 'Excluidos com sucesso'
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function criar()
    {
        if ($this->input->method() == 'post') {
            $response = [];

            $this->form_validation->set_rules('prazo', 'Prazo', 'required');
            $this->form_validation->set_rules('opcao', 'Opção', 'required');
            $this->form_validation->set_rules('elementos', 'Elementos', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = [];
                foreach ($this->input->post() as $key => $value) {
                    $errors[$key] = form_error($key);
                }
                $response['errors'] = array_filter($errors);
                $response['status'] = FALSE;
            } else {
                $gravado = $this->m_prazo_entrega->gravar();
                if ($gravado) {
                    $response['status']  = TRUE;
                    $response['message'] = 'Gravado com sucesso';
                } else {
                    $db_error = $this->db->error();
                    $response['status']       = FALSE;
                    $response['errors']['db'] = $db_error['message'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $this->_form();
        }
    }

    private function _form()
    {
        $data['form_action'] = "{$this->route}/criar";
        $data['getList'] = [];

        $this->load->view("{$this->views}/modal", $data);
    }
}

/* End of file: correio.php */
