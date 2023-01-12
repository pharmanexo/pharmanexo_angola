<?php

class Pedidos extends Conv_controller
{

    private $route;
    private $urlselect2;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("convidados/pedidos/");
        $this->urlselect2 = base_url("convidados/select2");
        $this->views = "convidados/pedidos/";

        $this->load->model('M_compradores', 'compradores');
        $this->load->model('M_estados', 'estado');
        $this->load->model('m_forma_pagamento', 'forma_pagamento');
        $this->load->model('m_forma_pagamento_fornecedor', 'forma_pagamento_fornecedor');
        $this->load->model('m_prazo_entrega', 'prazo_entrega');
        $this->load->model('m_valor_minimo', 'valor_minimo');

        $this->load->model('m_pedido_rep', 'pedido');
        $this->load->model('m_representante', 'rep');
        $this->load->model('m_fornecedor', 'fornecedor');
        $this->load->model('m_produto', 'produto');
    }

    /**
     * Redireciona para a função form
     *
     * @return redirect
     */
    public function index()
    {
        $page_title = "Pedidos";

        $data['to_datatable'] = "{$this->route}/to_datatable/";
        $data['url_update'] = "{$this->route}/detalhes/";

        $data['header'] = $this->tmp_conv->header(['title' => $page_title,]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,
            'buttons' => [

            ]
        ]);
        $data['scripts'] = $this->tmp_conv->scripts();

        $data['status'] = $this->db->get('conv_pedidos_status')->result_array();

        $this->load->view("{$this->views}/main", $data);
    }


    public function detalhes($idPedido)
    {
        $pedido = $this->db->where('id', $idPedido)->get('conv_pedidos')->row_array();
        $pedido['situacao'] = $this->db->where('id', $pedido['situacao'])->get('conv_pedidos_status')->row_array();

        $pedidoItens = $this->db
            ->select('cp.*, p.descricao')
            ->from('conv_pedidos_produtos cp')
            ->join('conv_pedidos pd', 'pd.id = cp.id_pedido')
            ->join('conv_promocoes p', 'p.codigo = cp.codigo and p.id_fornecedor = pd.id_fornecedor', 'INNER')
            ->where('id_pedido', $idPedido)
            ->group_by('p.codigo, p.id_fornecedor')
            ->get()->result_array();

        $total = 0;
        $totalCancelado = 0;
        foreach ($pedidoItens as $item) {
            if ($item['situacao'] <> 9){
                $total = ($item['quantidade'] * $item['preco_unitario']) + $total;
            }else{
                $totalCancelado = ($item['quantidade'] * $item['preco_unitario']) + $totalCancelado;
            }

        }

        $pedido['total'] = $total;
        $pedido['totalCancelado'] = $totalCancelado;

        $pedido['fornecedor'] = $this->db->where('id', $pedido['id_fornecedor'])->get('fornecedores')->row_array();

        $page_title = "Detalhes do Pedido";

        $pedido['produtos'] = $pedidoItens;

        $data['header'] = $this->tmp_conv->header(['title' => $page_title,]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading([
            'page_title' => $page_title,
            'buttons' => [
                ['type' => 'submit',
                    'id' => 'btnPedido',
                    'form' => 'frmPedido',
                    'class' => ($pedido['fechado'] == 1) ? 'btn-primary d-none' : 'btn-primary',
                    'icone' => 'fa-cart',
                    'label' => 'Enviar Pedido'
                ],
            ]
        ]);
        $data['scripts'] = $this->tmp_conv->scripts();

        $data['formAction'] = "{$this->route}/salvar";
        $data['urlDelete'] = "{$this->route}/delete_item/{$pedido['id']}/";
        $data['pedido'] = $pedido;

        $this->load->view("{$this->views}/detalhes", $data);

    }

    public function delete_item($id_pedido, $id_item)
    {
        $delete = $this->db
            ->where('id_pedido', $id_pedido)
            ->where('id', $id_item)
            ->delete('conv_pedidos_produtos');

        if ($delete) {
            $warn = [
                'type' => 'success',
                'message' => "Produto removido do pedido"
            ];

            $itens = $this->db->where('id_pedido', $id_pedido)->get('conv_pedidos_produtos');

            if ($itens->num_rows() == 0) {
                $warn = [
                    'type' => 'success',
                    'message' => "Produto removido do pedido, o pedido foi cancelado",
                    'redir' => $this->route
                ];

                $this->db->where('id', $id_pedido)->update('conv_pedidos', ['situacao' => 5, 'fechado' => 1]);
            } else {
                $warn = [
                    'type' => 'success',
                    'message' => "Produto removido do pedido",
                ];
            }

        } else {
            $warn = [
                'type' => 'warning',
                'message' => "Erro ao remover os registros"
            ];
        }

        $_SESSION['warning'] = $warn;

        if (isset($warn['redir'])) {
            redirect($warn['redir']);
        } else {
            redirect("{$this->route}/detalhes/{$id_pedido}");
        }

    }

    public function salvar()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            if (isset($post['idpedido'])) {
                $contato = $this->db
                    ->where('id_comprador', $_SESSION['dados']['id'])
                    ->get('compradores_contatos')
                    ->row_array();

                $pedido = $this->db
                    ->where('id', $post['idpedido'])
                    ->where('fechado', 0)
                    ->get('conv_pedidos')->row_array();


                if (!empty($pedido)) {
                    $pedido['obs'] = $post['obs'];
                    $fornecedor = $this->db->where('id', $pedido['id_fornecedor'])->get('fornecedores')->row_array();
                    $pedidoItens = $this->db
                        ->select('cp.*, p.descricao, p.unidade, p.marca, p.preco, p.id as id_produto')
                        ->from('conv_pedidos_produtos cp')
                        ->join('conv_pedidos pd', 'pd.id = cp.id_pedido')
                        ->join('conv_promocoes p', 'p.codigo = cp.codigo and p.id_fornecedor = pd.id_fornecedor', 'INNER')
                        ->where('id_pedido', $post['idpedido'])
                        ->group_by('p.codigo, p.id_fornecedor')
                        ->get()->result_array();

                    // gera OC no distribuidor

                    $pedido['fornecedor'] = $fornecedor;
                    $pedido['produtos'] = $pedidoItens;


                    $gravarOc = $this->registrarOrdemCompra($pedido);

                    if (!empty($gravarOc)) {

                        // atualiza o pedido
                        $update = [
                            'data_envio' => date('Y-m-d H:i:s', time()),
                            'fechado' => 1,
                            'situacao' => 2,
                            'observacao' => $pedido['obs'],
                            'ordem_compra' => $gravarOc['Cd_Ordem_Compra']
                        ];


                        $this->db->where('id', $pedido['id'])->update('conv_pedidos', $update);


                        // envia e-mail ao distribuidor notificando (verificar qual configuração usar)


                        // envia e-mail PARA O COMPRADOR E DISTRIBUIDOR notificando (com espelho)
                        $message = "Olá, pedido processado e
                                     enviado do distribuidor, verifique abaixo os detalhes do pedido. \n\n";
                        $espelho = $this->gerarEspelho($pedido);

                        $sendEspelho = $this->notify->send([
                            "to" => $contato['email'],
                            "greeting" => "Olá, {$contato['nome']}",
                            "subject" => "Pharmanexo - Novo Pedido - Comprador Convidado",
                            "message" => $message . $espelho,
                        ]);


                        $warn = [
                            'type' => 'success',
                            'message' => "Pedido enviado ao distribudior!"
                        ];


                    } else {
                        $warn = [
                            'type' => 'warning',
                            'message' => "Erro ao salvar os registros"
                        ];
                    }


                }

            } else {
                $warn = [
                    'type' => 'warning',
                    'message' => "Pedido não localizado ou encerrado."
                ];
            }


            $_SESSION['warning'] = $warn;
            redirect($this->route);
        }

    }

    private function gerarEspelho($pedido)
    {

        $comp = $this->db
            ->where('id', $_SESSION['dados']['id'])
            ->get('compradores')
            ->row_array();

        $dataPedido = date("d/m/Y H:i:s", strtotime($pedido['data_pedido']));

        $html = "";

        $html .= "<table>
                        <tr>
                            <td><strong>Pedido</strong></td>
                            <td>{$pedido['id']}</td>
                            <td><strong>Comprador</strong></td>
                            <td>{$comp['cnpj']} - {$comp['razao_social']}</td>
                        </tr>
                         <tr>
                            <td><strong>Data Pedido</strong></td>
                            <td>{$dataPedido}</td>
                            <td><strong>Fornecedor</strong></td>
                            <td>{$pedido['fornecedor']['cnpj']} - {$pedido['fornecedor']['nome_fantasia']}</td>
                        </tr>
                    </table> <hr>";

        $html .= "
        <table>
         <tr>
            <thead>
               <th>CODIGO</th>
               <th>PRODUTO</th>
               <th>QTDE</th>
               <th>VALOR</th>
               <th>TOTAL</th>
               <th></th>
               </thead>
           </tr>
      
      
        ";
        foreach ($pedido['produtos'] as $produto) {
            $html .= "
            <tr>
            <td> {$produto['codigo']} </td>
                                <td> {$produto['descricao']} </td>
                                <td> {$produto['quantidade']} </td>
                                <td> " . number_format($produto['preco_unitario'], 2, ',', '.') . "</td>
                                <td>" . number_format(($produto['preco_unitario'] * $produto['quantidade']), 2, ',', '.') . " </td>
           </tr>
            
            ";
        }

        $html .= " </table>";

        return $html;

    }

    private function registrarOrdemCompra($pedido = null)
    {
        if (!empty($pedido)) {


            $comp = $this->db
                ->where('id', $_SESSION['dados']['id'])
                ->get('compradores')
                ->row_array();

            $contato = $this->db
                ->where('id_comprador', $comp['id'])
                ->get('compradores_contatos')
                ->row_array();
            $cdPedido = 'PHX_CONV_' . $pedido['fornecedor']['id'] . $pedido['id'];

            $verificaOcExistente = $this->db->where('Cd_Ordem_Compra', $cdPedido)->get('ocs_sintese');

            if ($verificaOcExistente->num_rows() == 0) {
                $oc = [
                    'Dt_Gravacao' => date('Y-m-d H:i:s', time()),
                    'Tp_Movimento' => 'I',
                    'Cd_Fornecedor' => soNumero($pedido['fornecedor']['cnpj']),
                    'Cd_Ordem_Compra' => $cdPedido,
                    'Cd_Cotacao' => $cdPedido,
                    'Dt_Ordem_Compra' => date('Y-m-d', time()),
                    'Hr_Ordem_Compra' => date('H:i:s', time()),
                    'Tp_Situacao' => 1,
                    'Cd_Comprador' => soNumero($comp['cnpj']),
                    'id_comprador' => $comp['id'],
                    'id_fornecedor' => $pedido['fornecedor']['id'],
                    'Nm_Cidade' => $comp['cidade'],
                    'Id_Unidade_Federativa' => $comp['estado'],
                    'Nm_Aprovador' => $contato['nome'],
                    'Telefones_Ordem_Compra' => $contato['telefone'],
                    'pendente' => 1,
                    'Status_OrdemCompra' => 1,
                    'integrador' => 100,
                    'Ds_Observacao' => $pedido['obs']
                ];

                $this->db->insert('ocs_sintese', $oc);
                $idOc = $this->db->insert_id();

                if ($idOc > 0) {
                    $produtosOC = [];

                    foreach ($pedido['produtos'] as $produto) {

                        $produtosOC[] = [
                            'id_ordem_compra' => $idOc,
                            'Ds_Unidade_Compra' => $produto['unidade'],
                            'Ds_Marca' => $produto['marca'],
                            'Qt_Embalagem' => 1,
                            'Qt_Produto' => $produto['quantidade'],
                            'Vl_Preco_Produto' => $produto['preco_unitario'],
                            'Cd_ProdutoERP' => $produto['codigo'],
                            'Cd_Ordem_Compra' => $oc['Cd_Ordem_Compra'],
                            'Id_Produto_Sintese' => $produto['id_produto'],
                            'Ds_Produto_Comprador' => $produto['descricao'],
                            'codigo' => $produto['codigo']
                        ];

                    }

                    $insertProd = $this->db->insert_batch('ocs_sintese_produtos', $produtosOC);

                    return $oc;
                } else {
                    return false;
                }
            } else {
                return false;
            }

        }
    }

    public function addItem($idProd, $qtd)
    {
        if (isset($idProd) && !empty($idProd)) {
            $prod = $this->db->where('id', $idProd)->get('conv_promocoes')->row_array();
            $idComprador = $_SESSION['dados']['id'];

            if (!empty($prod)) {
                if ($qtd > $prod['quantidade']) {
                    $warn = [
                        'type' => 'warning',
                        'message' => 'Não é permitido quantidades maiores que o estoque disponível'
                    ];
                } else {

                    // verifica se existe um pedido aberto
                    $pedido = $this->db
                        ->where("id_comprador", $idComprador)
                        ->where("id_fornecedor", $prod['id_fornecedor'])
                        ->where("fechado", 0)
                        ->get('conv_pedidos')
                        ->row_array();

                    if (empty($pedido)) {
                        $insertPedido = [
                            'id_comprador' => $idComprador,
                            'id_fornecedor' => $prod['id_fornecedor'],
                        ];

                        $this->db->insert('conv_pedidos', $insertPedido);
                        $idPedido = $this->db->insert_id();
                    } else {
                        $idPedido = $pedido['id'];
                    }

                    $pedidoProd = $this->db
                        ->where('id_pedido', $idPedido)
                        ->where('codigo', $prod['codigo'])
                        ->get('conv_pedidos_produtos');

                    if ($pedidoProd->num_rows() > 0) {

                        $pedidoProd = $this->db
                            ->where('id_pedido', $idPedido)
                            ->where('codigo', $prod['codigo'])
                            ->set('quantidade', $qtd)
                            ->update('conv_pedidos_produtos');

                        if ($pedidoProd) {
                            $warn = [
                                'type' => 'success',
                                'message' => 'O produto ja existe no pedido, atualizamos a quantidade para o valor informado'
                            ];
                        } else {
                            $warn = [
                                'type' => 'warning',
                                'message' => 'O produto ja existe no pedido, mas não conseguimos atualizar, consulte o suporte'
                            ];
                        }

                    } else {
                        $dataProd = [
                            'id_pedido' => $idPedido,
                            'codigo' => $prod['codigo'],
                            'quantidade' => $qtd,
                            'preco_unitario' => $prod['preco'],
                            'situacao' => 1,
                            'data_registro' => date("Y-m-d H:i:s", time())
                        ];

                        $this->db->insert('conv_pedidos_produtos', $dataProd);

                        $warn = [
                            'type' => 'success',
                            'message' => 'Produto enviado para o pedido'
                        ];
                    }


                }

            } else {
                $warn = [
                    'type' => 'warning',
                    'message' => 'Produto não encontrado!'
                ];
            }


        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warn));
    }

    /**
     * Salva novo produto ou atualiza de um pedido do representante
     *
     * @param request post form
     * @return json
     */
    public function save()
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $post['preco_unidade'] = dbNumberFormat($post['preco_unidade']);

            $total = dbNumberFormat($post['quantidade_solicitada']) * dbNumberFormat($post['preco_desconto']);

            $post['total'] = $total;

            if (isset($post['desconto'])) $post['desconto'] = dbNumberFormat($post['desconto']);
            if (isset($post['preco_desconto'])) $post['preco_desconto'] = dbNumberFormat($post['preco_desconto']);

            $this->db->where('id_pedido', $post['id_pedido']);
            $this->db->where('cd_produto_fornecedor', $post['cd_produto_fornecedor']);
            $produto_existente = $this->db->get('pedidos_representantes_produtos');

            if ($produto_existente->num_rows() > 0) {

                # Atualiza produto existente

                $this->db->where('id_pedido', $post['id_pedido']);
                $this->db->where('cd_produto_fornecedor', $post['cd_produto_fornecedor']);

                $operacao = $this->db->update('pedidos_representantes_produtos', $post);
                $success = "atualizado";
                $error = "atualizar";
            } else {

                # Novo produto

                $operacao = $this->db->insert('pedidos_representantes_produtos', $post);
                $success = "cadastrado";
                $error = "cadastrar";
            }

            if ($operacao) {

                $warning = ['type' => 'success', 'message' => "Produto {$success}", 'id_pedido' => $post['id_pedido']];

            } else {
                $warning = ['type' => 'warning', 'message' => "Erro ao {$error} produto"];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($warning));
        }
    }

    public function get_produtos($id_pedido)
    {
        $this->db->where('id_pedido', $id_pedido);
        $result = $this->db->get('pedidos_representantes_produtos')->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    private function form($id = null)
    {
        $data['select_formas_pagamento'] = "{$this->urlselect2}/to_select2_formas_pagamento";
        $data['select_produtos'] = "{$this->urlselect2}/get_produtos/";
        $data['select_clientes'] = "{$this->urlselect2}/to_select2_compradores";
        $data['id_fornecedor'] = $this->session->id_fornecedor;
        $data['form_action'] = "{$this->route}saveOC";
        $data['form_action_produtos'] = "{$this->route}save";
        $data['url_datatable'] = "{$this->route}datatables_produtos/";
        $data['url_finalize'] = "{$this->route}finalizeRequest";
        $data['url_cancel'] = "{$this->route}cancelRequest";
        $data['url_preco'] = "{$this->route}getPrice";
        $data['url_historico'] = "{$this->route}get_historico";

        $this->db->where('id_representante', $this->session->id);
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $data['comissao'] = $this->db->get('representantes_fornecedores')->row_array()['comissao'];

        if (isset($id)) {
            $data['dados'] = $this->pedido->findById($id);
        }

        $buttons = [
            [
                'type' => 'button',
                'id' => 'btnCancel',
                'url' => "{$this->route}cancelRequest",
                'class' => 'btn-danger',
                'icone' => 'fa-ban',
                'label' => 'Cancelar Pedido'
            ],
            [
                'type' => 'button',
                'id' => 'btnExport',
                'url' => "{$this->route}exportar/{$id}",
                'class' => 'btn-primary',
                'icone' => 'fa-file-excel',
                'label' => 'Exportar Excel'
            ],
            [
                'type' => 'button',
                'id' => 'btnInsert',
                'url' => "{$this->route}finalizeRequest",
                'class' => 'btn-primary',
                'icone' => 'fa-check',
                'label' => 'Finalizar Pedido'
            ]
        ];

        if (isset($data['dados']['situacao']) && $data['dados']['situacao'] != '1') {
            $buttons = [];
        }

        $data['header'] = $this->tmp_conv->header([
            'title' => 'Novo Pedido',
            'buttons' => $buttons
        ]);
        $data['navbar'] = $this->tmp_conv->navbar();
        $data['sidebar'] = $this->tmp_conv->sidebar();
        $data['heading'] = $this->tmp_conv->heading();
        $data['scripts'] = $this->tmp_conv->scripts();

        $this->load->view("{$this->views}form", $data);
    }

    public function to_datatable()
    {
        $id_comprador = $_SESSION['dados']['id'];

        $r = $this->datatable->exec(
            $this->input->post(),
            'conv_pedidos cp',
            [
                ['db' => 'cp.id', 'dt' => 'id'],
                ['db' => 'cp.data_pedido', 'dt' => 'data_pedido', 'formatter' => function ($r) {
                    return date("d/m/Y H:i", strtotime($r));
                }],
                ['db' => 'cp.data_envio', 'dt' => 'data_envio', 'formatter' => function ($r) {
                    return date("d/m/Y H:i", strtotime($r));
                }],
                ['db' => 'cp.situacao', 'dt' => 'situacao'],
                ['db' => 'cp.situacao', 'dt' => 'situacao_lbl', 'formatter' => function ($r, $d) {

                    $status = $this->db->where('id', $r)->get('conv_pedidos_status')->row_array();
                    return (!empty($status)) ? $status['descricao'] : 'Indefinido';
                }],
                ['db' => 'cp.observacao', 'dt' => 'observacao'],
                ['db' => 'cp.data_faturamento', 'dt' => 'data_faturamento'],
                ['db' => 'cp.fechado', 'dt' => 'fechado'],
                ['db' => 'f.id', 'dt' => 'id_fornecedor'],
                ['db' => 'f.razao_social', 'dt' => 'razao_social'],
                ['db' => 'f.nome_fantasia', 'dt' => 'fornecedor'],
                ['db' => 'cp.id', 'dt' => 'total', 'formatter' => function ($r, $d) {
                    $total = $this->db
                        ->select("sum(quantidade * preco_unitario) as total")
                        ->where('id_pedido', $r)
                        ->get('conv_pedidos_produtos')
                        ->row_array();

                    return "R$ " . number_format($total['total'], 2, ',', '.');
                }],

            ],
            [
                ["fornecedores f", "f.id = cp.id_fornecedor"],
            ],
            "id_comprador = {$id_comprador}",
            ""
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }


}
