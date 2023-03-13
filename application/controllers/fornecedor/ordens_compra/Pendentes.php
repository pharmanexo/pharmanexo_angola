<?php

class Pendentes extends MY_Controller
{

    private $views;
    private $route;
    private $mirrorView;
    private $urlOncoprod;

    public function __construct()
    {
        parent::__construct();

        $this->views = 'fornecedor/ordens_compra/pendentes';
        $this->route = base_url('fornecedor/ordens_compra/pendentes');

        $this->mirrorView = 'fornecedor/ordens_compra';

        $this->load->model('Ordem_Compra', 'oc');
        $this->load->model('M_compradores', 'comp');
        $this->load->model('m_fornecedor', 'fornecedor');

        # URLs
        $this->urlCliente_sintese = $this->config->item('db_config')['url_client'];


        if ($this->config->item('db_config')['wb_oncoprod'] == 'teste') {
            $this->urlOncoprod = $this->config->item('db_config')['url_oncoprod_teste'];
        } else {
            $this->urlOncoprod = $this->config->item('db_config')['url_oncoprod'];
        }


    }

    /**
     * Direciona para a função main
     *
     * @return redirect
     */
    public function index()
    {
        $this->main();
    }

    /**
     * Exibe a tela de ocs pendentes
     *
     * @return view
     */
    private function main()
    {

        $page_title = "Ordens de Compra Pendentes";

        $data['header'] = $this->template->header([
            'title' => $page_title,
            'styles' => [
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
                   'url' => "javascript:history.back(1)",
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/export",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnChangeStatus',
                    'url' => "",
                    'class' => 'btn-success',
                    'icone' => 'fa-tasks',
                    'label' => 'Marcar como resgatadas'
                ],
            ]
        ]);
        $data['scripts'] = $this->template->scripts([
            'scripts' => [
            ]
        ]);

        $data['urlDatatables'] = "{$this->route}/to_datatable";
        $data['urlDetalhes'] = "{$this->route}/detalhes/";
        $data['urlChangeStatusPending'] = "{$this->route}/changeStatusPendingAll";
        $data['integradores'] = $this->db->get('integradores')->result_array();
        $data['compradores'] = $this->comp->find("id, CONCAT(cnpj, ' - ', razao_social) AS comprador", null, FALSE, 'comprador ASC');
        $data['estados'] = $this->db->get('estados')->result_array();

        $rede = $this->db
            ->where('id_usuario', $this->session->id_usuario)
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->get('usuarios_rede_atendimento')
            ->result_array();


        $rede_estados = [];
        $rede_clientes = [];

        foreach ($rede as $item) {
            if ($item['id_estado'] > 0) {
                $rede_estados[] = $item;
            } else {
                $rede_clientes[] = $item;
            }
        }


        foreach ($rede_estados as $rest) {
            foreach ($data['estados'] as $k => $estado) {
                if ($estado['id'] == $rest['id_estado']) {
                    $data['estados'][$k]['selected'] = true;
                }
            }
        }


