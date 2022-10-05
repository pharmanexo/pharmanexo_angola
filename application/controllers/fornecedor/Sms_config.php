<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sms_config extends MY_Controller
{
    private $route;
    private $views;
    private $oncoprod;
    private $oncoexo;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/sms_config');
        $this->views = 'fornecedor/sms_config';

        $this->load->model('m_sms', 'sms_config');

        $this->oncoprod = explode(',', ONCOPROD);
        $this->oncoexo = explode(',', ONCOEXO);
    }

    public function index()
    {
        $page_title = "Configuração de Notificações por SMS";

        $data['to_datatable'] = "{$this->route}/to_datatable";
        $data['url_modal'] = "{$this->route}/openModal";
        $data['url_delete'] = "{$this->route}/delete_multiple";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
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
                    'url' => "{$this->route}/export",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
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


        $this->load->view("{$this->views}/main", $data);
    }

    public function save()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            if(isset($post['modulo']) && !empty($post['modulo'])){
                $post['modulos'] = json_encode($post['modulo']);
                unset($post['modulo']);
                if (!$this->sms_config->insert($post)){
                    var_dump($this->db->error());
                }else{
                    var_dump($this->db->last_query());
                }
            }

          #  $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function update()
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $existente = $this->db
                ->where('id_fornecedor', $this->session->id_fornecedor)
                ->where('id_cliente', $post['id_cliente'])
                ->get('email_notificacao')
                ->row_array();

            if ( isset($existente) && !empty($existente) ) {

                $update = [
                    'gerente' => $post['gerente'],
                    'consultor' => $post['consultor'],
                    'geral' => $post['geral'],
                    'grupo' => $post['grupo']
                ];

                if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

                    $this->db->where_in('id_fornecedor', $this->oncoprod);
                    $this->db->where('id_cliente', $post['id_cliente']);
                    $this->db->update('email_notificacao', $update);
                } elseif ( in_array($this->session->id_fornecedor, $this->oncoexo) ) {

                    $this->db->where_in('id_fornecedor', $this->oncoexo);
                    $this->db->where('id_cliente', $post['id_cliente']);
                    $this->db->update('email_notificacao', $update);
                } else {

                    $this->db->where('id', $existente['id']);
                    $this->db->update('email_notificacao', $update);
                }

                $this->auditor->setLog('update', 'fornecedor/email_notificacao', $update);

                $output = ['type' => 'success', 'message' => 'Registro atualizado com sucesso!'];
            } else {

                $output = ['type' => 'warning', 'message' => 'Este comprador já possui e-mails cadastrados no sistema!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['itens'] as $item) {

                $email = $this->db->where('id', $item)->get('email_notificacao')->row_array();

                if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

                    $this->db->where_in('id_fornecedor', $this->oncoprod);
                    $this->db->where('id_cliente', $email['id_cliente']);
                    $this->db->delete('email_notificacao');
                } elseif ( in_array($this->session->id_fornecedor, $this->oncoexo) ) {

                    $this->db->where_in('id_fornecedor', $this->oncoexo);
                    $this->db->where('id_cliente', $email['id_cliente']);
                    $this->db->delete('email_notificacao');
                } else {

                    $this->db->where('id', $item);
                    $this->db->delete('email_notificacao');
                }
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type'    => 'warning', 'message' => 'Erro ao excluir'];
            }
            else {
                $this->db->trans_commit();

                $output = ['type'    => 'success', 'message' => 'Excluidos com sucesso'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'email_notificacao',
            [
                ['db' => 'email_notificacao.id', 'dt' => 'id'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social', 'formatter' => function($value, $row) {

                    return "{$row['cnpj']} - {$value}";
                }],
                ['db' => 'email_notificacao.gerente', 'dt' => 'gerente', 'formatter' => function($value, $row) {
                    return "<small>{$value}</small>";
                }],
                ['db' => 'email_notificacao.consultor', 'dt' => 'consultor', 'formatter' => function($value, $row) {
                    return "<small>{$value}</small>";
                }],
                ['db' => 'email_notificacao.geral', 'dt' => 'geral', 'formatter' => function($value, $row) {
                    return "<small>{$value}</small>";
                }],
                ['db' => 'email_notificacao.grupo', 'dt' => 'grupo', 'formatter' => function($value, $row) {
                    return "<small>{$value}</small>";
                }],
            ],
            [
                ['compradores c', 'c.id = email_notificacao.id_cliente']
            ],
            "id_fornecedor = {$this->session->id_fornecedor}"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function openModal($id = null)
    {
        if ( isset($id) ) {

            $title = "Atualizar configuração de SMS";
            $form_action = "{$this->route}/update";
            $data['dados'] = $this->db->select('*')->where('id', $id)->get('email_notificacao')->row_array();
        } else {
            $data['dados'] = [
                "id_fornecedor" => $this->session->id_fornecedor,
                "id_usuario" => $this->session->id_usuario,
            ];
            $title = "Nova configuração de SMS";
            $form_action = "{$this->route}/save";
        }

        $data['title'] = $title;
        $data['form_action'] =  $form_action;

        $this->load->view("{$this->views}/modal", $data);
    }

    public function export()
    {
        $this->db->select("CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador, en.gerente, en.consultor, en.geral, en.grupo");
        $this->db->from("email_notificacao en");
        $this->db->join('compradores c', 'c.id = en.id_cliente');
        $this->db->where("en.id_fornecedor = {$this->session->id_fornecedor}");
        $this->db->order_by('comprador ASC');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'comprador' => '',
                'gerente' => '',
                'consultor' => '',
                'geral' => '',
                'grupo' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'email_notificacao'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }
    }
}