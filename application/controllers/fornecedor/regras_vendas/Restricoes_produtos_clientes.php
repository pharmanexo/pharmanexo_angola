<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Restricoes_produtos_clientes extends MY_Controller
{
    private $route;
    private $views;
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/regras_vendas/restricoes_produtos_clientes');
        $this->views = 'fornecedor/regras_vendas/restricoes_produtos_clientes';

        $this->load->model('m_restricao_produto_cliente', 'restricao_produto_cliente');
        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_produto', 'produto');
        $this->load->model('m_estados', 'estado');
        $this->oncoprod = explode(',', ONCOPROD);
    }

    /**
     * exibe a view fornecedor/regras_vendas/restricoes_produtos_clientes/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Restrições de Produtos";

        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado/";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";

        $data['header'] = $this->template->header(['title' => $page_title, ]);
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
                    'url' => "{$this->route}/selecionarProdutos",
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
     * exibe a view fornecedor/regras_vendas/restricoes_produtos_clientes/form.php
     *
     * @return view
     */
    public function selecionarProdutos()
    {
        $page_title = 'Passo 1 - Selecione Produtos';

        # Usuarios do marketplace não veem a aba de compradores
        if ( isset($this->session->id_tipo_venda) && $this->session->id_tipo_venda == 1 ) {

            $data['subtitle'] = "Passo 2 - Selecione estados";
            $data['labelSubtitle'] = "Estados";
        } else {
            
            $data['subtitle'] = "Passo 2 - Selecione estados ou compradores(CNPJ)";
            $data['labelSubtitle'] = "Estados ou Compradores (CNPJ)";
        }

        $data['header'] = $this->template->header([
            'title' => 'Restrições Produtos Clientes',
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.css',
                THIRD_PARTY . 'plugins/select.dataTables.min.css'
            ]
        ]);

        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportarProdutos",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel', 
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btnAdicionar',
                    'form' => 'formRestricoes',
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Salvar Registro'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.js',
                THIRD_PARTY . 'plugins/dataTables.select.min.js'
            ]
        ]);

        $data['form_action'] = "{$this->route}/save";
        $data['modal_elementos'] = "{$this->route}/openModal";
        $data['datatable_produtos'] = "{$this->route}/to_datatable_produtos";
        $data['integradores'] = $this->db->get('integradores')->result_array();

        $this->load->view("{$this->views}/form", $data);
    }

    /**
     * Exibe o modal de estados ou compradores
     *
     * @param String tipo de select
     * @return view
     */
    public function openModal($option)
    {
        $data['title'] = ($option == 'ESTADOS') ? 'Selecione os Estados' : 'Selecione os CNPJs';

        switch ($option) {
            case 'ESTADOS':
                $result = $this->estado->getList();
                $data['label'] = "Estados";
                foreach ($result as $value) {
                    $rows[] = ['id' => $value['id'], 'descricao' => $value['estado']];
                }
                break;

            case 'CLIENTES':
                $result = $this->restricao_produto_cliente->getList($option);
                $data['label'] = "Clientes";
                foreach ($result as $value) {
                    $rows[] = ['id' => $value['id'], 'descricao' => $value['cliente']];
                }
                break;
        }

        if (isset($rows)) {
            $data['options'] = $rows;
        }

        $this->load->view("{$this->views}/modal", $data);
    }

    /**
     * Cadastra uma restrição de produto
     *
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();


            $this->form_validation->set_rules('produtos', 'Produtos', 'required');
            $this->form_validation->set_rules('opcoes', 'Estado ou CNPJ', 'required');

            if ($this->form_validation->run() === FALSE) {

                $errors = [];

                foreach ($post as $key => $value) {
                    $errors .= form_error($key, '<p>', '</p>');
                }

                $this->session->set_flashdata('warning', ['type' => 'warning', 'message' => "{$errors}"]);
                redirect("{$this->route}/selecionarProdutos");
            } else {

                $gravado = $this->restricao_produto_cliente->gravar();

                if ($gravado) {

                    $this->session->set_flashdata('warning', ['type' => 'success', 'message' => notify_create]);
                    redirect($this->route);
                } else {

                    $this->session->set_flashdata('warning', ['type' => 'warning', 'message' => notify_failed ]);
                    redirect("{$this->route}/selecionarProdutos");
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } 
    }

    /**
     * Deleta os registros selecionados do datatable de restrições
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            foreach ($post['el'] as $item) {

                $this->restricao_produto_cliente->excluir($item);
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
     * obtem os dados para o datatable de vendas diferenciadas por estado
     *
     * @return json
     */
    public function to_datatable_estado()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'restricoes_produtos_clientes',
            [
                ['db' => 'restricoes_produtos_clientes.id', 'dt' => 'id'],
                ['db' => 'restricoes_produtos_clientes.id_estado', 'dt' => 'id_estado'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'i.desc', 'dt' => 'integrador'],
                ['db' => 'estados.uf', 'dt' => 'uf'],
                [
                    'db' => 'pc.apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . $d;
                }],
                [
                    'db' => 'estados.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {
                        return "{$row['uf']} - {$value}";
                    }
                ]
            ],
            [
                ['estados', 'estados.id = restricoes_produtos_clientes.id_estado'],
                ['integradores i', 'i.id = restricoes_produtos_clientes.integrador'],
                ['produtos_catalogo pc', 'pc.codigo = restricoes_produtos_clientes.id_produto  AND pc.id_fornecedor = restricoes_produtos_clientes.id_fornecedor', 'LEFT']
            ],
            'restricoes_produtos_clientes.id_fornecedor = ' . $this->session->userdata('id_fornecedor'),
            "codigo, id_estado, integrador"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados para o datatable de vendas diferenciadas por comprador
     *
     * @return json
     */
    public function to_datatable_cnpj()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'restricoes_produtos_clientes',
            [
                ['db' => 'restricoes_produtos_clientes.id', 'dt' => 'id'],
                ['db' => 'restricoes_produtos_clientes.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'i.desc', 'dt' => 'integrador'],
                ['db' => 'compradores.cnpj', 'dt' => 'cnpj'],
                [
                    'db' => 'pc.apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . $d;
                }],
                [
                    'db' => 'compradores.razao_social',
                    'dt' => 'razao_social',
                    'formatter' => function ($value, $row) {
                        return "{$row['cnpj']} - {$value}";
                    }
                ]
            ],
            [
                ['compradores', 'compradores.id = restricoes_produtos_clientes.id_cliente'],
                ['integradores i', 'i.id = restricoes_produtos_clientes.integrador'],
                ['produtos_catalogo pc', 'pc.codigo = restricoes_produtos_clientes.id_produto AND pc.id_fornecedor = restricoes_produtos_clientes.id_fornecedor', 'LEFT']
            ],
            'restricoes_produtos_clientes.id_fornecedor = ' . $this->session->userdata('id_fornecedor'),
            'codigo, id_cliente, integrador'
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados para o datatable de produtos
     *
     * @return json
     */
    public function to_datatable_produtos()
    {

        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo',
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'apresentacao','dt' => 'produto_descricao', "formatter" => function ($value, $row) {

                    return $row['nome_comercial'] . " - " . $value;
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'bloqueado', 'dt' => 'bloqueado']
            ],
            null,
            "id_fornecedor = {$this->session->userdata('id_fornecedor')}"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Cria um arquivo excel com todos os registros de restrições
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select(" 
            CONCAT(e.uf, ' - ', e.descricao) AS estado,
            CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END  AS produto");
        $this->db->from("restricoes_produtos_clientes rpc");
        $this->db->join('estados e', "e.id = rpc.id_estado");
        $this->db->join('produtos_catalogo pc', "pc.codigo = rpc.id_produto", 'left');
        $this->db->where('rpc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->group_by("codigo, id_estado");
        $this->db->order_by("estado ASC");

        $query_estados = $this->db->get()->result_array();

       
        $this->db->select(" 
            CONCAT(c.cnpj, ' - ', c.razao_social) AS cnpj,
            CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END AS produto");
        $this->db->from("restricoes_produtos_clientes rpc");
        $this->db->join('compradores c', "c.id = rpc.id_cliente");
        $this->db->join('produtos_catalogo pc', "pc.codigo = rpc.id_produto", 'left');
        $this->db->where('rpc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->group_by("codigo, id_cliente");
        $this->db->order_by("cnpj ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1 ) {
           $query_estados[] = [
                'estado' => '',
                'produto' => ''
           ];
        }

        if (count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'cnpj' => '',
                'produto' => ''
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

    /**
     * Cria um arquivo excel com todos os registros de produtos
     *
     * @return file
     */
    public function exportarProdutos()
    {
        $this->db->select("
            pc.codigo, 
            CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) AS descricao,
            pc.marca");
        $this->db->from("produtos_catalogo pc");
        $this->db->where('pc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->order_by("descricao ASC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'descricao' => '',
                'marca' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

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

/* End of file: Restricoes.php */
