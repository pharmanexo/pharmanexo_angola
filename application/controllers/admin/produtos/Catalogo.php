<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Catalogo extends Admin_controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('admin/produtos/catalogo');
        $this->views = 'admin/produtos/catalogo';

        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_catalogo', 'catalogo');

        $this->oncoprod = explode(',', ONCOPROD);
    }

    /** Pagina inicial
     *
     * @return  view
     */
    public function index()
    {
        $page_title = "Catálogo de Produtos aprovados";

        $data = [
            'datasource'   => "{$this->route}/datatables/",
            'url_block' => "{$this->route}/block_product/",
            'url_update' => "{$this->route}/update/",
            'url_exportar' => "{$this->route}/exportar/",
            'fornecedores' => $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC'),
            'header'       => $this->template->header(['title' => $page_title]),
            'navbar'       => $this->template->navbar(),
            'sidebar'      => $this->template->sidebar(),
            'heading'      => $this->template->heading([
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
                    [

                        'type'  => 'a',
                        'id'    => 'btnInsert',
                        'url'   => "{$this->route}/insert",
                        'class' => 'btn-primary',
                        'icone' => 'fa-plus',
                        'label' => 'Novo Registro'
                    ]
                ]
            ]),
            'scripts'      => $this->template->scripts()
        ];

        $this->load->view("{$this->views}/main", $data);
    }

    public function insert()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();
        } else {
            
            $page_title = "Cadastro de Produtos";

            $data['slct_marcas'] = "{$this->route}/to_select2_marcas";
            $data['estados'] = $this->estado->getList();
            $data['fornecedores'] = $this->fornecedor->find("id, nome_fantasia", null, false, 'nome_fantasia ASC');

            $data['form_action_produto'] = "{$this->route}/saveProduct";
            $data['form_action_precosLotes'] = "{$this->route}/savePriceLot";

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

            $this->load->view("{$this->views}/create", $data);
        }
    }

    public function update($codigo, $id_fornecedor)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $update = [
                'quantidade_unidade' => $post['qtd_unidade']
            ];

            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $id_fornecedor);
           
            if ( $this->db->update('produtos_catalogo', $update) ) {

                $warning = ['type' => 'success', 'message' => 'Produto atualizado com sucesso!'];
                    
            } else {

                $warning = ['type' => 'warning', 'message' => 'Erro ao atualizar produto!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        } else {
                 
            $page_title = "Edição de Produtos";

            $data['url_update'] = "{$this->route}/update/{$codigo}/{$id_fornecedor}";
            $data['url_export_preco'] = "{$this->route}/exportar_preco/{$codigo}/{$id_fornecedor}";
            $data['url_export_lote'] = "{$this->route}/exportar_lote/{$codigo}/{$id_fornecedor}";

            $data['open_modal'] = "{$this->route}/open_modal/{$codigo}/{$id_fornecedor}";
            $data['url_delete'] = "{$this->route}/deleteLote/{$codigo}/{$id_fornecedor}";
            $data['dtbl_lotes'] = "{$this->route}/to_datatables_lotes/{$codigo}/{$id_fornecedor}";
            $data['dtbl_precos'] = "{$this->route}/to_datatables_precos/{$codigo}/{$id_fornecedor}";
            $data['produto'] = $this->catalogo->find("*", "codigo = {$codigo} and id_fornecedor = {$id_fornecedor}", true);


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

            $this->load->view("{$this->views}/update", $data);
        }
    }

    /** Dados do datatable
     *
     * @return  json
     */
    public function datatables($id_fornecedor)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo pc',
            [
                ['db' => 'pc.codigo', 'dt' => 'codigo'],
                ['db' => 'pc.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'pc.apresentacao', 'dt' => 'apresentacao'],
                ['db' => 'pc.descricao', 'dt' => 'descricao'],
                [
                    'db' => 'pc.nome_comercial',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                        if ( empty($r['apresentacao']) ) {

                            return $d . " - " . $r['descricao'];
                        }

                        return $d . " - " . $r['apresentacao'];
                }],
                ['db' => 'pc.marca', 'dt' => 'marca'],
                ['db' => 'pc.bloqueado', 'dt' => 'bloqueado'],
                ['db' => 'pc.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'f.razao_social', 'dt' => 'razao_social'],
                ['db' => 'f.id_estado', 'dt' => 'id_estado'],
                ['db' => 'pc.ativo', 'dt' => 'ativo'],
                ['db' => "CASE WHEN SUM(pl.estoque) is null THEN 0 ELSE SUM(pl.estoque) END", 'dt' => 'estoque'],
            ],
            [
                ['fornecedores f', 'f.id = pc.id_fornecedor', 'LEFT'],
                ['produtos_lote pl', 'pl.codigo = pc.codigo AND pl.id_fornecedor = pc.id_fornecedor', 'LEFT'],
            ],
            "pc.id_fornecedor = {$id_fornecedor}",
            "pc.codigo"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_lotes($codigo, $id_fornecedor)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
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
            "codigo = {$codigo} AND id_fornecedor = {$id_fornecedor}"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    public function to_datatables_precos($codigo, $id_fornecedor)
    {
        $r = $this->datatable->exec(
            $this->input->post(),
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
            "codigo = {$codigo} AND id_fornecedor = {$id_fornecedor}"
        );


        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /** Altera o status do produto
     *
     * @param   int  codigo do produto
     * @param   int  id do fornecedor
     * @param   int  ativar/desativar produto
     * @return  json
     */
    public function block_product($codigo, $id_fornecedor, $ativar = 0)
    {
         if (isset($codigo)) {

            $status = ($ativar == 1) ? 0 : 1;
            $ativo = ($ativar == 1) ? 1 : 0;
            $message = ($ativar == 1) ? 'ativado' : 'inativado';

            if ( in_array($id_fornecedor, $this->oncoprod) ) {

                $fornecedores = implode(',', $this->oncoprod);

                $this->db->where('codigo', $codigo);
                $this->db->where_in('id_fornecedor in {$fornecedores}');
                $produtos_catalogo = $this->db->update('produtos_catalogo', ['bloqueado' => $status, 'ativo' => $ativo]);


                if ($produtos_catalogo) {
                    $warning = ["type" => "success", "message" => "Produto {$message} com sucesso"];
                } else {

                    $warning = $this->notify->errorMessage();
                }
            } else {

                $this->db->where('codigo', $codigo);
                $this->db->where('id_fornecedor', $id_fornecedor);
                $produtos_catalogo = $this->db->update('produtos_catalogo', ['bloqueado' => $status, 'ativo' => $ativo]);

                if ($produtos_catalogo) {
                    $warning = ["type" => "success", "message" => "Produto {$message} com sucesso"];
                } else {

                    $warning = $this->notify->errorMessage();
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
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
            $data['nome_comercial'] = $post['nome_comercial'];
            $data['id_fornecedor'] = $post['id_fornecedor'];
            $data['ativo'] = 0;
            $data['aprovado'] = 0;
            $data['bloqueado'] = 0;
            $data['id_marca'] = $marca['id'];
            $data['ean'] = $post['ean'];
            $data['rms'] = $post['rms'];
            $data['quantidade_unidade'] = $post['quantidade_unidade'];
            $data['unidade'] = $post['unidade'];

            $this->db->where('id_fornecedor', $data['id_fornecedor']);
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
    public function savePriceLot($id_fornecedor)
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
                            'id_fornecedor' => $id_fornecedor,
                            'id_estado' => null,
                            'preco_unitario' => dbNumberFormat($post['preco'][$i]),
                        ];
                    } elseif ( $post['id_estado'][$i] != 0 ) {

                        $precos[] = [
                            'codigo' => $post['codigo_produto'],
                            'id_fornecedor' => $id_fornecedor,
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
                            'id_fornecedor' => $id_fornecedor,
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
    public function savePrice($codigo, $id_fornecedor)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $estado = ($post['id_estado'] == 30) ? null : $post['id_estado'];

            $preco = [
                'codigo' => $codigo,
                'id_fornecedor' => $id_fornecedor,
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
    public function saveLote($codigo, $id_fornecedor)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $id_fornecedor);
            $this->db->where('lote', $post['lote']);

            if ( $this->db->get('produtos_lote')->num_rows() < 1 ) {

                $lotes = [
                    'codigo' => $codigo,
                    'lote' => $post['lote'],
                    'id_fornecedor' => $id_fornecedor,
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
    public function updateLote($codigo, $id_fornecedor)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

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
    public function deleteLote($codigo, $id_fornecedor)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

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

    /**
     * Abre modal de preço ou lote
     *
     * @param   int codigo
     * @param   int ID do fornecedor
     * @param   int tipoCadastro do form (cadastro ou update)
     * @return  json
     */
    public function open_modal($codigo, $id_fornecedor, $tipoCadastro = null)
    {
        $data = [];

        $get = $this->input->get()['data'];

        $type = ($get['type'] == 1) ? 'savePrice' : 'saveLote';
        $title = ($get['type'] == 1) ? 'Preço' : 'Lote';
        $modal = ($get['type'] == 1) ? 'modal_preco' : 'modal_lote';

        $data['form_action'] = "{$this->route}/{$type}/{$codigo}/{$id_fornecedor}";
        $data['title'] = "Novo {$title}";
        $data['estados'] = ($get['type'] == 1) ? $this->estado->getList() : null;

        if (isset($tipoCadastro)) {

            $data['title'] = "Atualizar {$title}";
             $data['form_action'] = "{$this->route}/updateLote/{$codigo}/{$id_fornecedor}";
            if ( $get['type'] == 1 ) {
                $data['dados'] = $this->m_estoque->getPreco($codigo, $get['param'], $id_fornecedor);
            } else {
                $data['dados'] = $this->m_estoque->getLote($codigo, $get['param'], $id_fornecedor);
            }
        }

        $this->load->view("{$this->views}/{$modal}", $data);
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

    public function exportar($id_fornecedor = null)
    {
        if ( isset($id_fornecedor) ) {
                
            $this->db->select("
                pc.codigo,
                CASE WHEN pc.descricao is null THEN CONCAT(pc.nome_comercial, ' - ', pc.apresentacao) ELSE CONCAT(pc.nome_comercial, ' - ', pc.descricao) END AS descricao,
                pc.marca,
                CASE 
                    WHEN pc.bloqueado = 1 THEN 'inativo' ELSE 'ativo' END AS situacao");
            $this->db->from("produtos_catalogo pc");
            $this->db->join('fornecedores f', "f.id = pc.id_fornecedor", "left");
            $this->db->where("id_fornecedor", $id_fornecedor);
            $this->db->group_by("codigo");
            $this->db->order_by("codigo ASC");

            $query = $this->db->get()->result_array();
        } else {
            $query = [];
        }

        if ( count($query) < 1 ) {
            $query[] = [
                'codigo' => '',
                'descricao' => '',
                'marca' => '',
                'situacao' => '',
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

    public function exportar_preco($codigo, $id_fornecedor)
    {
        $this->db->select(" 
            CASE WHEN vpp.id_estado is null THEN 'Todos os Estados' ELSE CONCAT(e.uf, '-', e.descricao) END AS estado,
            FORMAT(vpp.preco_unitario, 4 , 'de_DE') AS preco, 
            DATE_FORMAT(vpp.data_criacao, '%d/%m/%Y') AS validade");
        $this->db->from("vw_produtos_precos vpp");
        $this->db->join("estados e", "e.id = vpp.id_estado", 'LEFT');
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('codigo', $codigo);
        $this->db->order_by('codigo');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'estado' => '',
                'preco' => '',
                'validade' => '',
            ];
        }

        $dados_page = ['dados' => $query , 'titulo' => 'precos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }
    }

    public function exportar_lote($codigo, $id_fornecedor)
    {
        $this->db->select("lote, local, estoque, DATE_FORMAT(validade, '%d/%m/%Y') AS validade");
        $this->db->from("produtos_lote ");
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('codigo', $codigo);
        $this->db->order_by('codigo');

        $query = $this->db->get()->result_array();

        if ( count($query) < 1 ) {
            $query[] = [
                'lote' => '',
                'local' => '',
                'estoque' => '',
                'validade' => '',
            ];
        }

        $dados_page = ['dados' => $query , 'titulo' => 'lotes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ( $exportar['status'] == false ) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }
    }
}

/* End of file: Catalogo.php */
