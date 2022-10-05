<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnaliseMercado extends Admin_controller
{
    private $route;
    private $views;
    
    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/admin/configuracoes/analiseMercado');
        $this->views = 'admin/configuracoes/analiseMercado';

        $this->load->model('m_configAnaliseMercado', 'analiseMercado');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
    }

    /**
     * Exibe a tela de configurações de analise de mercado
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Produtos para analise de mercado";

        $data['datasource'] = "{$this->route}/datatables";
        $data['urlDelete'] = "{$this->route}/delete_multiple";

        $data['header'] = $this->template->header([ 'title' => $page_title ]);
        $data['navbar']  = $this->template->navbar();
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

        $data['estados'] = $this->estado->find("id, uf, CONCAT(uf, ' - ', descricao) AS estado", null, false, 'estado ASC');

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * exibe o modal de criar configuração
     *
     * @return view
     */
    public function openModal()
    {
        $data['page_title'] = "Nova Configuração de analise de mercado";

        $data['formAction'] = "{$this->route}/save";
      
        $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia");
        $data['estados'] = $this->estado->find("id, uf, descricao");

        $data['urlSelectprodutos'] = "{$this->route}/selectProducts";

        $this->load->view("{$this->views}/modal", $data);
    }

    /**
     * Cria um registro de configuração de analise de mercado
     *
     * @return json
     */
    public function save()
    {
        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            // var_dump($post); exit();

            $this->form_validation->set_error_delimiters('<span>', '</span>');
            $this->form_validation->set_rules('codigo', 'Codigo', 'required');
            $this->form_validation->set_rules('id_fornecedor', 'Fornecedor', 'required');
            $this->form_validation->set_rules('preco_medio[]', 'Preço Médio', 'required');
            $this->form_validation->set_rules('preco_minimo[]', 'Preço Mínimo', 'required');
            $this->form_validation->set_rules('estado[]', 'Estado', 'required');

            if ($this->form_validation->run() === false) {
        
                $output = [ 'type' => 'warning', 'message' => $this->form_validation->error_array()];
            } else {

                $save = $this->analiseMercado->gravar($post);

                if ($save) {
                    
                    $output = ['type' => 'success', 'message' => notify_create];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } 
    }

    /**
     * Exibe a lista de configurações
     *
     * @return  json
     */
    public function datatables()
    {

        $datatables = $this->datatable->exec(
            $this->input->post(),
            'config_analise_mercado config',
            [
                ['db' => 'config.id', 'dt' => 'id'],
                ['db' => 'config.codigo', 'dt' => 'codigo'],
                ['db' => 'config.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'f.nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'config.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                    return date('d/m/Y H:i', strtotime($value));
                }],
                ['db' => "JSON_EXTRACT(config.data, '$.precos')", 'dt' => 'data', 'formatter' => function ($value, $row) {

                    $data = json_decode($value);

                    $array = array();

                    return implode(', ', array_column($data, 'estado'));

                    foreach (array_column($data, 'estado') as $uf) {

                        $array[] = "<span class='badge badge-primary mt-1'>{$uf}</span>";
                    }

                    return implode(' ', $array);
                }],
            ],
            [
                ['fornecedores f', 'f.id = config.id_fornecedor'],
                ['produtos_catalogo pc', 'pc.codigo = config.codigo AND pc.id_fornecedor = config.id_fornecedor'],
            ],
            null
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Função que deleta os registros do datatable
     *
     * @return  json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $id) {
                
                $this->analiseMercado->delete($id);
            }

            if ($this->db->trans_status() === false) {

                $this->db->trans_rollback();

                $output = $this->notify->errorMessage();
            } else {

                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função AJAX que obtem os produtos do catalogo
     *
     * @param - POST INT id_fornecedor
     * @return  json
     */
    public function selectProducts()
    {
        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            if ( $post['id_fornecedor'] == 'oncoprod' ) {

                $filial_id = explode(',', ONCOPROD)[0];
                
                $data = $this->db->where('id_fornecedor', $filial_id)->get('produtos_catalogo')->result_array();
            } else {

                $data = $this->db->where('id_fornecedor', $post['id_fornecedor'])->get('produtos_catalogo')->result_array();
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }
}