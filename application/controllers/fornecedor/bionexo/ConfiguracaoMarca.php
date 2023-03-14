<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ConfiguracaoMarca extends MY_Controller
{
    private $route;
    private $views;
    private $DB_BIONEXO;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/bionexo/configuracaoMarca');
        $this->views = 'fornecedor/bionexo/configuracaoMarca';

        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_marca', 'marca');
        $this->load->model('m_configuracao_marca_comprador', 'config_marca');

        $this->DB_BIONEXO = $this->load->database('bionexo', TRUE);
    }

    /**
     * Exibe a tela de confgiruações de marca por comprador
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Configuração de marca por comprador";
       
        # Template
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['header'] = $this->template->header(['title' => $page_title ]);
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
                    'id' => 'btnAdicionar',
                    'url' => "{$this->route}/criar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);

        # URLs
        $data['to_datatable'] = "{$this->route}/datatables";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['url_update'] = "{$this->route}/atualizar";

        # Selects
        $data['compradores'] = $this->comprador->listarCompradoresBionexo();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a tela de cadastrar configuracao_marca_comprador
     *
     * @return view
     */
    public function criar()
    {
        $page_title = "Nova configuração de marca";

        # Template
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['header'] = $this->template->header([ 
            'title' => $page_title,
            'styles' => [ THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css']
        ]);
        $data['scripts'] = $this->template->scripts([

            'scripts' => [ THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js']
        ]);
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
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
                    'form'  => 'formConfigMarca',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);

        # URLs
        $data['form_action'] = "{$this->route}/save";
        $data['return'] = "{$this->route}";

        # Selects
        $data['marcas'] = $this->marca->find("*", null, false, "marca ASC");
        $data['compradores'] = $this->comprador->listarCompradoresBionexo();

        $data['isUpdate'] = false;

        $this->load->view("{$this->views}/create", $data);
    }

    /**
     * exibe a tela para atualizar uma configuracao_marca_comprador
     *
     * @param - INT ID do registro da configuracao_marca_comprador
     * @return view
     */
    public function atualizar($id)
    {
        $page_title = "Atualizar configuração de marca";

        $dados = $this->config_marca->findById($id);

        if ( isset($dados['marcas']) ) {
            
            $dados['marcas'] = json_decode($dados['marcas'], true);
        }

        $data['dados'] = $dados;

        # Template
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
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
                    'form'  => 'formConfigMarca',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);

        # URLs
        $data['form_action'] = "{$this->route}/update/{$id}";
        $data['return'] = "{$this->route}";

        # Selects
        $data['marcas'] = $this->marca->find("*", null, false, "marca ASC");
        $data['comprador'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", "id = {$dados['id_cliente']}", true);

        $data['isUpdate'] = true;

        $this->load->view("{$this->views}/update", $data);
    }

    /**
     * Salva uma configração para acada comprador selecionado
     *
     * @param - POST form modal
     * @return  json
     */
    public function save()
    {
        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('tipo', 'Tipo', 'required');
            $this->form_validation->set_rules('compradores', 'Compradores', 'required');

            if (intval($post['tipo']) == 3) {
            
                $this->form_validation->set_rules('marcas', 'Marca', 'required');
            }

            if ($this->form_validation->run() === false) {

                $errors = [];

                foreach ($post as $key => $value) {

                    $errors[$key] = form_error($key);
                }

                $output = ['type' => 'warning', 'message' => array_filter($errors)];
            } else {

                $compradores = explode(',', $post['compradores']);

                if ( isset($post['marcas']) && !empty($post['marcas']) && intval($post['tipo']) == 3 ) {

                    $arrayMarcas = explode(',', $post['marcas']);

                    $marcas = json_encode($arrayMarcas);
                } else {

                    $marcas = null;
                }

                if ( isset($post['replicarMatriz']) ) {

                    $filiais = $this->fornecedor->find("id", "id_matriz = {$this->session->id_matriz}");
                    $fornecedores = array_column($filiais, 'id');
                } else {

                    $fornecedores[] = $this->session->id_fornecedor;
                }

                $this->db->trans_begin();

                foreach ($fornecedores as $id_fornecedor) {
                   
                    foreach ($compradores as $id_cliente) {

                        $id = $this->config_marca->verifyExist($id_cliente, $id_fornecedor);

                        $data = [
                            'id_cliente' => $id_cliente,
                            'id_fornecedor' => $id_fornecedor,
                            'tipo' => $post['tipo'],
                            'marcas' =>  ( isset($marcas) ) ? $marcas : null
                        ];

                        if ( $id != false ) {
                           
                            # Atualiza

                            $data['id'] = $id;

                            $this->config_marca->update($data);
                        } else {

                            # Insere novo

                            $this->config_marca->insert($data);
                        }
                    }
                }

                if ($this->db->trans_status() === false) {

                    $this->db->trans_rollback();

                    $output = ['type' => 'warning', 'message' => notify_failed];
                } else {
                    
                    $this->db->trans_commit();

                    $output = ['type' => 'success', 'message' => notify_create];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Atualiza o registro
     *
     * @param - GET INT id do registro
     * @param - POST form modal
     * @return  json
     */
    public function update($id)
    {

        if ( $this->input->is_ajax_request() ) {
            
            $post = $this->input->post();

            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('tipo', 'Tipo', 'required');

            if (intval($post['tipo']) == 3) {
            
                $this->form_validation->set_rules('marcas[]', 'Marcas', 'required');
            }

            if ($this->form_validation->run() === false) {

                $errors = [];

                foreach ($post as $key => $value) {

                    $errors[$key] = form_error($key);
                }

                $output = ['type' => 'warning', 'message' => array_filter($errors)];
            } else {

                $marcas = ( isset($post['marcas']) ) ? json_encode($post['marcas']) : null;

                $data = ['id' => $id, 'tipo' => $post['tipo'], 'marcas' => $marcas ];

                $updt = $this->config_marca->update($data);

                if ( $updt ) {
                        
                    $output = ['type' => 'success', 'message' => notify_update];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função que deleta os registros selecionados do datatable
     *
     * @param - POST - Array de ids
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $id) {

                $this->config_marca->delete($id);
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
     * Obtem os registros da tabela controle_automatica
     *
     * @return  json
     */
    public function datatables()
    {
        $data = $this->datatable->exec(
            $this->input->post(),
            'configuracao_marca_comprador config',
            [
                ['db' => 'config.id', 'dt' => 'id'],
                ['db' => 'config.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'config.tipo', 'dt' => 'tipo', 'formatter' => function ($value, $row) {

                    switch ($value) {
                        case '1':
                            return 'Maior Estoque';
                            break;
                        case '2':
                            return 'Menor preço';
                            break;
                        case '3':
                            return 'Por marca';
                            break;
                    }
                }],
                ['db' => 'config.marcas', 'dt' => 'marcas'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social'],
                ['db' => 'c.razao_social', 'dt' => 'comprador', "formatter" => function ($value, $row) {

                    return ( strlen($row['razao_social']) <= 20 ) ? $row['razao_social'] : trim(substr($row['razao_social'], 0, 20)) . "...";
                }],
                ['db' => 'config.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {
                    
                    return date('d/m/Y H:i', strtotime($value));
                }],
            ],
            [
                ['compradores c', 'c.id = config.id_cliente']
            ],
            "config.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Cria um arquivo excel do datatable
     *
     * @return  file
     */
    public function exportar()  
    {

        $this->db->select(" 
            cnpj,
            razao_social,
            config.tipo,
            DATE_FORMAT(config.data_criacao, '%d/%m/%Y %H:%i') AS data_criacao
        ");
        $this->db->from("configuracao_marca_comprador config");
        $this->db->join('compradores c', "config.id_cliente = c.id");
        $this->db->where("config.id_fornecedor", $this->session->id_fornecedor);
        $this->db->order_by("c.razao_social ASC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1 ) {
            $query[] = [
                'cnpj' => '',
                'razao-social' => '',
                'tipo' => '',
                'data_criacao' => ''
            ];
        }  else {

            foreach ($query as $kk => $row) {
                
                switch ($row['tipo']) {
                    case '1':
                        $tipo = 'Maior Estoque';
                        break;
                    case '2':
                        $tipo = 'Menor preço';
                        break;
                    case '3':
                        $tipo = 'Por marca';
                        break;
                }

                $query[$kk]['tipo'] = $tipo;
            }
        }

        $dados_page = ['dados' => $query , 'titulo' => 'Compradores'];
       
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

/* End of file: Configuracao.php */
