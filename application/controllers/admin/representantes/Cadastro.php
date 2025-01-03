<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cadastro extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/representantes/cadastro');
        $this->views = 'admin/representantes/cadastro/';
        $this->load->model('admin/m_representante', 'representante');
        $this->load->model('admin/m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estados');
    }

    public function index()
    {
        $page_title = "Representantes";
        
        $data['datasource'] = "{$this->route}/datatables";
        $data['url_update'] = "{$this->route}/atualizar";
        $data['url_status'] = "{$this->route}/updateStatus/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
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
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type'  => 'a',
                    'id'    => 'btnInsert',
                    'url'   => "{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o datatables de Representantes
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'representantes',
            [
                [ 'db' => 'id', 'dt' => 'id' ],
                [ 'db' => 'nome', 'dt' => 'nome' ],
                [ 'db' => 'cnpj', 'dt' => 'cnpj' ],
                [ 'db' => 'telefone_comercial', 'dt' => 'telefone_comercial' ],
                [ 'db' => 'telefone_celular', 'dt' => 'telefone_celular' ],
                [ 'db' => 'email', 'dt' => 'email' ],
                [ 'db' => 'estado', 'dt' => 'estado' ],
                [ 'db' => 'comissao', 'dt' => 'comissao'],
                [ 'db' => 'status', 'dt' => 'status' ]
            ],
            null,
            'status != 3'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Função que cria o representante
     *
     * @return  json
     */
    public function criar()
    {
        if ($this->input->method() == 'post') {
            // Obtem o request do form
            $postData = $this->input->post();

            // Validação de campos
            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');
            $this->form_validation->set_rules('nome', 'Nome', 'required');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
            $this->form_validation->set_rules('senha', 'Senha', 'required');

            // Verifica se a validação deu errado
            if ($this->form_validation->run() === false) {
                $errors = [];
                foreach ($postData as $key => $value) {
                    $errors[$key] = form_error($key);
                }
                // Retorna com a lista de erros da validação
                $output = [ 'type' => 'warning', 'message' => array_filter($errors)];
            } else {

                // Manda para a model
                $salvar = $this->representante->salvar($postData);

                if ( $salvar['status'] ) {

                    $output = $this->notify->formWarning('create', 'novo.representante');
                } else {

                    $output = $this->notify->errorMessage();
                }

            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } else {
            $this->form();
        }
    }

    /**
     * Função que atualiza o representante
     *
     * @param   int  $id
     * @return  json
     */
    public function atualizar($id)
    {
        if ($this->input->method() == 'post') {

            $postData = $this->input->post();

            // var_dump($_FILES); exit();

            //Validação de campos
            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');
            $this->form_validation->set_rules('nome', 'Nome', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');

            // Verifica se ocorreu algum erro na validação
            if ($this->form_validation->run() === FALSE) {
                $errors = [];
                foreach ($postData as $key => $value) {
                    $errors[$key] = form_error($key);
                }
                $output = [ 'status' => false, 'message' => array_filter($errors)];
            }
            else {
                // Manda para a model para atualizar
                $atualizar = $this->representante->atualizar($postData, $id);
                if ($atualizar['status']) {

                   $output = $this->notify->formWarning('update', 'atualizar.representante');
                } else {

                    $output = $this->notify->errorMessage();
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        else {
            $this->form($id);
        }
    }

    /**
     * Função para deletar representantes
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {
                $this->representante->excluir($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = $this->notify->errorMessage();
            }
            else {
                $this->db->trans_commit();

                 // Log
                $this->auditor->setlog('delete', 'admin/representantes', ['id' => $post['el']]);

                $output = $this->notify->formWarning('delete', 'deletar.representante');
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função que muda o status do representante
     *
     * @param   int  $id representante
     * @param   int  $opt status 
     * @return  json
     */
    public function updateStatus($id, $opt)
    {
        if ($this->representante->updateStatus($id, $opt)) {

            //Log
            $this->auditor->setlog('update', 'admin/representante', ['id' => $id, 'status' => $opt]);

            $output = [
                'type' => 'success',
                'message' => 'Status Alterado com Sucesso!'
            ];
        } else {
            $output = [
                'type' => 'warning',
                'message' => 'Erro ao Alterar Status!'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Exibe a view admin/representantes/form.php
     *
     * @param   int  $id
     * @return  view
     */
    private function form($id = null)
    {
        $data = [];
        $data['form_action'] = "{$this->route}/criar";
        $page_title = "Cadastro de Representantes";
        $data['estados'] = $this->estados->get('id, uf, descricao');
        $data['fornecedores'] = $this->fornecedor->get('id, razao_social');
        $data['tipo_cadastro'] = 1;
        $data['url_route_success'] = $this->route;

        if(isset($id)) {
            $page_title = "Edição de Representantes";
            $data['form_action'] = "{$this->route}/atualizar/{$id}";
            $data['representante'] = $this->representante->findById($id);
            $data['file_url'] = base_url('/public/representantes/') . $id . '/';
            $data['tipo_cadastro'] = 2;


            $data['datatable'] = "{$this->route}/datatable_comissoes/{$id}";
            $data['modal'] = "{$this->route}/open_modal";
            $data['delete'] = "{$this->route}/delete_comissao";

            $rep_estado = $this->db->where('id_representante', $id)->get('representantes_estados')->result_array();
            $data['rep_estado'] = array_column($rep_estado, 'id_estado');


            $data['rep_comissao'] = $this->db->select('*')->where("id_representante = {$id} and id_fornecedor = {$this->session->id_fornecedor}")->get('representantes_fornecedores')->row_array();
        }

        $data['header'] = $this->template->header([ 'title' => $page_title]);
        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons'    => [
                [
                    'type'  => 'a',
                    'id'    => 'btnBack',
                    'url'   => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_comissoes/{$id}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type'  => 'submit',
                    'id'    => 'btnSave',
                    'form'  => 'formRepresentante',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);



        $this->load->view("{$this->views}/form", $data);
    }

    #COMISSOES FORNECEDORES

    /**
     * Função que deleta comissao do fornecedor do representante
     *
     * @param   int  $id
     * @return  json
     */
    public function delete_comissao()
    {
        $post = $this->input->post();

        $this->db->trans_begin();

        foreach ($post['el'] as $id) {
            $this->db->where('id', $id);
            $this->db->delete('representantes_fornecedores');
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

    /**
     * Abre modal comissao de fornecedor do representante
     *
     * @param   int  $id
     * @return  json
     */
    public function open_modal($id = null)
    {
        $data = [];
        $data['form_action'] = "{$this->route}/save_comissao";
        $data['fornecedores'] = $this->fornecedor->get('id, razao_social');
        $data['title'] = "Nova Comissão";

        if (isset($id)) {
            $data['form_action'] = "{$this->route}/save_comissao/{$id}";
            $data['title'] = "Atualizar Comissão";

            $this->db->where('id', $id);

            $data['dados'] = $this->db->get('representantes_fornecedores')->row_array();
        }

        $this->load->view("{$this->views}modal", $data);
    }

    /**
     * cadastra ou atualiza comissao de fornecedor do representante
     *
     * @param   int  $id
     * @return  json
     */
    public function save_comissao($id = null)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            # Atualiza
            if (isset($id)) {

                unset($post['id_representante']);

                $this->db->where('id', $id);

                if ( $this->db->update('representantes_fornecedores', $post) ) {

                    $output = ['type' => 'success', 'message' => 'Comissão atualizada com sucesso'];
                } else {

                    $output = ['type' => 'warning', 'message' => 'Erro ao atualizar comissão.'];
                }
            } 
            # Cadastra novo
            else {

                $this->db->where('id_fornecedor', $post['id_fornecedor']);
                $this->db->where('id_representante', $post['id_representante']);
                if ($this->db->get('representantes_fornecedores')->num_rows()) {

                    $output = ['type' => 'warning', 'message' => 'Este fornecedor já possui registro!'];
                } else {

                    if ( $this->db->insert('representantes_fornecedores', $post) ) {

                        $output = ['type' => 'success', 'message' => 'Comissão cadastrada com sucesso' ];
                    } else {

                        $output = ['type' => 'warning', 'message' => 'Erro ao cadastrar comissão.'];
                    }
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function datatable_comissoes($id_representante)
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'representantes_fornecedores',
            [
                [ 'db' => 'representantes_fornecedores.id', 'dt' => 'id' ],
                [ 'db' => 'representantes_fornecedores.comissao', 'dt' => 'comissao' ],
                [ 'db' => 'fornecedores.razao_social', 'dt' => 'razao_social' ],
                [ 
                    'db' => 'representantes_fornecedores.data_criacao', 
                    'dt' => 'data_criacao', 
                    'formatter' => function ($value, $row) {
                        return date("d/m/Y H:i:s", strtotime($value));
                }],
            ],
            [
                ['fornecedores', 'representantes_fornecedores.id_fornecedor = fornecedores.id']
            ],
            "representantes_fornecedores.id_representante = {$id_representante}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar()
    {
        $this->db->select("
            nome,
            cnpj,
            email,
            telefone_comercial AS telefone,
            (CASE 
                WHEN status = 0 THEN 'Inativo'
                WHEN status = 1 THEN 'Ativo'
                WHEN status = 2 THEN 'Bloqueado' END) AS status");
        $this->db->from("representantes");
        $this->db->where("status != 3");
        $this->db->order_by("nome ASC");

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'nome' => '',
                'cnpj' => '',
                'email' => '',
                'telefone' => '',
                'status' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Representantes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    /**
     * Gera arquivo excel do datatable de comissoes
     *
     * @return  downlaod file
     */
    public function exportar_comissoes($id_representante)
    {
        $this->db->select("
            f.razao_social AS fornecedor,
            rf.comissao,
            DATE_FORMAT(rf.data_criacao, '%d/%m/%Y %H:%i:%s') AS criado_em");
        $this->db->from("representantes_fornecedores rf");
        $this->db->join('fornecedores f', "rf.id_fornecedor = f.id");
        $this->db->where("rf.id_representante = {$id_representante}");
        $this->db->order_by("fornecedor ASC");

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'fornecedor' => '',
                'comissao' => '',
                'criado_em' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Comissoes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Representantes.php */
