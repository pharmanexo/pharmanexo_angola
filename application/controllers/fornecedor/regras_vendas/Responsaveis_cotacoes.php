<?php


class Responsaveis_cotacoes extends MY_Controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/responsaveis_cotacoes');
        $this->views = 'fornecedor/regras_vendas/responsaveis_cotacoes';

        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_cot_responsaveis', 'responsaveis');
        $this->load->model('m_equipe_comercial', 'equipe');
    }

    public function index()
    {

        $page_title = "Responsaveis Cotacões";

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
                    'url' => "{$this->route}/openModal",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
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
            'vw_responsaveis_cotacoes',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'comprador_razao_social', 'dt' => 'comprador_razao_social', "formatter" => function ($value, $row) {
                    return $row['comprador_cnpj'] . " - " . $value;
                }],
                ['db' => 'assistente', 'dt' => 'assistente'],
                ['db' => 'gerente', 'dt' => 'gerente'],
                ['db' => 'consultor', 'dt' => 'consultor'],
                ['db' => 'comprador_cnpj', 'dt' => 'comprador_cnpj'],
                ['db' => 'comprador_nome_fantasia', 'dt' => 'comprador_nome_fantasia'],

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


        $compradores = $this->comprador->find("id, cnpj, razao_social");
        $data['compradores'] = $compradores;

        $assistentes = $this->equipe->find('id, nome', 'cargo_id = 1');
        $data['assistentes'] = $assistentes;

        $consultores = $this->equipe->find('id, nome', 'cargo_id = 2');
        $data['consultores'] = $consultores;

        $gerentes = $this->equipe->find('id, nome', 'cargo_id = 3');
        $data['gerentes'] = $gerentes;

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

    public function openModalUpdate($id)
    {
        $data['form_action'] = "{$this->route}/update";
        $data['dados'] = $this->responsaveis->findById($id);

        $data['isUpdate'] = true;

        $compradores = $this->comprador->find("id, cnpj, razao_social");
        $data['compradores'] = $compradores;

        $assistentes = $this->equipe->find('id, nome', 'cargo_id = 1');
        $data['assistentes'] = $assistentes;

        $consultores = $this->equipe->find('id, nome', 'cargo_id = 2');
        $data['consultores'] = $consultores;

        $gerentes = $this->equipe->find('id, nome', 'cargo_id = 3');
        $data['gerentes'] = $gerentes;

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Deleta os registros de controle de responsaveis cotações
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();


            if (!isset($post['el'])) {

                $output = ['type' => 'warning', 'message' => 'Nenhum item selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }

            foreach ($post['el'] as $item) {
                $this->responsaveis->delete($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type' => 'warning', 'message' => notify_failed];
            } else {

                $this->db->trans_commit();

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

        $this->form_validation->set_rules('assistente', 'Assistente', 'required');
        $this->form_validation->set_rules('gerente', 'Gerente', 'required');
        $this->form_validation->set_rules('consultor', 'Consultor', 'required');

        if ($this->form_validation->run() === false) {

            $errors = [];

            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $response['errors'] = array_filter($errors);
            $response['status'] = false;
        } else {

            $post = $this->input->post();


            $data = [
                'id' => $post['id'],
                'id_gerente' => $post['gerente'],
                'id_assistente' => $post['assistente'],
                'id_consultor' => $post['consultor'],
            ];


            $id = $this->responsaveis->update($data);

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
        $this->db->select("comprador_cnpj, comprador_razao_socail, assistante, gerente, consultor");
        $this->db->from("vw_responsaveis_cotacoes");
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);

        $query = $this->db->get()->result_array();


        if (count($query) < 1 ) {
            $query[] = [
                'cnpj' => '',
                'comprador' => '',
                'assistente' => '',
                'gerente' => '',
                'consultor' => '',
            ];
        }


        $dados_page1 = ['dados' => $query , 'titulo' => 'Responsáveis Cotações'];


        $exportar = $this->export->excel("planilha.xlsx", $dados_page1);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

}
