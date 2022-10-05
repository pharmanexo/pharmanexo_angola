<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controle_cotacoes extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/controle_cotacoes');
        $this->views = 'fornecedor/regras_vendas/controle_cotacoes';

        $this->load->model('m_controle_cotacoes', 'controle_cotacoes');
        $this->load->model('m_compradores', 'compradores');
    }

    /**
     * exibe a view fornecedor/regras_vendas/controle_cotacoes/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Controle de Cotações";

        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj";
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
     * Exibe o modal de cadastrar controle de cotação
     *
     * @return view
     */
    public function openModal()
    {
        $data['form_action'] = "{$this->route}/save";
        $data['getList']     = "{$this->route}/getList";
        $data['isUpdate']    = FALSE;
        $data['integradores'] = $this->db->get('integradores')->result_array();

        $this->load->view("{$this->views}/form", $data);
    }

    public function openModalUpdate($id)
    {
        $data['form_action'] = "{$this->route}/update";
        $data['dados'] = $this->controle_cotacoes->getById($id);
        $data['isUpdate'] = true;

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Registra um controle de cotação
     *
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $response = [];

            $post = $this->input->post();

            $this->form_validation->set_rules('regra_venda', 'Regra de venda', 'required');
            $this->form_validation->set_rules('opcao', 'Opções', 'required');

            if ($this->form_validation->run() === FALSE) {
                $errors = [];
                foreach ($this->input->post() as $key => $value) {
                    $errors[$key] = form_error($key);
                }

                $response['errors'] = array_filter($errors);
                $response['status'] = false;
            } else {
                $gravado = $this->controle_cotacoes->gravar();
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

    /**
     * Atualiza um controle de cotação
     *
     * @return json
     */
    public function update()
    {
        $response = [];

        $this->form_validation->set_rules('regra_venda', 'Tipo de Venda', 'required');

        if ($this->form_validation->run() === false) {

            $errors = [];

            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            $response['errors'] = array_filter($errors);
            $response['status'] = false;
        } else {

            $data = $this->input->post();
            $id = $this->controle_cotacoes->update($data);

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
     * Deleta os registros de controle de cotações
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();


            if (!isset($post['el'])) {

                $output = ['type'    => 'warning', 'message' => 'Nenhum produto selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }

            foreach ($post['el'] as $item) {
                $this->controle_cotacoes->delete($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type'    => 'warning', 'message' => notify_failed];
            } else {
                
                $this->db->trans_commit();

                $output = ['type'    => 'success', 'message' => notify_delete];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Obtem os dados para o datatable  de controle de cotações por estado
     *
     * @return json
     */
    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'controle_cotacoes',
            [
                ['db' => 'controle_cotacoes.id', 'dt' => 'id'],
                ['db' => 'controle_cotacoes.regra_venda', 'dt' => 'regra_venda', "formatter" => function ($value, $row) {
                    return ($value == '0' ? 'Manual' : ($value == '1' ? 'Automática' : 'Manual e Automática' ));
                }],
                ['db' => 'estados.uf', 'dt' => 'uf'],
                ['db' => 'i.desc', 'dt' => 'integrador'],
                ['db' => 'estados.descricao', 'dt' => 'descricao', 'formatter' => function ($value, $row) {
                    return "{$row['uf']} - {$value}";
                }]
            ],
            [
                ['estados', 'estados.id = controle_cotacoes.id_estado'],
                ['integradores i', 'controle_cotacoes.integrador = i.id'],
            ],
            'controle_cotacoes.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Obtem os dados para o datatable  de controle de cotações por comprador
     *
     * @return json
     */
    public function to_datatable_cnpj()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'controle_cotacoes',
            [
                ['db' => 'controle_cotacoes.id', 'dt' => 'id'],
                ['db' => 'controle_cotacoes.regra_venda', 'dt' => 'regra_venda', "formatter" => function ($value, $row) {
                    return ($value == '0' ? 'Manual' : ($value == '1' ? 'Automática' : 'Manual e Automática' ));
                }],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                ['db' => 'i.desc', 'dt' => 'integrador'],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social', 'formatter' => function ($value, $row) {
                    return "{$row['cnpj']} - {$value}";
                }]
            ],
            [
                ['compradores', 'compradores.id = controle_cotacoes.id_cliente'],
                ['integradores i', 'controle_cotacoes.integrador = i.id'],
            ],
            'controle_cotacoes.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Obtem os registros dos estados ou compradores
     *
     * @param string tipo de registro
     * @return json
     */
    public function getList($option)
    {
        $result = $this->controle_cotacoes->getList($option);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Cria um arquivo excel com todos os registros de cotações controle do fornecedor logado
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select(" 
            CONCAT(e.uf, ' - ', e.descricao) AS estado,
            (CASE 
                WHEN cc.regra_venda = 0 THEN 'Manual'
                WHEN cc.regra_venda = 1 THEN 'Automático'
                ELSE 'Manual e Automático' END) AS tipo_de_venda");
        $this->db->from("controle_cotacoes cc");
        $this->db->join('estados e', "e.id = cc.id_estado");
        $this->db->where('cc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("estado ASC");

        $query_estados = $this->db->get()->result_array();

       
        $this->db->select(" 
            CONCAT(c.cnpj, ' - ', c.razao_social) AS cnpj,
            (CASE 
                WHEN cc.regra_venda = 0 THEN 'Manual'
                WHEN cc.regra_venda = 1 THEN 'Automático'
                ELSE 'Manual e Automático' END) AS tipo_de_venda");
        $this->db->from("controle_cotacoes cc");
        $this->db->join('compradores c', "c.id = cc.id_cliente");
        $this->db->where('cc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("cnpj ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1 ) {
           $query_estados[] = [
                'estado' => '',
                'tipo_de_venda' => ''
           ];
        }

        if (count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'cnpj' => '',
                'tipo_de_venda' => ''
            ];
        }

        $dados_page1 = ['dados' => $query_estados , 'titulo' => 'Estados'];
        $dados_page2 = ['dados' => $query_clientes, 'titulo' => 'Compradores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Controle_cotacoes.php */