        $this->load->view("{$this->views}/main", $data);
    }

    /**
     * Exibe a tela de detalhes de uma oc
     *
     * @return view
     */
    public function detalhes($idOC)
    {
        $page_title = "Ordem de Compra";

        $data['oc'] = $this->getOC($idOC);
        if (!empty($data['oc']['id_usuario_cancelamento'])) {
            $data['oc']['usuario_cancelamento'] = $this->db
                ->select('id, nickname, email')
                ->where('id', $data['oc']['id_usuario_cancelamento'])
                ->get('usuarios')
                ->row_array();
        }


        /*  $status = $this->oc->get_status($data['oc']['Status_OrdemCompra']);

          $data['oc']['situacao'] = (!empty($status) && !empty($status['descricao'])) ? $status['descricao'] : '';*/


        $fp = $this->oc->getCotFormaPagamento($data['oc']['id_fornecedor'], $data['oc']['Cd_Cotacao']);

        if ($fp !== false) {
            $data['oc']['fp'] = $fp;
        } else {
            $data['oc']['fp'] = 'Não informado';
        }

        if (isset($data['oc']['Cd_Condicao_Pagamento']) && !empty($data['oc']['Cd_Condicao_Pagamento'])) {

            $id = $data['oc']['Cd_Condicao_Pagamento'];
            if (is_numeric($id)) {
                $select = $this->db->where('id', $id)->get('formas_pagamento')->row_array();

                if (!empty($select)) {
                    $fp = $select['descricao'];
                } else {
                    $fp = $id;
                }

            }

        }

        $data['oc']['fp_oc'] = $fp;

        $data['to_datatable'] = "{$this->route}/to_datatable_produtos";
        $data['url_resgate'] = "{$this->route}/resgatar/{$idOC}";
        $data['url_codigo'] = "{$this->route}/addCodigo/{$idOC}";
        $data['url_cancel_item'] = "{$this->route}/cancelItem/";
        $data['url_cancel_oc'] = "{$this->route}/cancelOrdem/{$idOC}";
        $data['url_list'] = "{$this->route}";
        $data['urlChangeStatusPending'] = "{$this->route}/changeStatusPending/{$idOC}";

        # Select
        if (isset($_SESSION['id_matriz']) && $_SESSION['id_matriz'] == 1) {
            $data['formas_pagamento'] = $this->getFormaPagamento($data['oc']['comprador']['cnpj']);
        } else {
            $data['formas_pagamento'] = [];
        }


        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading([
            'page_title' => $page_title,
            'buttons' => [
                [
                    'type' => 'a',
                    'id' => 'btnVoltar',
                    'url' => $this->route,
                    'class' => 'btn-secondary',
                    'icone' => 'fa-arrow-left',
                    'label' => 'Retornar'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnChangeStatus',
                    'url' => $data['url_resgate'],
                    'class' => 'btn-success',
                    'icone' => 'fa-tasks',
                    'label' => 'Marcar como resgatada'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnCancelOc',
                    'url' => $data['url_cancel_oc'],
                    'class' => 'btn-danger',
                    'icone' => 'fa-ban',
                    'label' => 'Cancelar Ordem de Compra'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnResgate',
                    'url' => "",
                    'class' => 'btn-primary',
                    'icone' => 'fa-check',
                    'label' => 'Efetuar Resgate'
                ],
                [
                    'type' => 'button',
                    'id' => 'btnExport',
                    'url' => "{$this->route}/export_details/{$idOC}",
                    'class' => 'btn-primary',
                    'icone' => 'fa-file-excel',
                    'label' => 'Exportar Excel'
                ]
            ]
        ]);
        $data['scripts'] = $this->template->scripts();

        #usuario fornecedor
        $data['usuarios'] = $this->oc->get_usuarios($this->session->id_fornecedor);

        $data['matriz'] = (isset($_SESSION['id_matriz'])) ? $_SESSION['id_matriz'] : 0;

        $this->load->view("{$this->views}/detalhes", $data);
    }

    public function addCodigo($id_oc)
    {

        if ($this->input->is_ajax_request()) {
            $post = $this->input->post();

            $this->db->where('id_ordem_compra', $id_oc);
            $this->db->where('Cd_Produto_Comprador', $post['Cd_Produto_Comprador']);
            $this->db->where('Id_Produto_Sintese', $post['Id_Produto_Sintese']);
            $updt = $this->db->update('ocs_sintese_produtos', [
                'codigo' => $post['codigo']
            ]);

            if ($updt) {

                $output = ['type' => 'success', 'message' => 'Registro atualizado com sucesso'];
            } else {

                $output = ['type' => 'warning', 'message' => 'Erro ao atualizar registro'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function cancelItem($idProd)
    {
        if ($this->input->method()) {
            $post = $this->input->post();

            $produto = $this->db->where('id', $idProd)->get('ocs_sintese_produtos')->row_array();
            if (!empty($produto)) {
                $oc = $this->db->where('id', $produto['id_ordem_compra'])->get('ocs_sintese')->row_array();

                if (!empty($oc)) {
                    $pedidoConvidado = $this->db
                        ->where('id_fornecedor', $this->session->id_fornecedor)
                        ->where('ordem_compra', $oc['Cd_Ordem_Compra'])
                        ->get('conv_pedidos')->row_array();


                    $updateProd = [
                        'situacao' => 9,
                        'motivo_situacao' => $post['motivo'],
                        'id_usuario_cancelamento' => $this->session->id_usuario
                    ];

                    // atualiza o produto na OC
                    $this->db->where('id', $idProd)
                        ->update('ocs_sintese_produtos', $updateProd);

                    //atualiza item no pedido convidado
                    $this->db
                        ->where('codigo', $produto['codigo'])
                        ->where('id_pedido', $pedidoConvidado['id'])
                        ->update('conv_pedidos_produtos', $updateProd);

                    //verifica se o pedido ainda tem itens aprovados
                    $verificaItensAprovados = $this->db
                        ->where('id_pedido', $pedidoConvidado['id'])
                        ->where('situacao', 1)
                        ->get('conv_pedidos_produtos');

                    if ($verificaItensAprovados->num_rows() > 0) {
                        // atualiza a situação do pedido e OC
                        $this->db
                            ->where('id', $pedidoConvidado['id'])
                            ->update('conv_pedidos', ['situacao' => 6]);
                    } else {
                        // atualiza a situação do pedido e OC
                        $this->db
                            ->where('id', $pedidoConvidado['id'])
                            ->update('conv_pedidos', ['situacao' => 4]);

                        $this->db
                            ->where('id', $oc['id'])
                            ->update('ocs_sintese', ['pendente' => 0, 'Status_OrdemCompra' => 11, 'motivo_cancelamento' => 'TODOS ITENS FORAM REJEITADOS PELO DISTRIBUIDOR']);
                    }


                }
            }


        }


    }

    public function cancelOrdem($idOc)
    {
        if ($this->input->method()) {
            $post = $this->input->post();

            $oc = $this->db->where('id', $idOc)->get('ocs_sintese')->row_array();

            $updateProd = [
                'situacao' => 9,
                'motivo_situacao' => $post['motivo'],
                'id_usuario_cancelamento' => $this->session->id_usuario
            ];

            if (!empty($oc)) {
                $pedidoConvidado = $this->db
                    ->where('id_fornecedor', $this->session->id_fornecedor)
                    ->where('ordem_compra', $oc['Cd_Ordem_Compra'])
                    ->get('conv_pedidos')->row_array();

                if (!empty($pedidoConvidado)) {
                    $this->db
                        ->where('id', $pedidoConvidado['id'])
                        ->update('conv_pedidos', ['situacao' => 4]);


                    //atualiza item no pedido convidado
                    $this->db
                        ->where('id_pedido', $pedidoConvidado['id'])
                        ->update('conv_pedidos_produtos', $updateProd);

                }

                // atualiza o produto na OC
                $this->db->where('id_ordem_compra', $idOc)
                    ->update('ocs_sintese_produtos', $updateProd);

                $this->db
                    ->where('id', $oc['id'])
                    ->update('ocs_sintese',
                        [
                            'pendente' => 0,
                            'Status_OrdemCompra' => 11,
                            'motivo_cancelamento' => $post['motivo'],
                            'id_usuario_cancelamento' => $this->session->id_usuario
                        ]
                    );

                $warn = [
                    'type' => 'success',
                    'message' => 'Ordem de compra cancelada. Aguarde que vamos redirecionar a página.',
                    'redir' => $this->route
                ];
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($warn));

        }
    }

    /**
     * Excuta o resgate na sintese da oc
     *
     * @return array
     */
    public function resgatar($oc)
    {
        if ($this->input->is_ajax_request()) {

            $post = $this->input->post();

            $idForn = $this->session->id_fornecedor;
            $oc = $this->oc->find('*', "id = '{$oc}' and id_fornecedor = {$idForn}", true);

            $ocProds = $this->db->select("*")
                ->where('id_ordem_compra', $oc['id'])
                ->where('situacao is null')
                ->get('ocs_sintese_produtos')->result_array();

            if (!empty($ocProds)) {
                $prods = [];

                foreach ($ocProds as $prod) {

                    $prod['Qt_Embalagem'] = ($prod['Qt_Embalagem'] > 0) ? $prod['Qt_Embalagem'] : 1;

                    $qtd = round($prod['Qt_Produto'] / $prod['Qt_Embalagem']);

                    $prods[] = [
                        "codigo" => $prod['codigo'],
                        "quantidade" => $qtd,
                        "preco" => ($prod['Vl_Preco_Produto'] * $prod['Qt_Embalagem']),
                        "cod_vol" => $this->getUnidVenda($prod['codigo'], $idForn),
                        'qtd_emb' => $this->getQtdEmb($prod['codigo'], $idForn)
                    ];

                }

                $post = [
                    "cod_oc" => $oc['Cd_Ordem_Compra'],
                    "data" => $oc['Dt_Ordem_Compra'],
                    "data_entrega" => date('d/m/Y', strtotime($oc['Dt_Previsao_Entrega'])),
                    "id_fornecedor" => $idForn,
                    "cnpj" => $oc['Cd_Comprador'],
                    "products" => $prods,
                    "id_forma_pagamento" => (isset($post['forma_pagto'])) ? $post['forma_pagto'] : $oc['Cd_Condicao_Pagamento'],
                    "usuario" => isset($post['usuario_resgate']) ? $post['usuario_resgate'] : $this->session->nome
                ];

                $ch = curl_init('https://pharmanexo.com.br/pharma_integra/OrdemCompra');
                $payload = json_encode($post);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);

                $result = json_decode($result, true);


                if (isset($result['type']) && $result['type'] == 'success') {

                    $pedidoConvidado = $this->db
                        ->where('id_fornecedor', $this->session->id_fornecedor)
                        ->where('ordem_compra', $oc['Cd_Ordem_Compra'])
                        ->get('conv_pedidos')->row_array();

                    // verifica se existem itens cancelado
                    $itensCancelados = $this->db
                        ->where('id_ordem_compra', $oc['id'])
                        ->where('situacao', 9)
                        ->get('ocs_sintese_produtos');


                    if (!empty($pedidoConvidado)) {
                        if ($itensCancelados->num_rows() > 0) {
                            $this->db
                                ->where('id', $pedidoConvidado['id'])
                                ->update('conv_pedidos', ['situacao' => 7]);
                        } else {
                            $this->db
                                ->where('id', $pedidoConvidado['id'])
                                ->update('conv_pedidos', ['situacao' => 7]);
                        }
                    }

                    # Registra resgate
                    $this->db->where("id", $oc['id']);
                    $this->db->update('ocs_sintese', [
                        'id_usuario_resgate' => $this->session->id_usuario,
                        'Status_OrdemCompra' => 4,
                        'data_resgate' => date("Y-m-d H:i:s")
                    ]);

                    # Registra resgate produtos
                    $this->db->where("id_ordem_compra", $oc['id']);
                    $this->db->update('ocs_sintese_produtos', [
                        'resgatado' => 1,
                        'data_resgate' => date("Y-m-d H:i:s")
                    ]);

                    # Enviao de espelho
                    $sendEmail = $this->sendEmail($oc['id']);

                    $route = base_url("fornecedor/ordens_compra/resgatadas/espelho/{$oc['id']}");

                    $output = ['type' => 'success', 'message' => 'Resgate efetuado com sucesso', 'route' => $route];

                    switch (intval($oc['integrador'])) {
                        case 1:
                            #resgate sintese
                            $this->resgateSintese(preg_replace('/[^\d\-]/', '', $oc['Cd_Fornecedor']), $oc['Cd_Ordem_Compra']);
                            break;
                        case 2:
                            $resg = [];
                            break;
                        case 3:
                            #resgate sintese
                            $this->resgateApoio($oc['id_fornecedor'], $oc['Cd_Ordem_Compra']);
                            break;
                    }


                } else {

                    $output = ['type' => 'warning', 'message' => $result['message']];
                }
            } else {
                $output = ['type' => 'warning', 'message' => 'Nenhum item pendente de resgate, verifique com o suporte'];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }

    }

    /**
     * Envia email com o espelho da OC para os emails registrados para o comprador
     *
     * @param - INT ID da ordem de compra
     * @return  bool
     */
    public function sendEmail($id_oc)
    {
        # Obtem os dados para o espelho (oc, ofertas, comprador) e do fornecedor
        $data['ordem_compra'] = $this->getOC($id_oc);


        $data['fornecedor'] = $this->fornecedor->findById($this->session->id_fornecedor);

        # Cria o espelho
        $mirror = $this->load->view("{$this->mirrorView}/mirror_oc", $data, true);

        $cliente = $data['ordem_compra']['comprador'];

        # envio de email para consultores, gerentes...
        $emails = $this->db->where('id_cliente', $cliente['id'])
            ->where('id_fornecedor', $this->session->id_fornecedor)
            ->get('email_notificacao')
            ->row_array();

        $destinatarios = [];

        if (isset($this->session->email) && !empty($this->session->email))
            array_push($destinatarios, $this->session->email);

        if (isset($emails) && !empty($emails) && $this->config->item('db_config')['wb_oncoprod'] != 'teste') {

            if (isset($emails['gerente']) && !empty($emails['gerente']))
                array_push($destinatarios, $emails['gerente']);

            if (isset($emails['consultor']) && !empty($emails['consultor']))
                array_push($destinatarios, $emails['consultor']);

            if (isset($emails['geral']) && !empty($emails['geral']))
                array_push($destinatarios, $emails['geral']);

            if (isset($emails['grupo']) && !empty($emails['grupo']))
                array_push($destinatarios, $emails['grupo']);
        } else {
            $destinatarios = ['marlon.boecker@pharmanexo.com.br'];
        }

        if (!empty($destinatarios)) {

            $destinatarios = implode($destinatarios, ', ');

            $nome_hospital = (!empty($cliente['nome_fantasia'])) ? $cliente['nome_fantasia'] : $cliente['razao_social'];

            //assunto
            $assunto = "PHARMANEXO | Pedido {$data['ordem_compra']['Cd_Ordem_Compra']} - {$nome_hospital}";

            if (isset($data['ordem_compra']['integrador'])) {
                $integrador = $this->db->where('id', $data['ordem_compra']['integrador'])->get('integradores')->row_array();
                if (!empty($integrador)){
                    $assunto = "PEDIDO {$integrador['desc']} {$data['ordem_compra']['Cd_Ordem_Compra']} - {$nome_hospital}";
                }
            }

            # notificar por e-mail
            $notificar = [
                "to" => $destinatarios,
                "greeting" => "",
                "subject" => $assunto,
                "message" => $mirror,
                "oncoprod" => 1
            ];

            $enviarEmail = $this->notify->send($notificar);

            return true;
        }

        return false;
    }

    /**
     * Obtem os registros para o datatable de ocs
     *
     * @return json
     */
    public function to_datatable()
    {
        $r = $this->datatable->exec(
            $this->input->post(),
            'ocs_sintese',
            [
                ['db' => 'ocs_sintese.id', 'dt' => 'id'],
                ['db' => 'ocs_sintese.prioridade', 'dt' => 'prioridade'],
                ['db' => 'ocs_sintese.Cd_Ordem_Compra', 'dt' => 'Cd_Ordem_Compra'],
                ['db' => 'ocs_sintese.Cd_Cotacao', 'dt' => 'Cd_Cotacao'],
                ['db' => 'ocs_sintese.Hr_Ordem_Compra', 'dt' => 'Hr_Ordem_Compra'],
                ['db' => 'ocs_sintese.Dt_Ordem_Compra', 'dt' => 'Dt_Ordem_Compra'],
                ['db' => 'ocs_sintese.Dt_Ordem_Compra', 'dt' => 'data', 'formatter' => function ($d, $r) {
                    return date('d/m/Y H:i:s', strtotime("{$d} {$r['Hr_Ordem_Compra']}"));
                }],

                ['db' => 'ocs_sintese.Dt_Previsao_Entrega', 'dt' => 'Dt_Previsao_Entrega', 'formatter' => function ($d) {
                    return date("d/m/Y", strtotime($d));
                }],
                ['db' => 'compradores.razao_social', 'dt' => 'razao_social'],
                ['db' => '(select sum(Qt_Produto * Vl_Preco_Produto) from ocs_sintese_produtos where id_ordem_compra = ocs_sintese.id and resgatado = 0)', 'dt' => 'valor', 'formatter' => function ($d) {
                    return number_format($d, 4, ',', '.');
                }],
                ['db' => 'compradores.id', 'dt' => 'id_cliente'],
                ['db' => 'compradores.estado', 'dt' => 'estado'],
                ['db' => 'ocs_sintese.integrador', 'dt' => 'id_integrador'],
            ],
            [
                ['compradores', 'compradores.id = ocs_sintese.id_comprador'],
            ],
            'ocs_sintese.pendente = 1 and id_fornecedor = ' . $this->session->id_fornecedor
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($r));
    }

    /**
     * obtem o array de uma oc
     *
     * @param string numero da oc
     * @return array
     */
    private function getOC($idOC)
    {
        $oc = $this->oc->findById($idOC);

        $oferta = $this->db->select("prazo_entrega, id_forma_pagamento, valor_minimo, id_usuario")
            ->where('id_fornecedor', $oc['id_fornecedor'])
            ->where('Cd_Cotacao', $oc['Cd_Cotacao'])
            ->get('cotacoes_produtos')
            ->row_array();

        $oferta['condicao_pagto'] = $this->db->where('id', $oferta['id_forma_pagamento'])->get('formas_pagamento')->row_array()['descricao'];

        $oc['oferta'] = $oferta;

        $situacao = $this->db
            ->where('codigo', $oc['Status_OrdemCompra'])->get('ocs_sintese_status')->row_array();

        if (!empty($situacao)) {

            switch ($situacao['codigo']) {
                case '4':

                    if (isset($oc['Dt_Resgate']) && !empty($oc['Dt_Resgate'])) {
                        $oc['situacao'] = 'Resgatada pelo fornecedor';
                    } else if (strtotime($oc['Dt_Previsao_Entrega']) > time()) {
                        $oc['situacao'] = 'Aguardando Entrega em ' . date('d/m/Y', strtotime($oc['Dt_Previsao_Entrega']));
                    } else if (strtotime($oc['Dt_Previsao_Entrega']) < time()) {
                        $oc['situacao'] = 'Entregue';
                    }

                    break;
                default:
                    $oc['situacao'] = $situacao['descricao'];
                    break;
            }
        }


        $oc['produtos'] = $this->oc->get_products($idOC);

        $oc['comprador'] = (isset($oc['id_comprador']) && !empty($oc['id_comprador'])) ? $this->comp->findById($oc['id_comprador']) : 'Comprador não localizado';
        if (isset($oc['Cd_Condicao_Pagamento']) && !empty($oc['Cd_Condicao_Pagamento'])) {
            $id = $oc['Cd_Condicao_Pagamento'];
            $select = $this->db->where('id', $id)->get('formas_pagamento')->row_array();

            if (!empty($select)) {
                $oc['form_pagamento'] = $select['descricao'];
            } else {
                $oc['form_pagamento'] = $id;
            }

        }

        # Flag para identificar se a OC tem algum produto sem codigo
        $hasNoCode = 0;
        $total = 0.00;
        foreach ($oc['produtos'] as $kk => $row) {


            $total = $total + (intval($row['Qt_Produto']) * $row['Vl_Preco_Produto']);

            if (empty($row['codigo'])) {

                $this->db->select("id_pfv AS codigo, obs_produto");
                $this->db->where('cd_cotacao', $oc['Cd_Cotacao']);
                $this->db->where('id_fornecedor', $oc['id_fornecedor']);
                $this->db->where('preco_marca', $row['Vl_Preco_Produto']);
                $this->db->group_start();
                $this->db->where("cd_produto_comprador = '{$row['Cd_Produto_Comprador']}' ");
                $this->db->where('id_produto', $row['Id_Produto_Sintese']);
                $this->db->or_group_start();
                $this->db->where('id_produto', $row['Id_Produto_Sintese']);
                $this->db->group_end();
                $this->db->group_end();

                $item = $this->db->get('cotacoes_produtos')->row_array();

                if (isset($item) && !empty($item)) {

                    # Atualiza o codigo do produto
                    $this->db->where('id_ordem_compra', $row['id_ordem_compra']);
                    $this->db->where("cd_produto_comprador = '{$row['Cd_Produto_Comprador']}' ");
                    $this->db->where('Id_Produto_Sintese', $row['Id_Produto_Sintese']);
                    $this->db->where('Id_Sintese', $row['Id_Sintese']);
                    $this->db->where("codigo is null");
                    $this->db->update('ocs_sintese_produtos', ['codigo' => $item['codigo']]);

                    $oc['produtos'][$kk]['codigo'] = $item['codigo'];
                    $oc['produtos'][$kk]['obs_cot_produto'] = $item['obs_produto'];
                } else {

                    $hasNoCode = 1;
                }
            }

            if (!empty($oc['produtos'][$kk]['codigo'])) {

                $codigo = $oc['produtos'][$kk]['codigo'];

            } else if (!empty($row['codigo'])) {
                $codigo = $row['codigo'];
            }


            if (!empty($codigo)) {
                $prod = $this->db->select('nome_comercial, apresentacao')
                    ->where('id_fornecedor', $oc['id_fornecedor'])
                    ->where('codigo', $codigo)
                    ->get('produtos_catalogo')
                    ->row_array();
                $oc['produtos'][$kk]['produto_catalogo'] = "{$prod['nome_comercial']}";
            }
        }


        $oc['total'] = $total;

        if (isset($oc['Telefones_Ordem_Compra']) && !empty($oc['Telefones_Ordem_Compra'])) {
            $oc['Telefones_Ordem_Compra'] = json_decode($oc['Telefones_Ordem_Compra'], true);
        }

        $oc['hasNoCode'] = $hasNoCode;

        return $oc;
    }

    /**
     * Gera um arquivo Excel com todos os registros do datatable
     *
     * @return file
     */
    public function export()
    {
        $this->db->select("ocs.id");
        $this->db->select("ocs.Dt_Ordem_Compra");
        $this->db->select("ocs.Hr_Ordem_Compra");
        $this->db->select("ocs.Cd_Ordem_Compra");
        $this->db->select("c.razao_social AS empresa");
        $this->db->select("ocs.Dt_Previsao_Entrega");
        $this->db->select("ocs.Cd_Cotacao");
        $this->db->from("ocs_sintese ocs");
        $this->db->join("compradores c", "c.id = ocs.id_comprador");
        $this->db->where("ocs.pendente", 1);
        $this->db->where("ocs.id_fornecedor", $this->session->id_fornecedor);
        $this->db->order_by("Dt_Ordem_Compra DESC");

        $query = $this->db->get()->result_array();

        if (count($query) < 1) {

            $query[] = [
                'data_criacao' => '',
                'ordem_compra' => '',
                'empresa' => '',
                'valor' => '',
                'entrega_acordada' => '',
                'cotacao' => ''
            ];
        } else {

            $data = [];

            foreach ($query as $kk => $row) {

                $this->db->select("sum(Qt_Produto * Vl_Preco_Produto) as value");
                $this->db->where("id_ordem_compra", $row['id']);
                $valor = $this->db->get('ocs_sintese_produtos')->row_array();

                $data[] = [
                    'data_criacao' => date('d/m/Y H:i:s', strtotime("{$row['Dt_Ordem_Compra']} {$row['Hr_Ordem_Compra']}")),
                    'ordem_compra' => $row['Cd_Ordem_Compra'],
                    'empresa' => $row['empresa'],
                    'valor' => number_format($valor['value'], 4, ',', '.'),
                    'entrega_acordada' => date("d/m/Y", strtotime($row['Dt_Previsao_Entrega'])),
                    'cotacao' => $row['Cd_Cotacao']
                ];
            }

            $query = $data;
        }

        $dados_page = ['dados' => $query, 'titulo' => 'ordens_compra'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    /**
     * Gera um arquivo Excel com todos os registros do datatable de detalhes das oc
     *
     * @return file
     */
    public function export_details($idOC)
    {
        $oc = $this->getOC($idOC);

        $query = [];

        foreach ($oc['produtos'] as $kk => $produto) {

            $query[] = [
                'codigo' => $produto['codigo'],
                'produto' => $produto['Ds_Produto_Comprador'],
                'marca' => $produto['Ds_Marca'],
                'unidade' => $produto['Ds_Unidade_Compra'],
                'qtd_embalagem' => $produto['Qt_Embalagem'],
                'qtd_produto' => $produto['Qt_Produto'],
                'preco' => number_format($produto['Vl_Preco_Produto'], 4, ',', '.')
            ];
        }

        if (count($query) < 1) {

            $query[] = [
                'codigo' => '',
                'produto' => '',
                'marca' => '',
                'unidade' => '',
                'qtd_embalagem' => '',
                'qtd_produto' => '',
                'preco' => ''
            ];
        }

        $dados_page = ['dados' => $query, 'titulo' => 'produtos'];

        $exportar = $this->export->excel("planilha.xlsx", $dados_page);

        if ($exportar['status'] == false) {

            $warning = ['type' => 'warning', 'message' => $exportar['message']];
        } else {

            $warning = ['type' => 'success', 'message' => 'Planilha exportada com sucesso!'];
        }

        $this->session->set_userdata('warning', $warning);

        redirect($this->route);
    }

    /**
     * obtem a unidade de um produto no catalogo
     *
     * @param INT codigo do produto
     * @param INT Id do fornecedor
     * @return string
     */
    private function getUnidVenda($codigo, $id_fornecedor)
    {
        $con = $this->db->select('unidade')->where("codigo = {$codigo} and id_fornecedor = {$id_fornecedor}")->get('produtos_catalogo')->row_array();

        return $con['unidade'];
    }

    /**
     * obtem a quantidade da embalagem de um produto no catalogo
     *
     * @param INT codigo do produto
     * @param INT Id do fornecedor
     * @return string
     */
    private function getQtdEmb($codigo, $id_fornecedor)
    {
        $con = $this->db->select('quantidade_unidade')->where("codigo = {$codigo} and id_fornecedor = {$id_fornecedor}")->get('produtos_catalogo')->row_array();

        return $con['quantidade_unidade'];
    }

    /**
     * Obtem as formas de pagamento via sintese
     *
     * @param - String CNPJ do comprador
     * @return  array
     */
    public function getFormaPagamento($cnpj)
    {

        # URL para onde será enviada a requisição GET
        $url = $this->urlOncoprod;

        $client = new SoapClient($url);

        $function = 'RetornarCondicoesDePagamento';
        $arguments = array('RetornarCondicoesDePagamento' => array('Cnpj' => preg_replace("/\D+/", "", $cnpj),));

        $result = $client->__soapCall($function, $arguments);

        $formas_pagto = [];

        $result = json_encode($result);
        $xml = json_decode($result, true);

        if (isset($xml['RetornarCondicoesDePagamentoResult']['CondicaoPagamento'])) {

            $array = $xml['RetornarCondicoesDePagamentoResult']['CondicaoPagamento'];

            if (!isset($array[0])) {

                $aux = $array;
                unset($array);
                $array[0] = $aux;
            }
            foreach ($array as $row) {

                $formas_pagto[] = [
                    'id' => $row['condicaoPagamentoId'],
                    'value' => $row['condicaoPagamentoDescricao'],
                ];
            }
        }

        return $formas_pagto;
    }

    public function getFormaPagamentoId($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('formas_pagamento')->row_array();
    }

    /**
     * Atualiza os registros das ocs como não pendente
     *
     * @return json
     */
    public function changeStatusPendingAll()
    {
        if ($this->input->method() == 'post') {
            $post = $this->input->post();

            $this->db->trans_begin();

            if (!isset($post['el'])) {

                $output = ['type' => 'warning', 'message' => 'Nenhum registro selecionado'];

                $this->output->set_content_type('application/json')->set_output(json_encode($output));
                return;
            }

            foreach ($post['el'] as $id) {

                # Registra resgate
                $this->db->where("id", $id);
                $this->db->where('id_fornecedor', $this->session->id_fornecedor);
                $this->db->update('ocs_sintese', [
                    'pendente' => 0,
                    'Status_OrdemCompra' => 4,
                    'id_usuario_resgate' => $this->session->id_usuario,
                    'data_resgate' => date("Y-m-d H:i:s")
                ]);

                # Registra resgate
                $this->db->where("id_ordem_compra", $id);
                $this->db->update('ocs_sintese_produtos', [
                    'resgatado' => 1,
                    'data_resgate' => date("Y-m-d H:i:s")
                ]);

            }

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();

                $output = ['type' => 'warning', 'message' => notify_failed];
            } else {

                $this->db->trans_commit();

                $output = ['type' => 'success', 'message' => notify_update];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    /**
     * Atualiza os registros das ocs como não pendente
     *
     * @return json
     */
    public function changeStatusPending($id)
    {
        if ($this->input->method() == 'post') {

            $post = $this->input->post();

            $this->db->where('id', $id);
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
            $updt = $this->db->update('ocs_sintese', ['pendente' => 0]);

            if ($updt) {

                $output = ['type' => 'success', 'message' => notify_update];
            } else {

                $output = ['type' => 'warning', 'message' => notify_failed];
            }

            $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function resgateSintese($cnpj, $oc)
    {

        $client = new SoapClient("{$this->urlCliente_sintese['principal']}?wsdl");

        $params = array(
            "cnpjFornecedor" => $cnpj,
            "codigoOCSintese" => $oc,
            "codigoUsuarioERP" => "PHARMAINT321"
        );

        $response = $client->__soapCall("InformarResgateOrdemCompra", array($params));

        return true;
    }

    private function resgateApoio($idFornecedor, $oc)
    {
        $forn = $this->db->where('id', $idFornecedor)->get('fornecedores')->row_array();
        $credencial_apoio = json_decode($forn['credencial_apoio'], true);

        if (!empty($credencial_apoio)) {
            #producao
            $wsdl = "https://ws.apoiocotacoes.com.br/app/fornecedores/WSFornecedores?wsdl";

            /* #homolog
             $wsdl = "http://ws.homologacao.apoiocotacoes.com.br/app/fornecedores/WSFornecedores?WSDL";*/

            $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://cotacao.fornecedores.client.webService.apoio.com.br/">
                               <soapenv:Header/>
                               <soapenv:Body>
                                  <web:service>
                                     <usuario>' . $credencial_apoio['login'] . '</usuario>
                                         <senha>' . $credencial_apoio['password'] . '</senha>
                                         <operacao>WHR</operacao>
                                         <parametros></parametros>
                                          <xml>
                                            <Id_Pdc>' . $oc . '</Id_Pdc>
                                            <Status_Resgate_Pedido>A</Status_Resgate_Pedido>
                                          </xml>
                                  </web:service>
                               </soapenv:Body>
                            </soapenv:Envelope>';

            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "Content-length: " . strlen($xml_post_string),
            ); //SOAPAction: your op URL


            $url = str_replace("?wsdl", "", $wsdl);


            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // converting
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }

            curl_close($ch);


            if (isset($error_msg)) {
                return false;
            } else {
                return true;
            }

        }

    }

}
