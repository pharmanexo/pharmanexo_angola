<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fornecedores extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/fornecedores/fornecedores');
        $this->views = "admin/fornecedores/";

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_tipos_venda', 'tipos_venda');
    }

    /**
     * Exibe a view admin/forncedores/main.php
     *
     * @param   int  $id
     * @return  view
     */
    public function index()
    {
        $page_title = "Fornecedores";
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
     * Exibe o datatables de fornecedores
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'fornecedores',
            [
                ['db' => 'fornecedores.id', 'dt' => 'id'],
                ['db' => 'fornecedores.status', 'dt' => 'status'],
                ['db' => 'fornecedores.razao_social', 'dt' => 'razao_social'],
                ['db' => 'fornecedores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'fornecedores.telefone', 'dt' => 'telefone'],
            ],
            null,
            'fornecedores.status != 3'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Função que cria o fornecedor
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
            $this->form_validation->set_rules('razao_social', 'Nome/Razão Social', 'required');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
//            $this->form_validation->set_rules('senha', 'Senha', 'required');


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
                $salvar = $this->fornecedor->salvar($postData);

                if ( $salvar['status'] ) {

                    $output = ['type' => 'success', 'message' => notify_create];
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
     * Função que atualiza o fornecedor
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
            $this->form_validation->set_rules('razao_social', 'Nome/Razão Social', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
            $this->form_validation->set_rules('cnpj', 'CNPJ', 'required');


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
                $updt = $this->fornecedor->atualizar($postData, $id);
                if ( $updt['status'] ) {

                    $output = ['type' => 'success', 'message' => notify_update];
                } else {

                    $output = ['type' => 'warning', 'message' => $updt['error']];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        else {
            $this->form($id);
        }
    }

    /**
     * Função que altera o status dos fornecedores selecionados para excluido
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {
                $this->fornecedor->excluir($item);
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
     * Função que muda o status do fornecedor
     *
     * @param   int  $id fornecedor
     * @param   int  $opt status 
     * @return  json
     */
    public function updateStatus($id, $opt)
    {
        if ($this->fornecedor->updateStatus($id, $opt)) {

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
     * Função customizada do form_validation, verifica se o cnpj do fornecedor já existe na tabela de fornecedores no DB
     *
     * @param   int  $cnpj
     * @return  bool
     */
    public function check_unique_cnpj($cnpj)
    {
        $id = $this->input->post('id');

        $result = $this->fornecedor->check_unique_cnpj($id, $cnpj);

        // Se retornar TRUE o cnpj já existe
        if ($result) {
            $this->form_validation->set_message('check_unique_cnpj', "O campo {field} já existe, ele deve ser único!");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Exibe a view admin/forncedores/form.php
     *
     * @param   int  $id
     * @return  view
     */
    private function form($id = null)
    {
        $data = [];
        $data['form_action'] = "{$this->route}/criar";
        $page_title = "Cadastro de Fornecedores";
        $data['src_logo'] = base_url('images/avatar-empresa-360sites.png');
        $data['tipo_cadastro'] = 1;
        $data['url_route_success'] = $this->route;
        $data['url_token'] = "{$this->route}/gerarToken";

        $data['matrizes'] = $this->db->select("id, nome")->get('fornecedores_matriz')->result_array();

        if (isset($id)) {
            $page_title = "Edição de Fornecedores";
            $data['tipo_cadastro'] = 2;
            $data['form_action'] = "{$this->route}/atualizar/{$id}";
            $data['fornecedor'] = $this->fornecedor->findById($id);

            # Caminho da pasta da logo do fornecedor
            $root_path_logo = 'public/fornecedores/' . $id . '/' . $data['fornecedor']['logo'];

            if( isset($data['fornecedor']['logo']) && !empty($data['fornecedor']['logo']) && file_exists($root_path_logo) ) {
              
                $data['src_logo'] = base_url('/public/fornecedores/') . $id . '/' . $data['fornecedor']['logo'];
            } else {

                $data['src_logo'] = base_url('images/avatar-empresa-360sites.png');
            }

            $data['url_token'] = "{$this->route}/gerarToken/{$id}";

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
                    'form'  => 'formFornecedor',
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


    public function gerarToken($id = null)
    {
        if (isset($id) && intval($id) > 0){
            $forn = $this->fornecedor->findById($id);

            if (!empty($forn)){
                $request = $this->create_token($forn['cnpj']);

                $this->session->set_userdata('warning', ['type' => 'success', 'message' => 'Token criado com sucesso']);

                redirect("{$this->route}/atualizar/{$id}");

            }




        }


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
        $this->db->from("fornecedores");
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

        $dados_page = ['dados' => $query, 'titulo' => 'Fornecedores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    private function create_token($cnpj){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pharmanexo.com.br/pharma_api/IntegraNexo/homolog/auth/crateToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('cnpj' => $cnpj),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
       return json_decode($response, true);
    }

}

/* End of file: Fornecedores.php */
