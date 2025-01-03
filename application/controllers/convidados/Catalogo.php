<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Produtos
 * Marlon Boecker
 */
class Catalogo extends Rep_controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_catalogo', 'catalogo');
        $this->load->model('m_estados', 'estados');

        $this->route = base_url("/representantes/catalogo/");
        $this->views = "representantes/catalogo/";
    }

    /**
     * Direciona para a função main
     *
     * @return function main
     */
    public function index()
    {

        $this->main();
    }

    /**
     * direciona para a função form
     *
     * @return json/view
     */
    public function insert()
    {

        $this->form();
    }

    /**
     * Atualiza o registro do produto ou direciona para a função form
     *
     * @param - INT codigo do produto 
     * @return json/view
     */
    public function update($codigo)
    {
        if ($this->input->method() == 'post') {
        } else {
            $this->form($codigo);
        }
    }

    /**
     * Cadastra o registro do produto ou direciona para a função form
     *
     * @param - INT codigo do produto 
     * @return json/view
     */
    public function vincular()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $response = [];

            $this->form_validation->set_rules('id_produto', 'Produto', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = [];
                foreach ($post as $key => $value) {
                    $errors[$key] = form_error($key);
                }

                $response['errors'] = array_filter($errors);
                $response['status'] = false;
            } else {
                $response['status'] = true;
                $response['message'] = "Salvo com sucesso!";
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            $page_title = "Cadastro de Produtos";

            $data['produto'] = $this->input->post();

            $data['form_action'] = "{$this->route}vincular";

            $data['slct2_produtos'] = "{$this->route}to_select2_produtos";
            $data['url_delete'] = "{$this->route}delete";
            $data['url_new_prod'] = "{$this->route}insert";

            $data['estados'] = $this->estados->get();

            // tmp_rep
            $data['header'] = $this->tmp_rep->header([
                'title' => $page_title,
            ]);

            $data['navbar'] = $this->tmp_rep->navbar();
            $data['sidebar'] = $this->tmp_rep->sidebar();

            $data['heading'] = $this->tmp_rep->heading([
                'page_title' => $page_title,
                'buttons' => [
                    [
                        'type' => 'a',
                        'id' => 'btnCancelar',
                        'url' => "{$this->route}",
                        'class' => 'btn-secondary',
                        'icone' => 'fa-arrow-left',
                        'label' => 'Voltar',
                    ],
                ]
            ]);

            $data['scripts'] = $this->tmp_rep->scripts([
                'scripts' => [
                    THIRD_PARTY . 'plugins/jquery.form.min.js',
                    THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
                ]
            ]);

            $this->load->view("{$this->views}/vincular_produto", $data);
        }
    }

    /** 
     * Altera o status do produto
     *
     * @param   int  codigo do produto
     * @param   int  ativar/desativar produto
     * @return  json
     */
    public function block_product($codigo = null, $ativar = 0)
    {
        if (isset($codigo)) {

            $status = ($ativar == 1) ? 0 : 1;
            $ativo = ($ativar == 1) ? 1 : 0;
            $message = ($ativar == 1) ? 'ativado' : 'inativado';

            $produtos_catalogo = $this->db->query("UPDATE produtos_catalogo SET bloqueado = {$status}, ativo = {$ativo} WHERE codigo = {$codigo} AND id_fornecedor = {$this->session->id_fornecedor}");


            if ($query) {

                $output = ["type" => "success", "message" => "Produto {$message} com sucesso"];
            } else {

                $output = ["type" => "warning", "message" => "Erro ao exceutar, tente novamente"];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /** 
     * Pagina inicial
     *
     * @return  view
     */
    private function main()
    {
        $page_title = "Catálogo de Produtos";

        $data['to_datatable'] = "{$this->route}to_datatables/";
        $data['url_update'] = "{$this->route}update/";

        $data['url_block'] = "{$this->route}block_product/";


        // tmp_rep
        $data['header'] = $this->tmp_rep->header([
            'title' => $page_title,
        ]);

        $data['navbar'] = $this->tmp_rep->navbar();
        $data['sidebar'] = $this->tmp_rep->sidebar();

        $data['heading'] = $this->tmp_rep->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
            ]
        ]);

        $data['scripts'] = $this->tmp_rep->scripts([
            'scripts' => []
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe o formulario de cadastrar ou atualizar produto
     *
     * @param INT codigo do produto
     * @return view
     */
    private function form($codigo = null)
    {
        $page_title = "Cadastro de Produtos";

        $data['produto'] = $this->input->post();

        $data['form_action'] = "";

        if (isset($codigo) && !empty($codigo)) {
            $page_title = "Edição de Produtos";
            $data['produto'] = $this->catalogo->find("*", "codigo = {$codigo} AND id_fornecedor = {$this->session->id_fornecedor}", true);
            $data['to_datatable_produtos'] = "{$this->route}to_datatables_produtos_precos/{$codigo}";
            $data['form_action'] = "";
        }

        # var_dump($data['produto']);exit();

        $data['slct2_produtos'] = "{$this->route}to_select2_produtos";
        $data['url_delete'] = "{$this->route}delete";
        $data['url_new_prod'] = "{$this->route}insert";

        $data['estados'] = $this->estados->get("*");
        $data['header'] = $this->tmp_rep->header(['title' => $page_title, ]);
        $data['navbar'] = $this->tmp_rep->navbar();
        $data['sidebar'] = $this->tmp_rep->sidebar();
        $data['heading'] = $this->tmp_rep->heading([ 'page_title' => $page_title, 'buttons' => [ ] ]);
        $data['scripts'] = $this->tmp_rep->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}/vincular_produto", $data);
    }

    /**
     * Obtem a lista de produtos do fornecedor logado
     *
     * @return json
     */
    public function to_datatables()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'produtos_catalogo',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                [
                    'db' => 'nome_comercial',
                    'dt' => 'nome_comercial'
                ],
                [
                    'db' => 'apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . $d;
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'bloqueado', 'dt' => 'bloqueado'],
                ['db' => 'ativo', 'dt' => 'ativo'],
                ['db' => 'preco_unidade', 'dt' => 'preco_unidade', "formatter" => function ($d) {
                    return number_format($d, 4, ',', '.');
                }],
            ],
            null,
            "id_fornecedor = {$this->session->userdata('id_fornecedor')}",
            "codigo"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Obtem a lista de produtos com estoque e preço
     *
     * @param - INT codigo do produto
     * @return json
     */
    public function to_datatables_produtos_precos($codigo)
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_estoque_produtos_fornecedores',
            [
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'preco_unidade', 'dt' => 'preco_unidade', 'formatter' => function ($d) {
                    return number_format($d, 4, ',', '.');
                }],
                ['db' => 'quantidade_unidade', 'dt' => 'quantidade_unidade'],
                ['db' => 'estoque_unitario', 'dt' => 'estoque_unitario'],
                ['db' => 'estado', 'dt' => 'estado'],
                ['db' => 'id_estado', 'dt' => 'id_estado'],
                ['db' => 'lote', 'dt' => 'lote'],
                ['db' => 'validade', 'dt' => 'validade', 'formatter' => function ($d) {
                    return date("d/m/Y", strtotime($d));
                }]
            ],
            null,
            "id_fornecedor = {$this->session->id_fornecedor} AND codigo = {$codigo} AND estoque > 0"

        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * cria um arquivo em excel com os produtos do catalogo do fornecedor logado
     *
     * @return file excel
     */
    public function exportar()
    {
        $this->db->select(" 
            codigo,
            CASE WHEN descricao is null THEN CONCAT(nome_comercial, ' - ', apresentacao) ELSE CONCAT(nome_comercial, ' - ', descricao) END AS descricao,
            marca,
            CASE 
                WHEN bloqueado = 1 THEN 'inativo' ELSE 'ativo' END AS situacao");
        $this->db->from("produtos_catalogo");
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->group_by('codigo');
        $this->db->order_by('codigo');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'descricao' => '',
                'marca' => '',
                'situacao' => ''
            ];
        }

        $dados_page = ['dados' => $query , 'titulo' => 'Produtos'];

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

/* End of file Controllername.php */
