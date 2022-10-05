<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ControleAutomatica extends MY_Controller
{
    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('/fornecedor/controleAutomatica');
        $this->views = 'fornecedor/controleAutomatica';

        $this->load->model('m_catalogo', 'catalogo');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_fornecedor', 'fornecedor');
    }

    /**
     * Exibe a tela de produtos da cotação automatica
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Controle de produtos cotação Automática";

        # URLs
        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple";
        $data['url_update'] = "{$this->route}/openModalUpdate";

        # Selects
        $data['compradores'] = $this->comprador->find("id, cnpj, razao_social", null, false, 'razao_social ASC');
        $data['estados'] = $this->estado->find("id, uf, descricao", null, false, 'descricao ASC');


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
     * exibe o modal de criar regra da automatica
     *
     * @return view
     */
    public function openModal()
    {
        $data['form_action'] = "{$this->route}/save";
        $data['getList']     = "{$this->route}/getList";

        # Selects
        $data['produtos'] = $this->catalogo->find('codigo, nome_comercial', "id_fornecedor = {$this->session->id_fornecedor}", false, "codigo ASC, nome_comercial ASC");

        $this->load->view("{$this->views}/create", $data);
    }

    /**
     * exibe o modal de atualizar regra da automatica
     *
     * @param INT Id do registro
     * @param INT flag (1 => estado, 2 => comprador)
     * @return view
     */
    public function openModalUpdate($id, $option)
    {
        
        $data['form_action'] = "{$this->route}/update/{$id}";

        $data['option'] = $option;
        $nome_option = ( $option == 1 ) ? 'estados' : 'clientes';

        if ( $option == 1 ) {

            $list = $this->estado->find("id, CONCAT(uf, ' - ', descricao) AS value");
        } else {

            $list = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) as value");
        }

        $dados = $this->db->where("id", $id)->get("controle_automatica")->row_array();

        $ids = json_decode($dados['options'], true);


        foreach ($list as $key => $item) {
            
            if ( in_array($item['id'], $ids[$nome_option]) ) {
               
                $list[$key]['selected'] = 1;
            } else {

                $list[$key]['selected'] = 0;
            }
        }

        $data['dados'] = $dados;
        $data['list'] = $list;
        $data['produto'] = $this->catalogo->find("*", "codigo = {$dados['codigo']} AND id_fornecedor = {$this->session->id_fornecedor}", true);

        $this->load->view("{$this->views}/update", $data);
    }

    /**
     * Cria um registro de controle da automatica
     *
     * @param Array POST form
     * @return json
     */
    public function save()
    {

        if ( $this->input->is_ajax_request() ) {

            $post = $this->input->post();

            $this->form_validation->set_rules('produtos', 'Produtos', 'required');
            $this->form_validation->set_rules('elementos', 'Elementos', 'required');

            if ($this->form_validation->run() === false) {

                $errors = [];

                foreach ($post as $key => $value) {

                    $errors[$key] = form_error($key);
                }

                $output = ['type' => 'warning', 'message' => array_filter($errors)];
            } else {

                $elementos = explode(',', $post['elementos']);
                $produtos = explode(',', $post['produtos']);
                $type = strtolower($post['opcao']);

                $data = [];

                if ( isset($post['replicarMatriz']) ) {
                    
                    $filiais = $this->fornecedor->find("id", "id_matriz = {$this->session->id_matriz}", false, "id ASC");

                    foreach ($filiais as $f) {
                        
                        foreach ($produtos as $codigo_produto) {

                            $data[] = [
                                'codigo' => $codigo_produto,
                                'id_fornecedor' => $f['id'],
                                'id_usuario' => $this->session->id_usuario,
                                'id_estado' => ( $post['opcao'] == 'ESTADOS' ) ? 1 : null,
                                'id_cliente' => ( $post['opcao'] == 'CLIENTES' ) ? 1 : null,
                                'options' => json_encode(["{$type}" => $elementos])
                            ];
                        }
                    }
                } else {

                    foreach ($produtos as $codigo_produto) {

                        $data[] = [
                            'codigo' => $codigo_produto,
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'id_usuario' => $this->session->id_usuario,
                            "{$type}" => 1,
                            'options' => json_encode(["{$type}" => $elementos])
                        ];
                    }
                }

                if ( !empty($data) ) {

                    $this->db->insert_batch("controle_automatica", $data);

                    $output = ['type' => 'success', 'message' => notify_create];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * atualiza um registro de controle da automatica
     *
     * @param INt ID do registro
     * @param Array POST form
     * @return json
     */
    public function update($id)
    {
       
        $post = $this->input->post();

        var_dump($post); exit();

        $this->form_validation->set_rules('elementos', 'Elementos', 'required');

        if ($this->form_validation->run() === false) {

            $errors = [];

            foreach ($post as $key => $value) {

                $errors[$key] = form_error($key);
            }

            $output = ['type' => 'warning', 'message' => array_filter($errors)];
        } else {

            $elementos = explode(',', $post['elementos']);
            $produtos = explode(',', $post['produtos']);
            $type = strtolower($post['opcao']);

            $data = [];

            $dados = $this->db->where("id", $id)->get("controle_automatica")->row_array();

            if ( isset($post['replicarMatriz']) ) {
                
                $filiais = $this->fornecedor->find("id", "id_matriz = {$this->session->id_matriz}", false, "id ASC");

                foreach ($filiais as $id_fornecedor) {
                    
                    $this->db->where("id_fornecedor", $id_fornecedor);
                    $this->db->where("codigo", $dados['codigo']);
                }
            } else {

                foreach ($produtos as $codigo_produto) {

                    $data[] = [
                        'codigo' => $codigo_produto,
                        'id_fornecedor' => $this->session->id_fornecedor,
                        'id_usuario' => $this->session->id_usuario,
                        'id_estado' => ( $post['opcao'] == 'ESTADOS' ) ? 1 : null,
                        'id_cliente' => ( $post['opcao'] == 'CLIENTES' ) ? 1 : null,
                        'options' => json_encode(["{$type}" => $elementos])
                    ];
                }
            }

            if ( !empty($data) ) {

                $this->db->insert_batch("controle_automatica", $data);

                $output = ['type' => 'success', 'message' => notify_create];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

       $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * obtem os dados para o datatable de prazo por estado
     *
     * @return json
     */
    public function to_datatable_estado()
    {
        $data = $this->datatable->exec(
            $this->input->post(),
            'controle_automatica ca',
            [
                ['db' => 'ca.id', 'dt' => 'id'],
                ['db' => 'ca.codigo', 'dt' => 'codigo'],
                ['db' => 'ca.id_estado', 'dt' => 'id_estado'],
                ['db' => 'e.uf', 'dt' => 'uf'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'e.descricao', 'dt' => 'descricao', 'formatter' => function ($value, $row) {

                    return "{$row['uf']} - {$value}";
                }],
                ['db' => 'ca.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                    return date("d/m/Y H:i", strtotime($value));
                }]
            ],
            [
                ['estados e', 'e.id = ca.id_estado'],
                ['produtos_catalogo pc', 'pc.codigo = ca.codigo AND pc.id_fornecedor = ca.id_fornecedor'],
            ],
            "ca.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * obtem os dados para o datatable de prazo por comprador
     *
     * @return json
     */
    public function to_datatable_cnpj()
    {
        $data = $this->datatable->exec(
            $this->input->post(),
            'controle_automatica ca',
            [
                ['db' => 'ca.id', 'dt' => 'id'],
                ['db' => 'ca.codigo', 'dt' => 'codigo'],
                ['db' => 'ca.id_cliente', 'dt' => 'id_cliente'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'c.razao_social', 'dt' => 'razao_social', 'formatter' => function ($value, $row) {

                    return "{$row['cnpj']} - {$value}";
                }],
                ['db' => 'ca.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                    return date("d/m/Y H:i", strtotime($value));
                }]
            ],
            [
                ['compradores c', 'c.id = ca.id_cliente'],
                ['produtos_catalogo pc', 'pc.codigo = ca.codigo AND pc.id_fornecedor = ca.id_fornecedor'],
            ],
            "ca.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Deleta os registros selecionados do datatable
     *
     * @return json
     */
    public function delete_multiple($all = null)
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();


            if ( isset($all) ) {

                $this->db->where("id_fornecedor", $this->session->id_fornecedor);
                $del = $this->db->delete("controle_automatica");

                if ( $del ) {
                    
                    $output = ['type' => 'success', 'message' => notify_delete];
                } else {

                    $output = ['type' => 'warning', 'message' => notify_failed];
                }
            } else {

                if (!isset($post['el'])) {

                    $output = ['type' => 'warning', 'message' => 'Nenhum registro selecionado'];

                    $this->output->set_content_type('application/json')->set_output(json_encode($output));
                    return;
                }

                $this->db->trans_begin();

                foreach ($post['el'] as $item) {

                    $this->db->where('id', $item)->delete("controle_automatica");
                }

                if ($this->db->trans_status() === false) {

                    $this->db->trans_rollback();

                    $output = ['type' => 'warning', 'message' => notify_failed];
                } else {

                    $this->db->trans_commit();

                    $output = ['type' => 'success', 'message' => notify_delete];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * obtem os dados dos estados ou compradores
     *
     * @param string 
     * @return json
     */
    public function getList($option)
    {
        
        if ( $this->input->is_ajax_request() ) {

            if ( $option == 'ESTADOS') {

                $data = $this->estado->find("id, CONCAT(uf, ' - ', descricao) AS value");
            } else {

                $data = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) as value");
            }

            $output = ['type' => (empty($data) ? 'warning' : 'success'), 'data' => $data];

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Cria um arquivo excel com todos os registros do datatables
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select("ca.codigo");
        $this->db->select("pc.nome_comercial");
        $this->db->select("CONCAT(e.uf, ' - ', e.descricao) AS estado");
        $this->db->from("controle_automatica ca");
        $this->db->join('estados e', "e.id = ca.id_estado");
        $this->db->join('produtos_catalogo pc', "pc.codigo = ca.codigo AND pc.id_fornecedor = ca.id_fornecedor");
        $this->db->where('ca.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where("ca.id_cliente is null");
        $this->db->order_by("pc.nome_comercial ASC");

        $query_estados = $this->db->get()->result_array();

        $this->db->select("ca.codigo");
        $this->db->select("pc.nome_comercial");
        $this->db->select("CONCAT(c.cnpj, ' - ', c.razao_social) AS comprador");
        $this->db->from("controle_automatica ca");
        $this->db->join('compradores c', "c.id = ca.id_cliente");
        $this->db->join('produtos_catalogo pc', "pc.codigo = ca.codigo AND pc.id_fornecedor = ca.id_fornecedor");
        $this->db->where('ca.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where("ca.id_estado is null");
        $this->db->order_by("pc.nome_comercial ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1 ) {
           $query_estados[] = [
                'codigo' => '',
                'produto' => '',
                'estado' => '',
                'criado_em' => ''
           ];
        }

        if (count($query_clientes) < 1 ) {
            $query_clientes[] = [
                'codigo' => '',
                'produto' => '',
                'comprador' => '',
                'criado_em' => ''
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