<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendas_diferenciadas extends MY_Controller
{
    private $route;
    private $views;
    private $oncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url('fornecedor/vendas_diferenciadas');
        $this->views = 'fornecedor/vendas_diferenciadas';

        $this->load->model('m_venda_diferenciada', 'venda_diferenciada');
        $this->load->model('m_compradores', 'comprador');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_produto', 'produto');
        $this->load->model('m_estados', 'estado');
        $this->load->model('m_estoque', 'estoque');
        $this->oncoprod = explode(',', ONCOPROD);
    }

    /**
     * exibe a view fornecedor/vendas_diferenciadas/main.php
     *
     * @return view
     */
    public function index()
    {
        $page_title = "Vendas Diferenciadas";

        $data['to_datatable_estado'] = "{$this->route}/to_datatable_estado/";
        $data['to_datatable_cnpj'] = "{$this->route}/to_datatable_cnpj/";
        $data['url_delete'] = "{$this->route}/delete/";
        $data['url_delete_multiple'] = "{$this->route}/delete_multiple/";
        $data['url_update'] = "{$this->route}/openUpdateModal";
        $data['header'] = $this->template->header(['title' => 'Vendas Diferenciadas']);
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

        unset($_SESSION['redirect_super']); // Remove a session de redirecionamento de super_promocoes

        $data['scripts'] = $this->template->scripts();

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * exibe a view fornecedor/vendas_diferenciadas/mainProducts.php
     *
     * @return view
     */
    public function selecionarProdutos()
    {

        $page_title = 'Passo 1 - Selecione Produtos';

        # Usuarios do marketplace não veem a aba de compradores
        if (isset($this->session->id_tipo_venda) && $this->session->id_tipo_venda == 1) {

            $data['subtitle'] = "Passo 2 - Selecione estados";
            $data['labelSubtitle'] = "Estados";
        } else {

            $data['subtitle'] = "Passo 2 - Selecione estados ou compradores(CNPJ)";
            $data['labelSubtitle'] = "Estados ou Compradores (CNPJ)";
        }

        $data['header'] = $this->template->header([
            'title' => 'Vendas Diferenciadas',
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.min.css',
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
                    'form' => 'formVendasDiferenciadas',
                    'class' => 'btn-primary',
                    'icone' => 'fa-arrow-right',
                    'label' => 'Avançar configuração'
                ]
            ]
        ]);

        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.min.js',
                THIRD_PARTY . 'plugins/dataTables.select.min.js'
            ]
        ]);

        $data['form_action'] = "{$this->route}/validaProdutos";
        $data['modal_elementos'] = "{$this->route}/openModal";
        $data['datatable_produtos'] = "{$this->route}/to_datatables_produtos";

        $this->load->view("{$this->views}/mainProducts", $data);
    }

    /**
     * Função que faz a validação da seleção de produtos e estados/Cnpjs
     *
     * @return função produtos()
     */
    public function validaProdutos()
    {
        $postData = $this->input->post();

        $this->form_validation->set_rules('produtos', 'Produtos', 'required');
        $this->form_validation->set_rules('selecionados', 'Estados/Clientes', 'required');

        if ($this->form_validation->run() === FALSE) {

            $errors = "";

            foreach ($postData as $key => $value) {

                $errors .= form_error($key, '<p>', '</p>');
            }

            $this->session->set_flashdata('warning', ['type' => 'warning', 'message' => "{$errors}"]);
            redirect("{$this->route}/selecionarProdutos");
        } else {

            $selecionados = explode(',', $this->input->post('selecionados'));

            $produtos = explode(',', $this->input->post('produtos'));

            $produtosSelecionados = [];

            foreach ($produtos as $p) {

                $produto = $this->db->select('*')
                    ->where('id_fornecedor', $this->session->userdata('id_fornecedor'))
                    ->where('codigo', $p)
                    ->group_by('codigo')
                    ->get('produtos_catalogo')
                    ->row_array();

                $produto['estoque_uf'] = $this->estoque->allStock($p, $this->session->id_fornecedor);

                $estado = $this->estado->find("*", "id = '{$this->session->id_estado}'", true)['id'];

                # Preco
                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $p,
                    'id_estado' => $this->session->id_estado
                ]);

                $produto['preco_unidade'] = $preco_unit;

                array_push($produtosSelecionados, $produto);

                if ($this->input->post('selectElements') == 'ESTADOS') {

                    $data['estados'] = implode(',', $selecionados);
                } else {

                    $data['clientes'] = implode(',', $selecionados);
                }
            }

            if (isset($produtosSelecionados) && !empty($produtosSelecionados)) {
                $data['produtos'] = $produtosSelecionados;
            }

            if (empty($data['produtos'])) {
                $this->session->set_userdata('warning', ['type' => 'warning', 'message' => "Não existe produto para este estado"]);
                redirect("$this->route/selecionarProdutos");
            }

            # Todos os produtos selecionados em um CARD só
            if (isset($postData['all']) && $postData['all'] == 1) {
                $data['all'] = 1;
                $data['produtos'] = implode(',', array_column($data['produtos'], 'codigo'));
            }

            $this->novo($data);
        }
    }

    /**
     * Exibe a tela de cadastro de uma nova venda diferenciada
     *
     * @return view
     */
    public function novo($dados)
    {
        $page_title = "Passo 3 - Configuração da oferta";

        $data['header'] = $this->template->header([
            'title' => 'Vendas Diferenciadas',
            'styles' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/css/bootstrap-duallistbox.min.css',
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
                    'url' => "{$this->route}/selecionarProdutos",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'submit',
                    'id' => 'btnAdicionar',
                    'form' => 'formProdutos',
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Salvar Registro'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
                THIRD_PARTY . 'plugins/bootstrap-duallistbox/js/jquery.bootstrap-duallistbox.min.js',
                THIRD_PARTY . 'plugins/dataTables.select.min.js'
            ]
        ]);
        $data['dados'] = $dados;
        $data['form_action'] = "{$this->route}/save";

        if ($this->session->has_userdata('redirect_super')) {
            $data['options'] = ['2' => 'Automático'];
        } else {

            $d = $this->fornecedor->findById($this->session->id_fornecedor);

            if (isset($d) && $d['id_tipo_venda'] == 4) {
                $data['options'] = ['4' => 'Distribuidor x Distribuidor'];
            } else {

                $data['options'] = [
                    '0' => 'Todos os tipos',
                    '1' => 'Manual',
                    '2' => 'Automático',
                    '3' => 'Manual e Automático',
                    '4' => 'Distribuidor x Distribuidor',
                    '5' => 'Distribuidor e Manual',
                    '6' => 'Distribuidor e Automático'
                ];
            }
        }

        $this->load->view("{$this->views}/form", $data);
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
            'vendas_diferenciadas vd',
            [
                ['db' => 'vd.id', 'dt' => 'id'],
                ['db' => 'vd.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'pc.nome_comercial', 'dt' => 'produto_descricao', 'formatter' => function ($value, $row) {

                    return "<small>{$value}</small>";
                }],
                ['db' => 'vd.codigo', 'dt' => 'codigo'],
                ['db' => 'e.uf', 'dt' => 'uf'],
                ['db' => 'vd.dias', 'dt' => 'dias', 'formatter' => function ($d) {
                    return (empty($d) || is_null($d)) ? 'X' : $d;
                }],
                [
                    'db' => 'vd.id_cliente',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $this->session->id_estado
                        ]);

                        $preco = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                [
                    'db' => 'e.descricao',
                    'dt' => 'descricao',
                    'formatter' => function ($value, $row) {
                        return "{$row['uf']} - {$value}";
                    }
                ],
                [
                    'db' => 'vd.regra_venda',
                    'dt' => 'regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
                    }
                ]
            ],
            [
                ['estados e', 'e.id = vd.id_estado'],
                ['produtos_catalogo pc', 'pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor', 'LEFT']
            ],
            "vd.promocao = 0 
            AND vd.id_estado is not null 
            AND vd.regra_venda != 2
            AND vd.id_fornecedor = {$this->session->userdata('id_fornecedor')}"
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
            'vendas_diferenciadas vd',
            [
                ['db' => 'vd.id', 'dt' => 'id'],
                ['db' => 'vd.desconto_percentual', 'dt' => 'desconto_percentual'],
                ['db' => 'pc.nome_comercial', 'dt' => 'produto_descricao', 'formatter' => function ($value, $row) {

                    return "<small>{$value}</small>";
                }],
                ['db' => 'vd.codigo', 'dt' => 'codigo'],
                ['db' => 'c.cnpj', 'dt' => 'cnpj'],
                ['db' => 'vd.dias', 'dt' => 'dias', 'formatter' => function ($d) {
                    return (empty($d) || is_null($d)) ? 'X' : $d;
                }],
                [
                    'db' => 'vd.id_cliente',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {

                        $preco_unit = $this->price->getPrice([
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'codigo' => $row['codigo'],
                            'id_estado' => $this->session->id_estado
                        ]);

                        $preco = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));
                        return number_format($preco, 4, ',', '.');
                    }
                ],
                [
                    'db' => "c.razao_social",
                    'dt' => 'razao_social',
                    'formatter' => function ($value, $row) {
                        return "<small>{$value}<br><strong>CNPJ: </strong>{$row['cnpj']}</small>";
                    }
                ],
                [
                    'db' => 'vd.regra_venda',
                    'dt' => 'regra_venda',
                    'formatter' => function ($value, $row) {
                        return status_regra_venda($value);
                    }
                ]
            ],
            [
                ['compradores c', 'c.id = vd.id_cliente', 'LEFT'],
                ['produtos_catalogo pc', 'pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor', 'LEFT']

            ],
            "vd.promocao = 0 
            AND vd.regra_venda != 2
            AND vd.id_cliente is not null 
            AND vd.id_fornecedor = {$this->session->userdata('id_fornecedor')}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem os dados para o datatable de produtos
     *
     * @return json
     */
    public function to_datatables_produtos()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'produtos_catalogo',
            [
                ['db' => 'codigo', 'dt' => 'codigo'],
                ['db' => 'id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'apresentacao', 'dt' => 'produto_descricao', "formatter" => function ($value, $row) {

                    return $row['nome_comercial'] . " - " . $value;
                }],
                ['db' => 'marca', 'dt' => 'marca'],
                ['db' => 'bloqueado', 'dt' => 'bloqueado'],
            ],
            null,
            "id_fornecedor = {$this->session->userdata('id_fornecedor')}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * Salva uma venda diferenciada pra cada produto selecionado
     *
     * @return json
     */
    public function save()
    {
        $postData = $this->input->post();

        $this->db->trans_begin();

        $dataNovo = [];
        $dataAtualiza = [];

        # Todos os produtos
        if (isset($postData['all'])) {

            $this->db->select('*');
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $this->db->where("codigo in ({$postData['produtos']})");
            $this->db->group_by('codigo');
            $produtos = $this->db->get('produtos_catalogo')->result_array();

            $prods = [];
            foreach ($produtos as $produto) {

                $prods[] = [
                    "codigo" => $produto['codigo'],
                    "id_estado" => '',
                    "regra_venda" => $postData['regra_venda'],
                    "desconto_percentual" => $postData['desconto_percentual'],
                    "dias" => $produto['dias'],
                ];
            }

            $postData['produtos'] = $prods;
        }

        if (isset($postData['estados']) && !empty($postData['estados'])) {

            foreach ($postData['produtos'] as $v) {
                ;

                // Percorre a lista de estados
                foreach (explode(',', $postData['estados']) as $id_estado) {

                    // Verifica se existe registro com o codigo e id estado
                    $verifica = $this->db->select('*')
                        ->where('id_fornecedor', $this->session->userdata('id_fornecedor'))
                        ->where('id_estado', $id_estado)
                        ->where('codigo', $v['codigo'])
                        ->get('vendas_diferenciadas')
                        ->row_array();

                    // Se existe armazena no array de atualizações
                    if (!empty($verifica)) {

                        // Armazena as colunas de atualização junto com o ID já existente
                        $dataAtualiza[] = [
                            'id' => $verifica['id'],
                            'desconto_percentual' => dbNumberFormat($v['desconto_percentual']),
                            'regra_venda' => $v['regra_venda']
                        ];
                    } // Se não existir armazena no array de novos
                    else {

                        $dataNovo[] = [
                            'id_estado' => $id_estado,
                            'id_fornecedor' => $this->session->userdata('id_fornecedor'),
                            'desconto_percentual' => dbNumberFormat($v['desconto_percentual']),
                            'codigo' => $v['codigo'],
                            'regra_venda' => $v['regra_venda'],
                            'dias' => $v['dias']
                        ];
                    }
                }
            }
        } else {

            foreach ($postData['produtos'] as $v) {

                // Percorre a lista de cnpjs
                foreach (explode(',', $postData['clientes']) as $id_cliente) {

                    // Verifica se existe registro com o id produto de cada cliente
                    $verifica = $this->db->select('*')
                        ->where('id_fornecedor', $this->session->userdata('id_fornecedor'))
                        ->where('id_cliente', $id_cliente)
                        ->where('codigo', $v['codigo'])
                        ->get('vendas_diferenciadas')
                        ->row_array();

                    // Se existe armazena no array de atualizçaão
                    if (!empty($verifica)) {

                        $dataAtualiza[] = [
                            'id' => $verifica['id'],
                            'desconto_percentual' => dbNumberFormat($v['desconto_percentual']),
                            'regra_venda' => $v['regra_venda']
                        ];
                    } // Se não existir armazena no novo
                    else {

                        $dataNovo[] = [
                            'id_cliente' => $id_cliente,
                            'id_fornecedor' => $this->session->userdata('id_fornecedor'),
                            'desconto_percentual' => $v['desconto_percentual'],
                            'codigo' => $v['codigo'],
                            'regra_venda' => $v['regra_venda']
                        ];
                    }
                }
            }
        }

        if (!empty($dataNovo))
            $this->db->insert_batch('vendas_diferenciadas', $dataNovo);

        if (!empty($dataAtualiza))
            $this->db->update_batch('vendas_diferenciadas', $dataAtualiza, 'id');


        if ($this->db->trans_status() === true) {

            $this->db->trans_commit();

            $output = ["type" => "success", "message" => notify_create];
        } else {

            $this->db->trans_rollback();
            $output = ["type" => "warning", "message" => notify_failed];
        }

        if ($this->session->has_userdata('redirect_super')) {
            $output['redirect'] = base_url('fornecedor/super_promocoes');
        } else {
            $output['redirect'] = 0;
        }

        unset($_SESSION['redirect_super']);

        $output['route'] = $this->route;

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Atualiza uma venda diferenciada
     *
     * @return json
     */
    public function update($id)
    {
        $post = $this->input->post();

        $this->form_validation->set_rules('desconto_percentual', 'Desconto', 'required');

        $venda = $this->venda_diferenciada->findById($id);

        if ($this->form_validation->run() === FALSE) {

            $errors = [];

            foreach ($post as $key => $value) {

                $errors[$key] = form_error($key, '', '');
            }

            $output = ['type' => 'warning', 'message' => array_filter($errors)];
        } else {

            if ($this->venda_diferenciada->update($post)) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /**
     * Deleta as vendas diferenciadas selecionadas
     *
     * @return json
     */
    public function delete_multiple()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            if (!isset($post['el'])) {
                $newdata = [
                    'type' => 'warning',
                    'message' => 'Nenhum produto selecionado'
                ];

                $this->output->set_content_type('application/json')->set_output(json_encode($newdata));
                return;
            }

            foreach ($post['el'] as $item) {
                $this->venda_diferenciada->excluir($item);
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
     * Exibe o modal de selecionar estados ou compradores
     *
     * @return view
     */
    public function openModal($option)
    {
        switch ($option) {
            case 'ESTADOS':
                $data['title'] = 'Selecione os Estados';
                $data['options'] = $this->estado->find("id, CONCAT(uf, ' - ', descricao) AS value");
                break;

            case 'CLIENTES':
                $data['title'] = 'Selecione os Compradores';
                $data['options'] = $this->comprador->find("id, CONCAT(cnpj, ' - ', razao_social) AS value");
                break;
        }

        $this->load->view("{$this->views}/modalSelect", $data);
    }

    /**
     * Exibe o modal de atualização da venda diferenciada
     *
     * @return view
     */
    public function openUpdateModal($id)
    {
        $data = [
            'dados' => $this->venda_diferenciada->getById($id),
            'title' => "Vendas Diferenciadas",
            'form_action' => "{$this->route}/update/{$id}"
        ];
        $this->db->select("SUM(estoque) as total")
            ->where('codigo', $data['dados']['codigo'])
            ->where('id_fornecedor', $this->session->userdata('id_fornecedor'));

        $estoque = $this->db->get('produtos_lote')->row_array()['total'];
        $data['dados']['estoque_uf'] = $estoque;

        $this->load->view("{$this->views}/modalUpdate", $data);
    }

    /**
     * Cria um arquivo excel com todos os registros do datatables de vendas diferenciadas
     *
     * @return file
     */
    public function exportar()
    {
        $this->db->select("
            vd.codigo, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
            CONCAT(e.uf, '-', e.descricao) AS estado,
            (CASE 
                WHEN vd.regra_venda = 0 THEN 'Todos'
                WHEN vd.regra_venda = 1 THEN 'Manual'
                WHEN vd.regra_venda = 2 THEN 'Automático'
                WHEN vd.regra_venda = 3 THEN 'Manual e Automático'
                WHEN vd.regra_venda = 4 THEN 'Distribuidor x Distribuidor'
                WHEN vd.regra_venda = 5 THEN 'Distribuidor x Manual'
                WHEN vd.regra_venda = 6 THEN ' Distribuidor x Automático' END) regra_venda");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join('estados e', "e.id = vd.id_estado");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo AND pc.id_fornecedor = vd.id_fornecedor", "left");
        $this->db->where('pc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.promocao', 0);
        $this->db->where('vd.id_cliente is null');
        $this->db->where('vd.regra_venda != 2');
        $this->db->order_by("produto ASC");

        $query_estados = $this->db->get()->result_array();

        $this->db->select("
            vd.codigo, 
            vd.desconto_percentual, 
            pc.nome_comercial AS produto, 
            CONCAT(c.cnpj, '-', c.razao_social), 'c.razao_social' AS comprador,
            (CASE 
                WHEN vd.regra_venda = 0 THEN 'Todos'
                WHEN vd.regra_venda = 1 THEN 'Manual'
                WHEN vd.regra_venda = 2 THEN 'Automático'
                WHEN vd.regra_venda = 3 THEN 'Manual e Automático'
                WHEN vd.regra_venda = 4 THEN 'Distribuidor x Distribuidor'
                WHEN vd.regra_venda = 5 THEN 'Distribuidor x Manual'
                WHEN vd.regra_venda = 6 THEN ' Distribuidor x Automático' END) regra_venda");
        $this->db->from("vendas_diferenciadas vd");
        $this->db->join('compradores c', "c.id = vd.id_cliente", "left");
        $this->db->join('produtos_catalogo pc', "pc.codigo = vd.codigo", "left");
        $this->db->where('pc.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('vd.promocao', 0);
        $this->db->where('vd.id_estado is null');
        $this->db->where('vd.regra_venda != 2');
        $this->db->order_by("produto ASC");

        $query_clientes = $this->db->get()->result_array();

        if (count($query_estados) < 1) {
            $query_estados[] = [
                'codigo' => '',
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'estado' => '',
                'regra_venda' => ''
            ];
        } else {

            $data = [];

            foreach ($query_estados as $kk => $row) {

                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $row['codigo'],
                    'id_estado' => $this->session->id_estado
                ]);

                $preco_desc = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));

                $data[] = [
                    'codigo' => $row['codigo'],
                    'desconto_percentual' => $row['desconto_percentual'],
                    'produto' => $row['produto'],
                    'preco' => number_format($preco_unit, 4, ',', '.'),
                    'preco_desconto' => number_format($preco_desc, 4, ',', '.'),
                    'estado' => $row['estado'],
                    'regra_venda' => $row['regra_venda']
                ];
            }

            $query_estados = $data;
        }

        if (count($query_clientes) < 1) {
            $query_clientes[] = [
                'codigo' => '',
                'desconto_percentual' => '',
                'produto' => '',
                'preco' => '',
                'preco_desconto' => '',
                'comprador' => '',
                'regra_venda' => ''
            ];
        } else {

            $data = [];

            foreach ($query_clientes as $kk => $row) {

                $preco_unit = $this->price->getPrice([
                    'id_fornecedor' => $this->session->id_fornecedor,
                    'codigo' => $row['codigo'],
                    'id_estado' => $this->session->id_estado
                ]);

                $preco_desc = $preco_unit - ($preco_unit * ($row['desconto_percentual'] / 100));

                $data[] = [
                    'codigo' => $row['codigo'],
                    'desconto_percentual' => $row['desconto_percentual'],
                    'produto' => $row['produto'],
                    'preco' => number_format($preco_unit, 4, ',', '.'),
                    'preco_desconto' => number_format($preco_desc, 4, ',', '.'),
                    'comprador' => $row['comprador'],
                    'regra_venda' => $row['regra_venda']
                ];
            }

            $query_clientes = $data;
        }

        $dados_page1 = ['dados' => $query_estados, 'titulo' => 'Estados'];
        $dados_page2 = ['dados' => $query_clientes, 'titulo' => 'Compradores'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page1, $dados_page2);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    /**
     * Cria um arquivo excel com todos os registros do datatables de produtos
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

        if (count($query) < 1) {

            $query[] = [
                'codigo' => '',
                'descricao' => '',
                'marca' => '',
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'Produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }
}

/* End of file: Vendas_diferenciadas.php */
