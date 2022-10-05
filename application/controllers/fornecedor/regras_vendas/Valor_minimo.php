<?php

class Valor_minimo extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/valor_minimo');
        $this->views = 'fornecedor/regras_vendas/valor_minimo';

        $this->load->model('m_valor_minimo');
        $this->load->model('m_compradores', 'compradores');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
    }

    /**
     * exibe a view fornecedor/regras_vendas/valor_monimo/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Desconto e Valor Mínimo";

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
     * exibe ao modal para cadastrar um valor minimo
     *
     * @return view
     */
    public function openModal()
    {
        $data['form_action'] = "{$this->route}/save";
        $data['getList'] = "{$this->route}/getList";
        $data['isUpdate'] = false;

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * exibe ao modal para atualizar um valor minimo
     *
     * @param - int ID do registro do valor minimo
     * @return view
     */
    public function openModalUpdate($id)
    {
        $data['form_action'] = "{$this->route}/update";
        $data['dados'] = $this->m_valor_minimo->getById($id);
        $data['isUpdate'] = TRUE;

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Valida e salva o registro de um valor minimo
     *
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $this->form_validation->set_rules('valor_minimo', 'Valor Mínimo', 'required');
            $this->form_validation->set_rules('opcao', 'Opções', 'required');
            $this->form_validation->set_rules('elementos', 'Elementos', 'required');

            if ($this->form_validation->run() === FALSE) {

                $errors = [];

                foreach ($this->input->post() as $key => $value) {

                    $errors[$key] = form_error($key);
                }

                $output = ['type' => 'warning', 'message' => array_filter($errors)];
            } else {

                $gravado = $this->m_valor_minimo->gravar();

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
     * Valida e atualiza o registro de um valor minimo
     *
     * @return json
     */
    public function update()
    {
        $post = $this->input->post();

        $this->form_validation->set_rules('valor_minimo', 'Valor Mínimo', 'required');

        if ($this->form_validation->run() === FALSE) {

            $errors = [];

            foreach ($post as $key => $value) {

                $errors[$key] = form_error($key);
            }

            $output = ['type' => 'warning', 'message' => array_filter($errors)];
        } else {

            $post['valor_minimo'] = dbNumberFormat($post['valor_minimo']);

            $id = $this->m_valor_minimo->update($post);

            if ($id) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {
               
                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Deleta os registros selecionados
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            if (!isset($post['el'])){
                $newdata = [
                    'type'    => 'warning',
                    'message' => 'Nenhum produto selecionado'
                ];

                $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
                return;
            }

            foreach ($post['el'] as $item) {
                $this->m_valor_minimo->excluir($item);
            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $newdata = [
                    'type' => 'warning',
                    'message' => 'Erro ao excluir'
                ];
            } else {
                $this->db->trans_commit();

                $newdata = ['type' => 'success', 'message' => 'Excluidos com sucesso'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
        }
    }

    /**
     * obtem os dados para o datatable de valor minimo por estado
     *
     * @return json
     */
    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'valor_minimo_cliente vmc',
            [
                ['db' => 'vmc.id', 'dt' => 'id'],
                ['db' => 'vmc.valor_minimo', 'dt' => 'valor_minimo', 'formatter' => function ($d) {
                    return number_format($d, 2, ',', '.');
                }],
                ['db' => 'vmc.desconto_padrao', 'dt' => 'desconto_padrao', 'formatter' => function ($d) {
                    return number_format($d, 2, ',', '.');
                }],
                ['db' => 'e.uf', 'dt' => 'uf'],
                ['db' => 'e.descricao', 'dt' => 'descricao', 'formatter' => function ($value, $row) {
                    return "{$row['uf']} - {$value}";
                }]
            ],
            [
                ['estados e', 'e.id = vmc.id_estado']
            ],
            "vmc.id_cliente is null AND vmc.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados para o datatable de valor minimo por comprador
     *
     * @return json
     */
    public function to_datatable_cnpj()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'valor_minimo_cliente vmc',
            [
                ['db' => 'vmc.id', 'dt' => 'id'],
                ['db' => 'vmc.valor_minimo', 'dt' => 'valor_minimo', 'formatter' => function ($d) {
                    return number_format($d, 2, ',', '.');
                }],
                ['db' => 'vmc.desconto_padrao', 'dt' => 'desconto_padrao', 'formatter' => function ($d) {
                    return number_format($d, 2, ',', '.');
                }],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social']
            ],
            [
                ['compradores c', 'c.id = vmc.id_cliente'],
            ],
            "vmc.id_estado is null AND vmc.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem a lista de estados ou compradores
     *
     * @return json
     */
    public function getList($option)
    {
        $result = $this->m_valor_minimo->getList($option);
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    /**
     * Cria um arquivo excel com todos os registros do datatables de valor minimo
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select(" 
            CONCAT(e.uf, ' - ', e.descricao) AS estado,
            vmc.valor_minimo,
            vmc.desconto_padrao");
        $this->db->from("valor_minimo_cliente vmc");
        $this->db->join('estados e', "e.id = vmc.id_estado");
        $this->db->where('vmc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("estado ASC");

        $query_estados = $this->db->get()->result_array();

       
        $this->db->select(" 
            CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador,
            vmc.valor_minimo,
            vmc.desconto_padrao");
        $this->db->from("valor_minimo_cliente vmc");
        $this->db->join('compradores c', "c.id = vmc.id_cliente");
        $this->db->where('vmc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("cnpj ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1 ) {
           $query_estados[] = [
                'estado' => '',
                'valor_minimo' => '',
                'desconto_geral' => ''
           ];
        } else {

            $data = [];
            foreach ($query_estados as $kk => $row) {
               
                $data[] = [
                    'estado' => $row['estado'],
                    'valor_minimo' => number_format($row['valor_minimo'], 2, ',', '.'),
                    'desconto_geral' => number_format($row['desconto_padrao'], 2, ',', '.')
                ];
            }

            $query_estados = $data;
        }

        if (count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'cnpj' => '',
                'valor_minimo' => '',
                'desconto_geral' => ''
            ];
        } else {

            $data = [];
            foreach ($query_clientes as $kk => $row) {
               
                $data[] = [
                    'cnpj' => $row['comprador'],
                    'valor_minimo' => number_format($row['valor_minimo'], 2, ',', '.'),
                    'desconto_geral' => number_format($row['desconto_padrao'], 2, ',', '.')
                ];
            }

            $query_clientes = $data;
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
