<?php


class Preco_fixo extends MY_Controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/preco_fixo');
        $this->views = 'fornecedor/regras_vendas/preco_fixo';

        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_preco_fixo', 'preco');
    }

    public function index()
    {

        $page_title = "Preços Fixos";

        $data['to_datatable'] = "{$this->route}/to_datatable";
        $data['url_delete'] = "{$this->route}/delete/";
        $data['url_update'] = "{$this->route}/openModalUpdate";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css'
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js'
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);


    }

    /**
     * Obtem os dados para o datatable  de controle de cotações por responsaveis
     *
     * @return json
     */
    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_preco_fixo',
            [
                ['db' => 'id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'cnpj', 'dt' => 'cnpj'],
                ['db' => 'nome_fantasia', 'dt' => 'nome_fantasia'],
                ['db' => 'id_estado', 'dt' => 'id_estado'],
                ['db' => 'estado', 'dt' => 'estado'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'preco_base', 'dt' => 'preco_base', 'formatter' => function ($r) {
                    return number_format($r, 4, ',', '.');
                }],

            ],
            null,
            'id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function openModal()
    {
        $data['form_action'] = "{$this->route}/save";
        $data['getList'] = "{$this->route}/getList";
        $data['isUpdate'] = FALSE;


        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Registra um controle de cotação responsaveis
     *
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $response = [];

            $post = $this->input->post();

            $this->form_validation->set_rules('consultor', 'Consultor', 'required');
            $this->form_validation->set_rules('gerente', 'Gerente', 'required');
            $this->form_validation->set_rules('assistente', 'Assistente', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = [];
                foreach ($this->input->post() as $key => $value) {
                    $errors[$key] = form_error($key);
                }

                $response['errors'] = array_filter($errors);
                $response['status'] = false;
            } else {
                $gravado = $this->responsaveis->gravar();
                if ($gravado) {
                    $response['status'] = true;
                    $response['message'] = 'Gravado com sucesso';
                } else {
                    $error = $this->db->error();
                    $response['status'] = false;
                    $response['errors'][0] = $error['message'];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }

    public function openModalUpdate($codigo, $id_cliente, $id_estado)
    {
        $data['form_action'] = "{$this->route}/update";
        $row = [];

        if (!$id_cliente == null) {

            $row = $this->db
                ->where('id_cliente', $id_cliente)
                ->where('codigo', $codigo)
                ->where('id_fornecedor', $this->session->id_fornecedor)
                ->get('vw_produtos_preco_fixo')
                ->row_array();

        }

        if (!$id_estado == 'null') {

            $row = $this->db
                ->where('id_estado', $id_estado)
                ->where('codigo', $codigo)
                ->where('id_fornecedor', $this->session->id_fornecedor)
                ->get('vw_produtos_preco_fixo')
                ->row_array();
        }

        $data['isUpdate'] = true;
        $data['dados'] = $row;

        $data['urlDelete'] = "{$this->route}/delete/";

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Deleta os registros de controle de responsaveis cotações
     *
     * @return json
     */
    public function delete()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            if (!empty($post['id_estado'])) {
                $data = [
                    'preco_base' => dbNumberFormat($post['preco']),
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $post['codigo'],
                    'id_estado' => $post['id_estado']
                ];
            } else if (!empty($post['id_cliente'])) {
                $data = [
                    'preco_base' => dbNumberFormat($post['preco']),
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $post['codigo'],
                    'id_cliente' => $post['id_cliente']
                ];
            }

            $d = $this->preco->deletar($data);

            if (!$d) {
                $output = ['type' => 'warning', 'message' => notify_failed];
            } else {
                $output = ['type' => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Atualiza um controle de cotação responsaveis
     *
     * @return json
     */
    public function update()
    {
        $response = [];

        $this->form_validation->set_rules('codigo', 'Produto', 'required');
        $this->form_validation->set_rules('preco', 'Preço', 'required');

        if ($this->form_validation->run() === false) {

            $errors = [];

            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $response['errors'] = array_filter($errors);
            $response['status'] = false;

        } else {

            $post = $this->input->post();

            if (!empty($post['id_estado'])) {
                $data = [
                    'preco_base' => dbNumberFormat($post['preco']),
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $post['codigo'],
                    'id_estado' => $post['id_estado']
                ];
            } else if (!empty($post['id_cliente'])) {
                $data = [
                    'preco_base' => dbNumberFormat($post['preco']),
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $post['codigo'],
                    'id_cliente' => $post['id_cliente']
                ];
            }

            $id = $this->preco->atualizar($data);

            if ($id) {

                $response['status'] = true;
                $response['message'] = 'Gravado com sucesso!';
            } else {
                $error = $this->db->error();
                $response['status'] = false;
                $response['errors'][0] = $error['message'];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    /**
     * Cria um arquivo excel com todos os registros de responsaveis cotações controle do fornecedor logado
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select("cnpj as CNPJ, nome_fantasia as Cliente, estado as Estado, codigo as Codigo, nome_comercial as Produto, preco_base as Preço");
        $this->db->from("vw_produtos_preco_fixo");
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);

        $query = $this->db->get()->result_array();


        if (count($query) < 1) {
            $query[] = [
                'cnpj' => '',
                'comprador' => '',
                'estado' => '',
                'codigo' => '',
                'produto' => '',
                'preco_base' => '',
            ];
        }


        $dados_page1 = ['dados' => $query, 'titulo' => 'Produtos Preços Fixo'];


        $exportar = $this->export->excel("planilha.xlsx", $dados_page1);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

}
