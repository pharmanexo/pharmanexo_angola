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
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_estoque');
        $this->load->model('m_estados', 'estados');
        $this->load->model('m_catalogo', 'catalogo');
        $this->load->model('m_fornecedor', 'fornecedor');

        $this->route = base_url("/fornecedor/estoque/produtos/");
        $this->views = "fornecedor/produtos/";

        $this->oncoprod = explode(',', ONCOPROD);
    }

    public function import()
    {
        $dados = [];

        $file = fopen('planilha_dados.csv', 'r');

        while (($line = fgetcsv($file, 30000, ',')) !== false) {
            $dados[] = [
                "substancia" => $line[0],
                "laboratorio" => $line[1],
                "codigo_ggrem" => $line[2],
                "registro" => $line[3],
                "ean1" => $line[4],
                "ean2" => $line[5],
                "produto" => $line[6],
                "apresentacao" => $line[7],
                "pf_sem_impostos" => !empty($line[8]) ? number_format(floatval($line[8]), 2, ",", ".") : 0.00,
                "pf0" => !empty($line[9]) ? number_format(floatval($line[9]), 2, ",", ".") : 0.00,
                "pf12" => !empty($line[10]) ? number_format(floatval($line[10]), 2, ",", ".") : 0.00,
                "pf17" => !empty($line[11]) ? number_format(floatval($line[11]), 2, ",", ".") : 0.00,
                "pf17alc" => !empty($line[12]) ? number_format(floatval($line[12]), 2, ",", ".") : 0.00,
                "pf175" => !empty($line[13]) ? number_format(floatval($line[13]), 2, ",", ".") : 0.00,
                "pf175alc" => !empty($line[14]) ? number_format(floatval($line[14]), 2, ",", ".") : 0.00,
                "pf18" => !empty($line[15]) ? number_format(floatval($line[15]), 2, ",", ".") : 0.00,
                "pf18alc" => !empty($line[16]) ? number_format(floatval($line[16]), 2, ",", ".") : 0.00,
                "pf20" => !empty($line[17]) ? number_format(floatval($line[17]), 2, ",", ".") : 0.00,
                "pmc0" => !empty($line[18]) ? number_format(floatval($line[18]), 2, ",", ".") : 0.00,
                "pmc12" => !empty($line[19]) ? number_format(floatval($line[19]), 2, ",", ".") : 0.00,
                "pmc17" => !empty($line[20]) ? number_format(floatval($line[20]), 2, ",", ".") : 0.00,
                "pmc17alc" => !empty($line[21]) ? number_format(floatval($line[21]), 2, ",", ".") : 0.00,
                "pmc175" => !empty($line[22]) ? number_format(floatval($line[22]), 2, ",", ".") : 0.00,
                "pmc175alc" => !empty($line[23]) ? number_format(floatval($line[23]), 2, ",", ".") : 0.00,
                "pmc18" => !empty($line[24]) ? number_format(floatval($line[24]), 2, ",", ".") : 0.00,
                "pmc18alc" => !empty($line[25]) ? number_format(floatval($line[25]), 2, ",", ".") : 0.00,
                "pmc20" => !empty($line[26]) ? number_format(floatval($line[26]), 2, ",", ".") : 0.00,
            ];
        }
        fclose($file);

        $this->db->insert_batch('produtos_anvisa', $dados);
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

            $post = $this->input->post();

            $update = [
                'quantidade_unidade' => $post['qtd_unidade']
            ];

            $this->db->where('codigo', $codigo);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);

            if ( $this->db->update('produtos_catalogo', $update) ) {

                $warning = ['type' => 'success', 'message' => 'Produto atualizado com sucesso!'];

            } else {

                $warning = ['type' => 'warning', 'message' => 'Erro ao atualizar produto!'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        } else {
            $this->form($codigo);
        }
    }

    public function delete()
    {
    }

    public function block_product($codigo = null, $ativar = 0)
    {
        if (isset($codigo)) {

            $status = ($ativar == 1) ? 0 : 1;
            $ativo = ($ativar == 1) ? 1 : 0;
            $message = ($ativar == 1) ? 'ativado' : 'inativado';

            if ( in_array($this->session->id_fornecedor, $this->oncoprod) ) {

                $fornecedores = implode(',', $this->oncoprod);

                $this->db->where('codigo', $codigo);
                $this->db->where_in('id_fornecedor in {$fornecedores}');
                $produtos_catalogo = $this->db->update('produtos_catalogo', ['bloqueado' => $status, 'ativo' => $ativo]);


                if ($produtos_catalogo) {
                    $warning = ["type" => "success", "message" => "Produto {$message} com sucesso"];
                } else {
                    $warning = ["type" => "warning", "message" => "Erro ao exceutar, tente novamente"];
                }
            } else {

                $this->db->where('codigo', $codigo);
                $this->db->where('id_fornecedor', $this->session->id_fornecedor);
                $produtos_catalogo = $this->db->update('produtos_catalogo', ['bloqueado' => $status, 'ativo' => $ativo]);

                if ($produtos_catalogo) {
                    $warning = ["type" => "success", "message" => "Produto {$message} com sucesso"];
                } else {
                    $warning = ["type" => "warning", "message" => "Erro ao exceutar, tente novamente"];
                }
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function not_found($codigo = null, $ativar = 0)
    {
        if (isset($codigo)) {

            $status = ($ativar == 1) ? 0 : 1;
            $message = ($ativar == 1) ? 'ativado' : 'inativado';
            $query = "UPDATE produtos_catalogo SET ativo = {$ativar}, ocultar_de_para = {$status} WHERE codigo = {$codigo} AND id_fornecedor = {$this->session->id_fornecedor}";


            $query = $this->db->query($query);

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

        $fornecedor = $this->fornecedor->findByID($this->session->id_fornecedor);

        if ( $fornecedor['permitir_cadastro_prod'] == 1 ) {

            $buttons = [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_produtos",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [

                    'type'  => 'a',
                    'id'    => 'btnInsert',
                    'url'   => "{$this->route}insert",
                    'class' => 'btn-primary',
                    'icone' => 'fa-plus',
                    'label' => 'Novo Registro'
                ]
            ];
        } else {

            $buttons = [
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_produtos",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
            ];
        }


        // TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title,]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => $buttons
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => []
        ]);

        $this->load->view("{$this->views}/main", $data);
    }

    private function form($codigo = null)
    {
        $page_title = "Cadastro de Produtos";

        $data['slct_marcas'] = "{$this->route}to_select2_marcas";
        $data['estados'] = $this->estados->todosEstados()->result_array();
        $data['form_action_produto'] = "{$this->route}saveProduct";
        $data['form_action_precosLotes'] = "{$this->route}savePriceLot";
        $view = "create";

        if (isset($codigo) && !empty($codigo)) {
            $page_title = "Edição de Produtos";

            $view = "update";
            $data['url_update'] = "{$this->route}update/{$codigo}";
            $data['url_export_preco'] = "{$this->route}exportar_preco/{$codigo}";
            $data['url_export_lote'] = "{$this->route}exportar_lote/{$codigo}";

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
                ['db' => 'descricao', 'dt' => 'descricao'],
                ['db' => 'ativo', 'dt' => 'ativo'],
                [
                    'db' => 'apresentacao',
                    'dt' => 'produto_descricao', "formatter" => function ($d, $r) {
                    if ( empty($r['descricao']) ) {
                        return $r['nome_comercial'] . " - " . $d;
                    } else {
                        return $r['nome_comercial'] . " - " . $r['descricao'];
                    }
                }],
            ],
            null,
            "id_fornecedor = {$this->session->userdata('id_fornecedor')}",
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
            'produtos_preco_max p',
            [
                ['db' => 'estados.uf', 'dt' => 'estado'],
                ['db' => 'p.id_estado', 'dt' => 'id_estado'],
                ['db' => 'p.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'pc.quantidade_unidade', 'dt' => 'quantidade_unidade'],
                ['db' => 'p.preco_unitario', 'dt' => 'preco_unitario', 'formatter' => function ($value, $row) {

                    if (in_array($row['id_fornecedor'], $this->oncoprod)) {

                        $preco = ($value / $row['quantidade_unidade']);
                    } else if (($row['id_fornecedor'] == 20) || ($row['id_fornecedor'] == 104)) {

                        $preco = ($value / $row['quantidade_unidade']);
                    } else {

                        $preco = $value;
                    }

                    return number_format($preco, 4, ',', '.');
                }],
                ['db' => 'p.data_criacao', 'dt' => 'data_criacao', 'formatter' => function ($value, $row) {

                    return date("d/m/Y H:i:s", strtotime($value));
                }],
            ],
            [
                ['estados', 'estados.id = p.id_estado', 'LEFT'],
                ['produtos_catalogo pc', 'pc.codigo = p.codigo AND pc.id_fornecedor = p.id_fornecedor'],
            ],
            "p.codigo = {$codigo} AND p.id_fornecedor = {$this->session->userdata('id_fornecedor')}"
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

            $data['codigo'] = $post['codigo'];
            $data['apresentacao'] = $post['apresentacao'];
            $data['descricao'] = $post['descricao'];
            $data['nome_comercial'] = $post['nome_comercial'];
            $data['id_fornecedor'] = $this->session->id_fornecedor;
            $data['ativo'] = 1;
            $data['aprovado'] = 1;
            $data['bloqueado'] = 0;
            $data['marca'] =  $post['marca'];
            $data['id_marca'] = $post['id_marca'];
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

    public function exportar_produtos()
    {
        $this->db->select(" 
            codigo,
            CASE WHEN descricao is null THEN CONCAT(nome_comercial, ' - ', apresentacao) ELSE CONCAT(nome_comercial, ' - ', descricao) END  AS descricao,
            marca");
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
                'ativo' => '',
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

    public function exportar_preco($codigo)
    {
        $this->db->select(" 
            CASE WHEN vpp.id_estado is null THEN 'Todos os Estados' ELSE CONCAT(e.uf, '-', e.descricao) END AS estado,
            FORMAT(vpp.preco_unitario, 4 , 'de_DE') AS preco, 
            DATE_FORMAT(vpp.data_criacao, '%d/%m/%Y') AS validade");
        $this->db->from("vw_produtos_precos vpp");
        $this->db->join("estados e", "e.id = vpp.id_estado", 'LEFT');
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
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

    public function exportar_lote($codigo)
    {
        $this->db->select("lote, local, estoque, DATE_FORMAT(validade, '%d/%m/%Y') AS validade");
        $this->db->from("produtos_lote ");
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
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

