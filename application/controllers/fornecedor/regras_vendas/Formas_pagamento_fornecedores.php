<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Formas_pagamento_fornecedores extends MY_Controller
{
    private $route;
    private $views;
    protected $oncoprod;
    protected $oncoexo;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/formas_pagamento_fornecedores');
        $this->views = 'fornecedor/regras_vendas/formas_pagamento_fornecedores';

        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_compradores', 'compradores');

        $this->oncoprod = explode(',', ONCOPROD);
        $this->oncoexo = explode(',', ONCOEXO);
    }

    /**
     * exibe a view fornecedor/regras_vendas/formas_pagamento/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Formas de Pagamento";

        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado";
        $data['to_datatable_cnpj']   = "{$this->route}/to_datatable_cnpj";
        $data['url_delete']          = "{$this->route}/delete/";
        $data['url_update']          = "{$this->route}/openUpdateModal";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css'
            ]
        ]);

        $data['navbar']  = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();

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
                    'type'  => 'a',
                    'id'    => 'btnAdicionar',
                    'url'   => "{$this->route}/openModal",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js',
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js'
            ]
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * exibe o modal de criar forma de pagamento
     *
     * @return view
     */
    public function openModal()
    {
        $data['select_formas_pagamento'] = "{$this->route}/to_select2_formas_pagamento";
        $data['getList']                 = "{$this->route}/getList";
        $data['form_action']             = "{$this->route}/save";
        $data['isUpdate']                = false;

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * exibe o modal de atualizar forma de pagamento
     *
     * @param int ID forma de pagamento
     * @return view
     */
    public function openUpdateModal($id)
    {
        $data['select_formas_pagamento'] = "{$this->route}/to_select2_formas_pagamento";
        $data['dados'] = $this->forma_pagamento_fornecedor->getById($id);

        if ( isset($data['dados']['id_forma_pagamento']) ) {
           $data['forma_pagto'] = $this->db->where('id', $data['dados']['id_forma_pagamento'])->get('formas_pagamento')->row_array();
        }

        $data['form_action'] = "{$this->route}/update";
        $data['isUpdate'] = true;

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Cria um registro forma de pagamento
     *
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $this->form_validation->set_rules('opcao', 'Opções', 'required');
            $this->form_validation->set_rules('elementos', 'Elementos', 'required');
            $this->form_validation->set_rules('id_forma_pagamento', 'Formas de Pagamento', 'required');

            if ($this->form_validation->run() === false) {

                $errors = [];

                foreach ($this->input->post() as $key => $value) {

                    $errors[$key] = form_error($key);
                }

                $output = ['type' => 'warning', 'message' => array_filter($errors)];
            } else {

                $gravado = $this->forma_pagamento_fornecedor->gravar();

                if ($gravado) {

                    $output = ['type' => 'success', 'message' => notify_create];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        } 
    }

    /**
     * Atualiza um registro forma de pagamento
     *
     * @return json
     */
    public function update()
    {
        $post = $this->input->post();

        $this->form_validation->set_rules('id_forma_pagamento', 'Forma de Pagamento', 'required');

        if ($this->form_validation->run() === false) {

            $errors = [];
            foreach ($post as $key => $value) {

                $errors[$key] = form_error($key, '', '');
            }

            $output = ['type' => 'warning', 'message' => array_filter($errors)];
        } else {

            $id = $this->forma_pagamento_fornecedor->update($post);
            
            if ($id) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {
                 
                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Deleta os registros selecionados de formas de pagamento
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            if (!isset($post['el'])) {

                $output = ['type' => 'warning', 'message' => 'Nenhum produto selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }

            foreach ($post['el'] as $item) {

                $this->forma_pagamento_fornecedor->excluir($item);
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
     * obtem os dados para o datatable de formas de pagamento por estado
     *
     * @return json
     */
    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'formas_pagamento_fornecedores',
            [
                ['db' => 'formas_pagamento_fornecedores.id', 'dt' => 'id'],
                ['db' => 'formas_pagamento.descricao',       'dt' => 'formaPagamento'],
                ['db' => 'estados.uf',                       'dt' => 'uf'],
                ['db' => 'estados.descricao',                'dt' => 'estado', 'formatter' => function ($value, $row) {
                    return "{$row['uf']} - {$value}";
                }]
            ],
            [
                ['estados', 'estados.id = formas_pagamento_fornecedores.id_estado'],
                ['formas_pagamento', 'formas_pagamento.id = formas_pagamento_fornecedores.id_forma_pagamento']
            ],
            'formas_pagamento_fornecedores.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados para o datatable de formas de pagamento por comprador
     *
     * @return json
     */
    public function to_datatable_cnpj()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'formas_pagamento_fornecedores',
            [
                ['db' => 'formas_pagamento_fornecedores.id', 'dt' => 'id'],
                ['db' => 'formas_pagamento.descricao',       'dt' => 'formaPagamento'],
                ['db' => 'compradores.cnpj',                    'dt' => 'cnpj'],
                ['db' => 'compradores.razao_social',      'dt' => 'razao_social']
            ],
            [
                ['compradores', 'compradores.id = formas_pagamento_fornecedores.id_cliente'],
                ['formas_pagamento', 'formas_pagamento.id = formas_pagamento_fornecedores.id_forma_pagamento']
            ],
            'formas_pagamento_fornecedores.id_fornecedor = ' . $this->session->userdata('id_fornecedor')
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados de forma de Pagamento para select
     *
     * @return json
     */
    public function to_select2_formas_pagamento()
    {
        $data = [];
        if (isset($_GET['page'])) {
            $page = $this->input->get('page');
            $length = 50;
            $data = [
                "start" => (($page - 1) * 50),
                "length" => $length
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            array_merge($this->input->get(), $data),
            "formas_pagamento",
            [
                ['db' => 'id',        'dt' => 'id'],
                ['db' => 'descricao', 'dt' => 'descricao'],
            ]
        )));
    }

    /**
     * obtem os dados dos estados ou compradores
     *
     * @param string 
     * @return json
     */
    public function getList($option)
    {
        $result = $this->forma_pagamento_fornecedor->getList($option);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Cria um arquivo excel com todos os registros do datatables de formas de pagamento
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select(" 
            CONCAT(e.uf, ' - ', e.descricao) AS estado,
            fp.descricao AS forma_pagamento");
        $this->db->from("formas_pagamento_fornecedores fpf");
        $this->db->join('estados e', "e.id = fpf.id_estado");
        $this->db->join('formas_pagamento fp', "fp.id = fpf.id_forma_pagamento");
        $this->db->where('fpf.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("estado ASC");

        $query_estados = $this->db->get()->result_array();

       
        $this->db->select(" 
            CONCAT(c.cnpj, ' - ', c.razao_social) AS cnpj,
            fp.descricao AS forma_pagamento");
        $this->db->from("formas_pagamento_fornecedores fpf");
        $this->db->join('compradores c', "c.id = fpf.id_cliente");
        $this->db->join('formas_pagamento fp', "fp.id = fpf.id_forma_pagamento");
        $this->db->where('fpf.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("cnpj ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1 ) {
           $query_estados[] = [
                'estado' => '',
                'forma_pagamento' => ''
           ];
        }

        if (count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'cnpj' => '',
                'forma_pagamento' => ''
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

/* End of file: Formas_pagamento.php */
