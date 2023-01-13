<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Clientes extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('admin/clientes');
        $this->views = 'admin/clientes/';
        $this->load->model('m_compradores', 'cliente');
        $this->load->model('m_tipos_venda', 'tipos_venda');
    }

    public function index()
    {
        $page_title = "Compradores";

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

    public function aprovarConvidado($id)
    {
        $this->db->where('id', $id)->update('compradores', ['situacao_promo' => 1]);

        $output = ['type' => 'success', 'message' => 'Convidado Aprovado!'];
        $this->session->set_userdata('warning', $output);

        redirect("{$this->route}/atualizar/{$id}");
    }

    /**
     * Exibe o datatables de CLientes
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'compradores',
            [
                ['db' => 'compradores.id', 'dt' => 'id'],
                ['db' => 'compradores.status', 'dt' => 'status'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'compradores.email', 'dt' => 'email'],
                ['db' => 'compradores.telefone', 'dt' => 'telefone']
            ],
            null
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Função que cria o cliente
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
            $this->form_validation->set_rules('razao_social', 'Razão Social', 'required|max_length[180]');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|is_unique[compradores.cnpj]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
            $this->form_validation->set_rules('estado', 'Estado', 'required');
            $this->form_validation->set_rules('id_tipo_venda', 'Tipo de Venda', 'required');

            // Se o campo aprovado não for marcado, motivo_recusa fica obrigatório
            if (!isset($postData['aprovado']))
                $this->form_validation->set_rules('motivo_recusa', 'Motivo Recusa', 'required|max_length[45]');

            // Verifica se a validação deu errado
            if ($this->form_validation->run() === FALSE) {
                $errors = [];
                foreach ($postData as $key => $value) {
                    $errors[$key] = form_error($key);
                }
                // Retorna com a lista de erros da validação
                $output = [ 'type' => 'warning', 'message' => array_filter($errors)];
            } else {
                // Manda para a model
                $salvar = $this->cliente->salvar($postData);

                if ( $salvar['status'] ) {

                    $output = ['type' => 'success', 'message' => notify_create];
                }
                else {

                    $output = $this->notify->errorMessage();
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } else {

            $this->form();
        }
    }

    /**
     * Função que altera o status dos clientes selecionados para excluido
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {
                $this->cliente->excluir($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = $this->notify->errorMessage();
            }
            else {
                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função que atualiza o cliente
     *
     * @param   int  $id
     * @return  json
     */
    public function atualizar($id)
    {
        if ($this->input->method() == 'post') {

            $postData = $this->input->post();

            //Validação de campos
            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('razao_social', 'Razão Social', 'required|max_length[180]');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required|callback_check_unique_cnpj');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
            $this->form_validation->set_rules('estado', 'Estado', 'required');
            $this->form_validation->set_rules('id_tipo_venda', 'Tipo de Venda', 'required');

            // Se o campo aprovado não for marcado, motivo_recusa fica obrigatório
            if (!isset($postData['aprovado']))
                $this->form_validation->set_rules('motivo_recusa', 'Motivo Recusa', 'required|max_length[45]');

            // Verifica se ocorreu algum erro na validação
            if ($this->form_validation->run() === FALSE) {
                $errors = [];
                foreach ($postData as $key => $value) {
                    $errors[$key] = form_error($key);
                }
                $output = [ 'type' => 'warning', 'message' => array_filter($errors)];
            }
            else {
                // Manda para a model para atualizar
                $atualizar = $this->cliente->atualizar($postData, $id);
                if ($atualizar['status']) {

                    $output = ['type' => 'success', 'message' => notify_update];
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
     * Função que Muda o status do cliente
     *
     * @param   int  $id cliente
     * @param   int  $opt status 
     * @return  json
     */
    public function updateStatus($id, $opt)
    {
        if ($this->cliente->updateStatus($id, $opt)) {

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
     * Verifica se ja existe cnpj cadastrado
     *
     * @param   string cnpj
     * @return  
     */
    function check_unique_cnpj($cnpj)
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        } else {
            $id = '';
        }

        $result = $this->cliente->check_unique_cnpj($id, $cnpj);
        if ($result == 0) {
            $response = true;
        } else {
            $this->form_validation->set_message('check_unique_cnpj', 'CNPJ must b unique');
            $response = false;
        }

        return $response;
    }

    /**
     * Exibe a view admin/clientes/form.php
     *
     * @param   int  $id
     * @return  view
     */
    private function form($id = null)
    {
        $data = [];
        $data['form_action'] = "{$this->route}/criar";
        $page_title = "Cadastro de Compradores";
        $data['src_logo'] = base_url('images/avatar-empresa-360sites.png');
        $data['tipo_cadastro'] = 1;
        $data['url_route_success'] = $this->route;


        if(isset($id)) {
            $page_title = "Edição de Compradores";
            $data['url_aprovar'] = "{$this->route}/aprovarConvidado/{$id}";
            $data['tipo_cadastro'] = 2;
            $data['form_action'] = "{$this->route}/atualizar/{$id}";
            $data['cliente'] = $this->cliente->findById($id);
            $data['file_url'] = base_url('/public/clientes/') . $id . '/' ;
            $src_logo = base_url('/public/clientes/') . $id . '/' . $data['cliente']['logo'];
            $data['src_logo'] = ( !is_null($data['cliente']['logo']) && $data['cliente']['logo'] != '' ) ? $src_logo : base_url('images/avatar-empresa-360sites.png');
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
                    'type'  => 'submit',
                    'id'    => 'btnSave',
                    'form'  => 'formCliente',
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
        $data['tipos_venda'] = $this->tipos_venda->get();

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Gera arquivo excel do datatable
     *
     * @return  downlaod file
     */
    public function exportar()
    {
        $this->db->select("
            cnpj,
            razao_social,
            telefone,
            (CASE 
                WHEN status = 0 THEN 'Inativo'
                WHEN status = 1 THEN 'Ativo'
                WHEN status = 2 THEN 'Bloqueado' END) AS status");
        $this->db->from("compradores");
        $this->db->where("status != 3");
        $this->db->order_by("razao_social ASC");

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'cnpj' => '',
                'razao_social' => '',
                'telefone' => '',
                'status' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Compradores'];

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

/* End of file: Clientes.php */
