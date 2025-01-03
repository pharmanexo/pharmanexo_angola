<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Produtos
 * Marlon Boecker
 */
class Produtos extends MY_Controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_estoque');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_catalogo', 'catalogo');

        $this->route = base_url("/b2b/produtos/");
        $this->views = "fornecedor/produtos/";
    }


    public function index()
    {
        $this->main();
    }

    public function insert()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();
        } else {
            $this->form();
        }
    }

    public function update($codigo)
    {
        if ($this->input->method() == 'post') {
        } else {
            $this->form($codigo);
        }
    }

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

            // TEMPLATE
            $data['header'] = $this->template->header([
                'title' => $page_title,
            ]);

            $data['navbar'] = $this->template->navbar();
            $data['sidebar'] = $this->template->sidebar();

            $data['heading'] = $this->template->heading([
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

            $data['scripts'] = $this->template->scripts([
                'scripts' => [
                    THIRD_PARTY . 'plugins/jquery.form.min.js',
                    THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
                ]
            ]);

            $this->load->view("{$this->views}/vincular_produto", $data);
        }
    }


    public function block_product($codigo = null, $ativar = 0)
    {
        if (isset($codigo)) {

            $status = ($ativar == 1) ? 0 : 1;
            $ativo = ($ativar == 1) ? 1 : 0;
            $message = ($ativar == 1) ? 'ativado' : 'inativado';


            $query = $this->db->query("UPDATE produtos_fornecedores_validades SET bloqueado = {$status}, ativo = {$ativo} WHERE codigo = {$codigo} AND id_fornecedor = {$this->session->id_fornecedor}");

            $produtos_catalogo = $this->db->query("UPDATE produtos_catalogo SET bloqueado = {$status}, ativo = {$ativo} WHERE codigo = {$codigo} AND id_fornecedor = {$this->session->id_fornecedor}");


            if ($query) {
                $warning = [
                    "type" => "success",
                    "message" => "Produto {$message} com sucesso"
                ];
            } else {
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao exceutar, tente novamente"
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function not_found($codigo = null, $ativar = 0)
    {
        if (isset($codigo)) {

            $status = ($ativar == 1) ? 0 : 1;
            $message = ($ativar == 1) ? 'ativado' : 'inativado';

            $query = $this->db->query("UPDATE produtos_fornecedores_validades SET sem_depara = {$status} WHERE codigo = {$codigo} AND id_fornecedor = {$this->session->id_fornecedor}");

            if ($query) {
                $warning = [
                    "type" => "success",
                    "message" => "Produto {$message} com sucesso"
                ];
            } else {
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao exceutar, tente novamente"
                ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    private function main()
    {
        $page_title = "Catálogo de Produtos";

        $data['to_datatable'] = "{$this->route}to_datatables/";
        $data['url_update'] = "{$this->route}update/";

        $data['url_block'] = "{$this->route}block_product/";


        // TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title,]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [

                    'type'  => 'a',
                    'id'    => 'btnInsert',
                    'url'   => "{$this->route}insert",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => []
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    private function form($codigo = null)
    {
        $page_title = "Cadastro de Produtos";

        $data['slct_produtos'] = "{$this->route}to_select2_produtos";
        $data['estados'] = $this->estados->todosEstados()->result_array();
        $data['form_action_produto'] = "{$this->route}saveProduct";
        $data['form_action_precosLotes'] = "{$this->route}savePriceLot";
        $view = "create";

        if (isset($codigo) && !empty($codigo)) {
            $page_title = "Edição de Produtos";

            $view = "vincular_produto";
            $data['open_modal'] = "{$this->route}open_modal/{$codigo}";
            $data['url_delete'] = "{$this->route}deleteLote/{$codigo}";
            $data['dtbl_lotes'] = "{$this->route}to_datatables_lotes/{$codigo}";
            $data['dtbl_precos'] = "{$this->route}to_datatables_precos/{$codigo}";
            $data['produto'] = $this->catalogo->find("*", "codigo = {$codigo} and id_fornecedor = {$this->session->id_fornecedor}", true);
        }


        $data['header'] = $this->template->header(['title' => $page_title ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [

                    'type'  => 'submit',
                    'id'    => 'btnSave',
                    'form'  => 'formPrecosLotes',
                    'class' => 'btn-primary',
                    'icone' => 'fa-save',
                    'label' => 'Salvar Alterações'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/jquery.form.min.js',
                THIRD_PARTY . 'plugins/jquery-validation-1.19.1/dist/jquery.validate.min.js'
            ]
        ]);

        $this->load->view("{$this->views}{$view}", $data);
    }

    public function to_datatables()
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'produtos_catalogo',
            [
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'bloqueado', 'dt' => 'bloqueado'],
                ['db' => 'ativo', 'dt' => 'ativo'],
                [
                    'db' => 'apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    return $r['nome_comercial'] . " - " . $d;
                }],
            ],
            null,
            "id_fornecedor = {$this->session->userdata('id_fornecedor')} and b2b = 1",
            "codigo"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_lotes($codigo)
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'produtos_lote',
            [
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'lote', 'dt' => 'lote'],
                ['db' => 'local', 'dt' => 'local'],
                ['db' => 'estoque', 'dt' => 'estoque'],
                ['db' => 'validade', 'dt' => 'validade', 'formatter' => function ($d) {
                    return date("d/m/Y", strtotime($d));
                }],
            ],
            null,
            "codigo = {$codigo} AND id_fornecedor = {$this->session->userdata('id_fornecedor')}"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_precos($codigo)
    {
        $r = $this->datatable->exec(
            $this->input->get(),
            'vw_produtos_precos',
            [
                ['db' => 'estados.uf', 'dt' => 'estado'],
                ['db' => 'vw_produtos_precos.id_estado', 'dt' => 'id_estado'],
                ['db' => 'preco_unitario', 'dt' => 'preco_unitario', 'formatter' => function ($d) {
                    return number_format($d, 4, ',', '.');
                }],
                ['db' => 'vw_produtos_precos.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($d) {
                    return date("d/m/Y H:i:s", strtotime($d));
                }],
            ],
            [
                ['estados', 'estados.id = vw_produtos_precos.id_estado', 'LEFT']
            ],
            "codigo = {$codigo} AND id_fornecedor = {$this->session->userdata('id_fornecedor')}"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_produtos_precos($id_produto)
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
            "id_fornecedor = {$this->session->id_fornecedor} AND codigo = {$id_produto} AND estoque > 0"

        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_select2_marcas()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            $this->input->get(),
            "marcas",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'marca', 'dt' => 'marca'],
            ]
        )));
    }

    /**
     * Função para criar produto na tabela produto_catalogo
     *
     * @return  json
     */
    public function saveProduct()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $data = [];

            $marca = explode(" ", $post['marca']);

            $this->db->select('*');
            $this->db->where("marca like '%{$marca[0]}%' and cnpj is not null");
            $marca = $this->db->get('marcas')->row_array();

            $data['codigo'] = $post['codigo'];
            $data['apresentacao'] = $post['apresentacao'];
            $data['marca'] =  $post['marca'];
            $data['descricao'] = null;
            $data['nome_comercial'] = $post['produto'];
            $data['id_fornecedor'] = $this->session->id_fornecedor;
            $data['ativo'] = 1;
            $data['aprovado'] = 1;
            $data['bloqueado'] = 0;
            $data['id_marca'] = $marca['id'];
            $data['ean'] = $post['ean'];
            $data['rms'] = $post['rms'];
            $data['quantidade_unidade'] = $post['quantidade_unidade'];
            $data['unidade'] = $post['unidade'];

            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('codigo', $data['codigo']);

            if ( $this->db->get('produtos_catalogo')->num_rows() > 0 ) {

                $output = [ 'type' => 'warning', 'message' => 'Este produto já existe no catálogo!' ];
            } else {

                if ( $this->db->insert('produtos_catalogo', $data) ) {

                    $output = [ 'type' => 'success', 'codigo' => $post['codigo'] ];
                }
                else {
                    $output = [ 'type' => 'warning', 'message' => "Erro ao cadastrar o produto!" ];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função para criar preços e lotes de produto
     *
     * @return  json
     */
    public function savePriceLot()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            if (!isset($post['codigo_produto']) || empty($post['codigo_produto'])) {

                $output = [ 'type' => 'warning', 'message' => "Erro ao cadastrar o produto!" ];
            } else {

                $precos = [];
                $lotes = [];

                // // Armazena os preços
                for ($i=0; $i < count($post['id_estado']); $i++) {

                    if ( $post['id_estado'][$i] == 30 ) {

                        $precos[] = [
                            'codigo' => $post['codigo_produto'],
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'id_estado' => null,
                            'preco_unitario' => dbNumberFormat($post['preco'][$i]),
                        ];
                    } elseif ( $post['id_estado'][$i] != 0 ) {

                        $precos[] = [
                            'codigo' => $post['codigo_produto'],
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'id_estado' => $post['id_estado'][$i],
                            'preco_unitario' => dbNumberFormat($post['preco'][$i]),
                        ];
                    }
                }

                // // Armazena os lotes
                for ($i=0; $i < count($post['lote']); $i++) {

                    if ( !empty($post['lote'][$i]) ) {

                        $lotes[] = [
                            'codigo' => $post['codigo_produto'],
                            'lote' => $post['lote'][$i],
                            'local' => $post['local'][$i],
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'estoque' => $post['estoque'][$i],
                            'validade' => dbDateFormat($post['validade'][$i]),
                        ];
                    }
                }

                $this->db->trans_begin();

                $this->db->insert_batch('produtos_preco', $precos);
                $this->db->insert_batch('produtos_lote', $lotes);

                if ($this->db->trans_status() !== false) {
                    $this->db->trans_commit();

                    $output = [ 'type' => 'success', 'message' => "Produto cadastrado com sucesso!", 'route' => $this->route ];
                } else {

                    $this->db->trans_rollback();

                    $output = [ 'type' => 'warning', 'message' => "Erro ao cadastrar o produto!"];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função para criar e atualizar preço de produto
     *
     * @return  json
     */
    public function savePrice($codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $estado = ($post['id_estado'] == 30) ? null : $post['id_estado'];

            $preco = [
                'codigo' => $codigo,
                'id_fornecedor' => $this->session->id_fornecedor,
                'id_estado' => $estado,
                'preco_unitario' => dbNumberFormat($post['preco']),
            ];

            if ($this->db->insert('produtos_preco', $preco)) {
                $output = [ 'type' => 'success', 'message' => "Preço cadastrado com sucesso!", 'route' => $this->route ];
            } else {
                $output = [ 'type' => 'warning', 'message' => "Erro ao cadastrar preço!"];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função para criar lote de produto
     *
     * @return  json
     */
    public function saveLote($codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where('lote', $post['lote']);

            if ( $this->db->get('produtos_lote')->num_rows() < 1 ) {

                $lotes = [
                    'codigo' => $codigo,
                    'lote' => $post['lote'],
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'local' => $post['local'],
                    'estoque' => $post['estoque'],
                    'validade' => dbDateFormat($post['validade']),
                ];

                if ($this->db->insert('produtos_lote', $lotes)) {
                    $output = [ 'type' => 'success', 'message' => "Estoque cadastrado com sucesso!", 'route' => $this->route ];
                } else {
                    $output = [ 'type' => 'warning', 'message' => "Erro ao cadastrar estoque!"];
                }
            } else {
                $output = [ 'type' => 'warning', 'message' => "Este lote já possui registro!" ];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função para atualizar lote de produto
     *
     * @param - int codigo
     * @return  json
     */
    public function updateLote($codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $id_fornecedor = $this->session->id_fornecedor;

            $data = [
                'estoque' => $post['estoque'],
                'local' => $post['local'],
            ];

            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('lote', $post['lote']);

            if ($this->db->update('produtos_lote', $data)) {
                $output = [ 'type' => 'success', 'message' => "Lote atualizado com sucesso!", 'route' => $this->route ];
            } else {
                $output = [ 'type' => 'warning', 'message' => "Erro ao atualizar lote!"];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Função para deletar lote de produto
     *
     * @param - int codigo
     * @return  json
     */
    public function deleteLote($codigo)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
            $id_fornecedor = $this->session->id_fornecedor;

            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('lote', $post['lote']);

            if ($this->db->delete('produtos_lote')) {
                $output = [ 'type' => 'success', 'message' => "Lote excluído com sucesso!" ];
            } else {
                $output = [ 'type' => 'warning', 'message' => "Erro ao excluir lote!"];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function to_select2_produtos()
    {
        $page = $this->input->get('page');
        $length = 50;
        $data = [
            "start" => (($page - 1) * 50),
            "length" => $length
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($this->select2->exec(
            array_merge($this->input->get(), $data),
            "produtos_anvisa",
            [
                ['db' => 'id', 'dt' => 'id'],
                ['db' => 'ean1', 'dt' => 'ean1'],
                ['db' => 'registro', 'dt' => 'registro'],
                ['db' => 'produto', 'dt' => 'produto'],
                ['db' => 'laboratorio', 'dt' => 'laboratorio'],
                ['db' => 'apresentacao', 'dt' => 'apresentacao'],
                ['db' => 'produto', 'dt' => 'descricao', 'formatter' => function($value, $row) {
                    return "{$row['produto']} - {$row['apresentacao']} ";
                }]
            ],
            NULL
        )));
    }

    /**
     * Abre modal de preço ou lote
     *
     * @param   int codigo
     * @param   int tipoCadastro do form (cadastro ou update)
     * @return  json
     */
    public function open_modal($codigo, $tipoCadastro = null)
    {
        $data = [];

        $get = $this->input->get()['data'];

        $type = ($get['type'] == 1) ? 'savePrice' : 'saveLote';
        $title = ($get['type'] == 1) ? 'Preço' : 'Lote';
        $modal = ($get['type'] == 1) ? 'modal_preco' : 'modal_lote';

        $data['form_action'] = "{$this->route}/{$type}/{$codigo}";
        $data['title'] = "Novo {$title}";
        $data['estados'] = ($get['type'] == 1) ? $this->estados->todosEstados()->result_array() : null;

        if (isset($tipoCadastro)) {

            $data['title'] = "Atualizar {$title}";
            $data['form_action'] = "{$this->route}/updateLote/{$codigo}";
            if ( $get['type'] == 1 ) {
                $data['dados'] = $this->m_estoque->getPreco($codigo, $get['param'], $this->session->id_fornecedor);
            } else {
                $data['dados'] = $this->m_estoque->getLote($codigo, $get['param'], $this->session->id_fornecedor);
            }
        }

        $this->load->view("{$this->views}{$modal}", $data);
    }
}

