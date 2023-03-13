<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos extends MY_Controller
{
    private $route, $views;

    public function __construct()
    {
        parent::__construct();
        $this->route = base_url('fornecedor/representantes/pedidos');
        $this->views = "fornecedor/representantes/pedidos";

        $this->load->model('m_representante', 'rep');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_pedido_rep', 'pedido');
        $this->load->model('m_pedido_rep_prod', 'pedido_prod');
        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_compradores', 'cliente');
    }

    /**
     * Exibe a view fornecedor/representantes/pedidos/main.php
     *
     * @return  view
     */
    public function index()
    {
        $page_title = 'Pedidos Representantes';

        $data['datatables'] = "{$this->route}/datatables";
        $data['url_detalhes'] = "{$this->route}/detalhes/";
        $data['situacao'] = statusPedidoRepresentante();

        $data['header'] = $this->template->header([
            'title' => $page_title,
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
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);

        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a view com os dados do pedido selecionado
     *
     * @param - int id do pedido
     * @return  view
     */
    public function detalhes($id_pedido)
    {
        $page_title = 'Itens do Pedido';
        $view = 'details';

        $data['datatables'] = "{$this->route}/datatables_produtos/{$id_pedido}";
        $data['url_reject'] = "{$this->route}/reject_item/{$id_pedido}";

        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['scripts'] = $this->template->scripts();

        $pedido = $this->db
            ->select('p.*, c.razao_social, c.estado')
            ->from('pedidos_representantes p')
            ->join('compradores c', 'c.id = p.id_comprador')
            ->where('p.id', $id_pedido)
            ->get()
            ->row_array();

        $data['pedido'] = $pedido;

        $data['formas_pagamento'] = $this->db->get('formas_pagamento')->result_array();

        $buttons = [
            [
                'type' => 'a',
                'id' => 'btnBack',
                'url' => "{$this->route}",
                'class' => 'btn-secondary',
                'icone' => 'fa-arrow-left',
                'label' => 'Voltar'
            ],
            [
                'type' => 'button',
                'id' => 'btnExport',
                'url' => "{$this->route}/exportar_detalhes/{$id_pedido}",
                'class' => 'btn-primary',
                'icone' => 'fa-file-excel',
                'label' => 'Exportar Excel'
            ],
            [
                'type' => 'button',
                'id' => 'btnReject',
                'url' => "{$this->route}/reject/{$id_pedido}",
                'class' => 'btn-danger',
                'icone' => 'fa-ban',
                'label' => 'Rejeitar pedido'
            ],
            [
                'type' => 'a',
                'id' => 'btnApprove',
                'url' => "{$this->route}/approve/{$id_pedido}",
                'class' => 'btn-primary',
                'icone' => 'fa-thumbs-up',
                'label' => 'Aprovar pedido'
            ]
        ];

        if ($pedido['situacao'] == 2) {
            $pedido['produtos'] = $this->getProdutosPedidos($id_pedido);
            $data['pedido'] = $pedido;
            $view = 'aprovacao';
            $page_title = "Orçamento - Itens";
            $data['form_action'] = "{$this->route}/aprovarOrcamento/{$id_pedido}";

            $buttons = [
                [
                    'type' => 'a',
                    'id' => 'btnBack',
                    'url' => "{$this->route}",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Voltar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/exportar_detalhes/{$id_pedido}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnReject',
                    'url' => "{$this->route}/reject/{$id_pedido}",
                    'class' => 'btn-danger',
                    'icone' => 'fa-ban',
                    'label' => 'Rejeitar pedido'
                ],
                [
                    'type' => 'a',
                    'id' => 'btnApprove',
                    'url' => "{$this->route}/aprovarOrcamento/{$id_pedido}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-thumbs-up',
                    'label' => 'Aprovar pedido'
                ]
            ];
        }


        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => $buttons
        ]);

        $this->load->view("{$this->views}/{$view}", $data);
    }

    public function get_info($id_cliente)
    {

        $cliente = $this->compradores->findById($id_cliente);
        $estado = $this->estado->find("*", "uf = '{$cliente['estado']}'", true);

        #valor minimo
        $valor_minimo = $this->valor_minimo->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        if (empty($valor_minimo)) {
            $valor_minimo = $this->valor_minimo->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        }
        $valor_minimo = $valor_minimo['valor_minimo'];

        #prazo entrega
        $prazo_entrega = $this->prazo_entrega->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        if (empty($prazo_entrega)) {
            $prazo_entrega = $this->prazo_entrega->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        }

        #condição pagamento
        $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_cliente = {$cliente['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        if (empty($forma_pagamento)) {
            $forma_pagamento = $this->forma_pagamento_fornecedor->find("*", "id_estado = {$estado['id']} and id_fornecedor = {$this->session->id_fornecedor}", true);
        }

        $formapgto_desc = $this->forma_pagamento->findById($forma_pagamento['id_forma_pagamento']);

        $forma_pagamento = $forma_pagamento['id_forma_pagamento'];

        $output = [
            "cliente" => $cliente,
            "valor_minimo" => (isset($valor_minimo) and $valor_minimo > 0) ? number_format($valor_minimo, 2, ',', '.') : '0,00',
            "prazo_entrega" => (isset($prazo_entrega) and $prazo_entrega > 0) ? $prazo_entrega : '10',
            "condicao" => (isset($forma_pagamento) and $forma_pagamento > 0) ? $forma_pagamento : '1',
            "condicao_desc" => (isset($formapgto_desc) and !empty($formapgto_desc)) ? $formapgto_desc['descricao'] : '',
        ];

    }


    public function getProdutosPedidos($id_pedido)
    {
        $this->db->select('prp.*, pc.nome_comercial');
        $this->db->from('pedidos_representantes_produtos prp');
        $this->db->join('produtos_catalogo pc', "pc.codigo = prp.cd_produto_fornecedor and pc.id_fornecedor = {$this->session->id_fornecedor}");
        $this->db->where('prp.id_pedido', $id_pedido);
        $result = $this->db->get()->result_array();

        foreach ($result as $k => $item) {
            switch ($item['status']) {
                case 0:
                    $result[$k]['class'] = '';
                    break;
                case 1:
                    $result[$k]['class'] = 'table-success';
                    break;
                case 9:
                    $result[$k]['class'] = 'table-danger';
                    break;
            }

        }

        return $result;

    }

    /**
     * Obtem dados para o datatable de fornecedores/representantes/pedidos
     *
     * @return  json
     */
    public function datatables()
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes',
            [
                ['db' => 'pedidos_representantes.id', 'dt' => 'id'],
                ['db' => 'pedidos_representantes.prioridade', 'dt' => 'prioridade'],
                ['db' => 'pedidos_representantes.situacao', 'dt' => 'situacao'],
                ['db' => 'pedidos_representantes.id_representante', 'dt' => 'id_representante'],
                ['db' => 'pedidos_representantes.id_fornecedor', 'dt' => 'id_fornecedor'],
                ['db' => 'pedidos_representantes.id_comprador', 'dt' => 'id_comprador'],
                ['db' => 'pedidos_representantes.condicao_pagamento', 'dt' => 'condicao_pagamento'],
                ['db' => 'pedidos_representantes.prazo_entrega', 'dt' => 'prazo_entrega'],
                ['db' => 'pedidos_representantes.valor_minimo', 'dt' => 'valor_minimo'],
                ['db' => 'pedidos_representantes.situacao', 'dt' => 'situacao'],
                ['db' => 'pedidos_representantes.data_abertura', 'dt' => 'data_abertura'],
                ['db' => 'representantes.nome', 'dt' => 'representante'],
                ['db' => 'compradores.razao_social', 'dt' => 'comprador'],
                [
                    'db' => 'pedidos_representantes.situacao',
                    'dt' => 'status_situacao',
                    'formatter' => function ($value, $row) {
                        return statusPedidoRepresentante($value);
                    }
                ],
                [
                    'db' => 'pedidos_representantes.data_abertura',
                    'dt' => 'data',
                    'formatter' => function ($value, $row) {
                        return date('d/m/Y H:i:s', strtotime($value));
                    }
                ],
            ],
            [
                ['representantes', 'representantes.id = pedidos_representantes.id_representante', 'LEFT'],
                ['compradores', 'compradores.id = pedidos_representantes.id_comprador', 'LEFT'],
            ],
            "pedidos_representantes.id_fornecedor = {$this->session->id_fornecedor}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    /**
     * Obtem dados dos produtos do pedido para datatable
     *
     * @param - int id do pedido
     * @return  json
     */
    public function datatables_produtos($id_pedido)
    {
        $datatables = $this->datatable->exec(
            $this->input->post(),
            'pedidos_representantes_produtos',
            [
                ['db' => 'pedidos_representantes_produtos.id_pedido', 'dt' => 'id_pedido'],
                ['db' => 'pedidos_representantes_produtos.cd_produto_fornecedor', 'dt' => 'cd_produto_fornecedor'],
                ['db' => 'pedidos_representantes_produtos.quantidade_solicitada', 'dt' => 'quantidade_solicitada'],
                ['db' => 'produtos_catalogo.nome_comercial', 'dt' => 'nome_comercial'],
                ['db' => 'pedidos_representantes_produtos.motivo', 'dt' => 'motivo'],
                ['db' => 'pedidos_representantes_produtos.status', 'dt' => 'status'],
                [
                    'db' => 'pedidos_representantes_produtos.preco_unidade',
                    'dt' => 'preco_unidade',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
                [
                    'db' => 'pedidos_representantes_produtos.desconto',
                    'dt' => 'desconto',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
                [
                    'db' => 'pedidos_representantes_produtos.preco_desconto',
                    'dt' => 'preco_desconto',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
                [
                    'db' => 'pedidos_representantes_produtos.total',
                    'dt' => 'total',
                    'formatter' => function ($value, $row) {
                        return number_format($value, 4, ",", ".");
                    }
                ],
            ],
            [
                ['pedidos_representantes', 'pedidos_representantes.id = pedidos_representantes_produtos.id_pedido'],
                ['produtos_catalogo', 'produtos_catalogo.codigo = pedidos_representantes_produtos.cd_produto_fornecedor and pedidos_representantes.id_fornecedor = produtos_catalogo.id_fornecedor', 'LEFT'],
            ],
            "pedidos_representantes_produtos.id_pedido = {$id_pedido}"
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($datatables));
    }

    public function aprovarOrcamento()
    {
        $post = $this->input->post();

        $data['id_forma_pagamento'] = $post['id_forma_pagamento'];
        $data['prazo_entrega'] = $post['prazo_entrega'];
        $data['valor_minimo'] = $post['valor_minimo'];
        $data['situacao'] = 7;

        $this->db->where('id', $data['id_forma_pagamento']);
        $fp = $this->db->get('formas_pagamento')->row_array();

        if (!empty($fp)) {
            $data['condicao_pagamento'] = $fp['descricao'];
        }

        $this->db->where('id', $post['id']);
        $this->db->update('pedidos_representantes', $data);

        if (!empty($post)) {
            foreach ($post['produtos'] as $k => $produto) {

                $prod = [
                    'preco_unidade' => dbNumberFormat($produto['preco_unidade']),
                    'desconto' => dbNumberFormat($produto['desconto']),
                    'preco_desconto' => dbNumberFormat($produto['preco_desconto']),
                    'total' => dbNumberFormat($produto['preco_desconto']) * intval($produto['quantidade_solicitada']),
                    'status' => 1
                ];
                $this->db->where('id_pedido', $post['id']);
                $this->db->where('cd_produto_fornecedor', $k);
                $this->db->where('status', 0);
                $this->db->update('pedidos_representantes_produtos', $prod);

            }
        }

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            $warning = ["type" => "warning", "message" => "Erro ao registrar"];
        } else {
            $this->db->trans_commit();
            $warning = ["type" => "success", "message" => "Registrado com sucesso"];

        }
        $this->output->set_content_type('application/json')->set_output(json_encode($warning));

        redirect($this->route);
    }

    /**
     * Aprova os itens com status 0 do pedido
     *
     * @param - int - id do pedido
     * @return  json
     */
    public function approve($id_pedido)
    {
        if ($this->input->is_ajax_request()) {

            $pedido = $this->pedido->find("*", "id = {$id_pedido}", true);
            $representante = $this->rep->findById($pedido['id_representante']);


            $cdPedido = "PHX_" . $pedido['id_comprador'] . "-" . $pedido['id'];
            $oc = $this->db->where('Cd_Ordem_Compra', $cdPedido)->get('ocs_sintese')->row_array();
            $insert = true;

            if (!is_null($oc)) {
                $insert = false;
            }

            $data = ["status" => 1, "faturado" => 1];

            $this->db->where('id_pedido', $id_pedido);
            $this->db->where('status', 1);
            $this->db->where('faturado', 0);
            $prd = $this->db->get('pedidos_representantes_produtos');
            $num_rows = $prd->num_rows();

            # Não existe itens para aprovação
            if ($num_rows < 1) {
                $warning = ["type" => "success", "message" => "Registrado com sucesso"];
            } else {

                // aprovado parcialmente
                $this->db->where('status', 9);
                $this->db->where('id_pedido', $id_pedido);
                $produtos = $this->db->get('pedidos_representantes_produtos')->result_array();


                if (!empty($produtos)) {
                    $this->db->where('id', $id_pedido);
                    $this->db->update('pedidos_representantes', ['situacao' => 6]);
                } else {
                    $this->db->where('id', $id_pedido);
                    $this->db->update('pedidos_representantes', ['situacao' => 4]);
                }

                $update = $this->db->update("pedidos_representantes_produtos", $data, "id_pedido = {$id_pedido} AND status = 1");

                if ($update) {

                    $total = $this->pedido_prod->totalPedidoAprovado($pedido['id']);
                    $cliente = $this->cliente->findById($pedido['id_comprador']);
                    $cliente_estado = $this->db->where('uf', $cliente['estado'])->get('estados')->row_array();

                    # Ordem de Compra

                    // inicia transação de banco de dados
                    $this->db->trans_start();


                    if ($insert) {

                        if ($cliente['pharma'] == 1) {
                            $cdPedido = "PHARMA_" . $cliente['id'] . "-" . $pedido['id'];
                        } else {
                            $cdPedido = "PHX_" . $cliente['id'] . "-" . $pedido['id'];
                        }


                        $oc = [
                            'Dt_Gravacao' => date('Y-m-d H:i:s', time()),
                            'Tp_Movimento' => 1,
                            'Cd_Fornecedor' => '',
                            'Cd_Condicao_Pagamento' => '',
                            'Cd_Cotacao' => $cdPedido,
                            'Cd_Ordem_Compra' => $cdPedido,
                            'Dt_Ordem_Compra' => date('Y-m-d', time()),
                            'Hr_Ordem_Compra' => date('H:i:s', time()),
                            'Cd_Comprador' => preg_replace('/[^0-9]/', '', $cliente['cnpj']),
                            'id_comprador' => $cliente['id'],
                            'Nm_Aprovador' => $this->session->nome,
                            'Ds_Observacao' => 'Pedido gerado a partir do representante: ' . $representante['nome'],
                            'endereco_entrega' => "Combinar com o comprador",
                            'id_fornecedor' => $this->session->id_fornecedor,
                            'Status_OrdemCompra' => 1,
                            'integrador' => 999,
                            'pendente' => 1,
                            'prioridade' => $pedido['prioridade']
                        ];

                        $this->db->insert('ocs_sintese', $oc);
                        $id = $this->db->insert_id();

                    } else {
                        $id = $oc['id'];
                    }


                    // produtos da oc

                    $produtos = $this->pedido_prod->getProdutosPedidos($pedido['id']);
                    $oc_produtos = [];

                    foreach ($produtos as $produto) {

                        $existProd = $this->db->where('codigo', $produto['cd_produto_fornecedor'])
                            ->where('id_ordem_compra', $id)
                            ->get('ocs_sintese_produtos')
                            ->row_array();

                        if (is_null($existProd)) {
                            $oc_produtos[] = [
                                'id_ordem_compra' => $id,
                                'Cd_Produto_Comprador' => $produto['cd_produto_fornecedor'],
                                'codigo' => $produto['cd_produto_fornecedor'],
                                'Cd_Ordem_Compra' => $oc['Cd_Ordem_Compra'],
                                'Id_Produto_Sintese' => $produto['cd_produto_fornecedor'],
                                'Qt_Produto' => isset($produto['quantidade_solicitada']) ? $produto['quantidade_solicitada'] : '',
                                'Vl_Preco_Produto' => $produto['preco_unidade'],
                                'id_confirmacao' => null,
                            ];
                        }

                    }

                    $this->db->insert_batch("ocs_sintese_produtos", $oc_produtos);


                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }

                    $date = date('d/m/Y');

                    $destinatarios = "marlon.boecker@pharmanexo.com.br";

                    $fornecedor = $this->fornecedor->findById($pedido['id_fornecedor']);

                    # Concatena o email de cada promoção
                    if (!empty($fornecedor['emails_config'])) {

                        $emails = json_decode($fornecedor['emails_config']);
                        if (!empty($emails->representantes)) {
                            $destinatarios .= ", {$emails->representantes}";
                        }
                    }

                    if (!empty($representante['email'])) {
                        $destinatarios = $destinatarios . ", {$representante['email']}";
                    }


                    #noticar por e-mail
                    $noticar = [
                        "to" => $destinatarios,
                        "greeting" => "{$representante['nome']}",
                        "subject" => "Pedido #{$id_pedido} Aprovado / Representante: {$representante['nome']}",
                        "message" => "O pedido #{$id_pedido} enviado em {$date} foi aprovado! <br> Ordem de Compra: {$oc['Cd_Ordem_Compra']}"
                    ];

                    $this->notify->send($noticar);

                    #noticar por notifações pharmanexo
                    $alert = [
                        "id_usuario" => null,
                        "id_fornecedor" => $pedido['id_fornecedor'],
                        "message" => "Pedido #{$id_pedido} do representante {$representante['nome']} foi aprovado. clique para ver mais",
                        "url" => base_url("fornecedor/representantes/pedidos/detalhes/{$id_pedido}")
                    ];

                    $this->notify->alert($alert);

                    $warning = ["type" => "success", "message" => "Registrado com sucesso"];
                } else {
                    $warning = ["type" => "warning", "message" => "Erro ao registrar"];
                }
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    /**
     * Rejeita os itens do pedido
     *
     * @param - int - id do pedido
     * @return  json
     */
    public function reject($id_pedido)
    {
        if ($this->input->is_ajax_request()) {

            $pedido = $this->pedido->find("*", "id = {$id_pedido}", true);
            $representante = $this->rep->findById($pedido['id_representante']);

            $data = ["motivo" => $this->input->post('motivo'), "status" => 9];

            if ($this->db->update("pedidos_representantes_produtos", $data, "id_pedido = {$id_pedido}")) {

                $this->validar_pedido($id_pedido);

                $date = date('d/m/Y');

                $fornecedor = $this->fornecedor->findById($pedido['id_fornecedor']);

                $destinatarios = "marlon.boecker@pharmanexo.com.br, ericlempe1994@gmail.com";

                # Concatena o email de cada promoção
                if (!empty($fornecedor['emails_config'])) {

                    $emails = json_decode($fornecedor['emails_config']);

                    if (!empty($emails->representantes)) {
                        $destinatarios .= ", {$emails->representantes}";
                    }
                }

                #noticar por e-mail
                $noticar = [
                    "to" => $destinatarios,
                    "greeting" => "{$representante['nome']}",
                    "subject" => "Pedido #{$id_pedido} Rejeitado / Representante: {$representante['nome']}",
                    "message" => "O pedido #{$id_pedido} enviado em {$date} não pôde ser aprovado: {$data['motivo']}"
                ];

                $this->notify->send($noticar);

                #noticar por notifações pharmanexo
                $alert = [
                    "id_usuario" => null,
                    "id_fornecedor" => $pedido['id_fornecedor'],
                    "message" => "Pedido #{$id_pedido} do representante {$representante['nome']} foi rejeitado. clique para ver mais",
                    "url" => base_url("fornecedor/representantes/pedidos/detalhes/{$id_pedido}")
                ];

                $this->notify->alert($alert);

                $warning = [
                    "type" => "success",
                    "message" => "Registrado com sucesso"
                ];
            } else {
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao registrar"
                ];
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    /**
     * Rejeita o item
     *
     * @param - int - id do pedido.
     * @param - post - codigo do produto (cd_produto_fornecedor)
     * @return  json
     */
    public function reject_item($id_pedido)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $data = ['status' => 9, 'motivo' => $post['motivo']];

            if ($this->db->update("pedidos_representantes_produtos", $data, "id_pedido = {$id_pedido} AND cd_produto_fornecedor = {$post['codigo']}")) {

                $warning = [
                    "type" => "success",
                    "message" => "Registrado com sucesso"
                ];
            } else {
                $warning = [
                    "type" => "warning",
                    "message" => "Erro ao registrar"
                ];
            }
        }

        $this->validar_pedido($id_pedido);

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));
    }

    public function validar_pedido($id_pedido)
    {
        $this->db->where('status <> 9');
        $this->db->where('id_pedido', $id_pedido);
        $produtos = $this->db->get('pedidos_representantes_produtos')->result_array();


        if (empty($produtos)) {
            $this->db->where('id', $id_pedido);
            $this->db->update('pedidos_representantes', ['situacao' => 5]);
        }

    }


    public function exportar()
    {
        $this->db->select("
            pr.id,
            r.nome AS representante,
            c.razao_social AS comprador,
            pr.prazo_entrega,
            DATE_FORMAT(data_abertura, '%d/%m/%Y %H:%i:%s') AS data_abertura
            ");
        $this->db->from("pedidos_representantes pr");
        $this->db->join('representantes r', 'r.id = pr.id_representante', 'left');
        $this->db->join('compradores c', 'c.id = pr.id_comprador', 'left');
        $this->db->where('pr.id_fornecedor', $this->session->id_fornecedor);
        $this->db->where("pr.situacao in (2, 3)");
        $this->db->order_by("r.id ASC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'id' => '',
                'representante' => '',
                'comprador' => '',
                'prazo_entrega' => '',
                'data_abertura' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'representantes'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    public function exportar_detalhes($id_pedido)
    {
        $this->db->select("
            prp.cd_produto_fornecedor AS codigo_produto,
            pc.nome_comercial AS produto,
            prp.quantidade_solicitada,
            FORMAT(prp.preco_unidade, 4, 'de_DE') AS preco_unidade,
            FORMAT(prp.desconto, 4, 'de_DE') AS desconto,
            FORMAT(prp.preco_desconto, 4, 'de_DE') AS preco_desconto,
            FORMAT(prp.total, 4, 'de_DE') AS total");
        $this->db->from("pedidos_representantes_produtos prp");
        $this->db->join('pedidos_representantes pr', 'pr.id = prp.id_pedido');
        $this->db->join('produtos_catalogo pc', 'pc.codigo = prp.cd_produto_fornecedor and pr.id_fornecedor = pc.id_fornecedor', 'left');
        $this->db->where("prp.id_pedido = {$id_pedido}");


        $query = $this->db->get()->result_array();

        if (count($query) < 1) {
            $query[] = [
                'codigo_produto' => '',
                'produto' => '',
                'quantidade_solicitada' => '',
                'preco_unidade' => '',
                'desconto' => '',
                'preco_desconto' => '',
                'total' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'representantes'];

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