<?php

class Pedidos extends MY_Controller
{
    public function __construct()
    {
        parent:: __construct();
    }

    public function index()
    {
        /**
         * CAMPOS QUE PERMITEM BUSCA
         * data_atual (referente a data inicio)
         * data_limite
         * cd_ordem_compra
         * status_ordem_compra
         **/

        if ($this->input->method() == 'post') {

            $getDados = $this->input->post();
            $arrayWhere = [];

            $this->db->trans_begin();
            /** Valida erros das consultas no BD. Caso de erro faz um rollback automatico */

            if (isset($getDados['data_atual']) && isset($getDados['data_limite']) && isset($_SESSION['id_fornecedor'])) {

                if (isset($getDados['cd_ordem_compra'])) {
                    $arrayWhere['Cd_Ordem_Compra'] = $getDados['cd_ordem_compra'];
                }

                if (isset($getDados['status_ordemCompra'])) {
                    if ($getDados['status_ordemCompra'] == 1) {
                        $arrayWhere['pendente'] = 1;
                    }
                    $arrayWhere['Status_OrdemCompra'] = $getDados['status_ordemCompra'];
                }

                $arrayWhere['id_fornecedor'] = $_SESSION['id_fornecedor'];

                $dataAtualCru = date_create($getDados['data_atual']);
                $dataLimiteCru = date_create($getDados['data_limite']);

                $dataAtual = $dataAtualCru->format('Y-m-d');
                $dataLimite = $dataLimiteCru->format('Y-m-d');

                $diferenca = $dataAtualCru->diff($dataLimiteCru);


                if (intval($diferenca->days) <= 2) {

                    $this->db->select('oc.*, c.nome_fantasia, c.razao_social');
                    $this->db->from('ocs_sintese oc');
                    $this->db->join('compradores c', 'c.id = oc.id_comprador');


                    $this->db->where($arrayWhere);
                    $this->db->where("date(oc.Dt_Ordem_Compra) BETWEEN '{$dataLimite}' AND '{$dataAtual}'");

                    $ocsSinteseResult = $this->db->get()->result_array();

                    $headerArray = [];

                    foreach ($ocsSinteseResult as $header) {

                        //BUSCA COTAÇÃO DA ORDEM DE COMPRA, CASO RESPONDIDA NO PHARMANEXO
                        $cotacao = $this->db
                            ->select('id_forma_pagamento')
                            ->where('cd_cotacao', $header['Cd_Cotacao'])
                            ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                            ->where("id_forma_pagamento > 0")
                            ->group_by('id_forma_pagamento')
                            ->get('cotacoes_produtos')
                            ->row_array();

                        //BUSCA A FORMA DE PAGAMENTO OFERTADA NA COTAÇÃO
                        if (!empty($cotacao)) {
                            $formaPagamentoCotacao = $this->db
                                ->where('id', $cotacao['id_forma_pagamento'])
                                ->get('formas_pagamento')
                                ->row_array();
                            if (!empty($formaPagamentoCotacao)) {
                                $header['forma_pagamento_ofertada'] = $formaPagamentoCotacao['descricao'];
                            } else {
                                $header['forma_pagamento_ofertada'] = '';
                            }

                        }

                        // BUSCA A FORMA DE PAGAMENTO INFORMADA NA ORDEM DE COMPRA
                        $formaPagamentoOc = $this->db
                            ->where('id', $header['Cd_Condicao_Pagamento'])
                            ->get('formas_pagamento')
                            ->row_array();

                        if (!empty($formaPagamentoOc)) {
                            $header['forma_pagamento_pedido'] = $formaPagamentoOc['descricao'];
                        } else {
                            $header['forma_pagamento_pedido'] = $header['Cd_Condicao_Pagamento'];
                        }


                        // AJUSTA O CAMPO TELEFONE PARA MOSTRAR APENAS O NUMERO
                        if (!empty($header['Telefones_Ordem_Compra'])) {

                            $telefone = json_decode($header['Telefones_Ordem_Compra'], true);
                            $telefone = (isset($telefone['Telefone_Ordem_Compra'])) ? $telefone['Telefone_Ordem_Compra'] : $telefone;

                            $header['Telefones_Ordem_Compra'] = (isset($telefone['Nr_Telefone'])) ? $telefone['Nr_Telefone'] : $telefone;

                        }

                        // BUSCA A DESCRIÇÃO DO STATUS DA OC
                        $ocsStatusOrdemCompra = $this->db->where('codigo', $header['Status_OrdemCompra'])->get('ocs_sintese_status')->row_array();
                        $header['status_ordemCompra_descricao'] = $ocsStatusOrdemCompra['descricao'];

                        // BUSCA OS DADOS DO USUÁRIO RESPONSÁVEL PELO RESGATE DA OC
                        if (!empty($header['id_usuario_resgate'])) {
                            $header['usuario_resgate'] = $this->db->select('id, nome, usuario_externo, email')
                                ->where('id', $header['id_usuario_resgate'])->get('usuarios')->row_array();
                        }else{
                            $header['usuario_resgate'] = [];
                        }


                        //BUSCA QUAL INTEGRADOR DA OC
                        if (!empty($header['integrador'])) {
                            $header['integrador'] = $this->db->where('id', $header['integrador'])->get('integradores')->row_array()['desc'];
                        }else{
                            $header['integrador'] = [];
                        }


                        // BUSCA PRODUTOS DA OC
                        $ocsSinteseProdutoResult = $this->db->where('id_ordem_compra', $header['id'])->get('ocs_sintese_produtos')->result_array();

                        // AJUSTA CAMPOS DOS PRODUTOS DA OC
                        foreach ($ocsSinteseProdutoResult as $k => $produto) {
                            if (!empty($produto['programacao'])){
                                $ocsSinteseProdutoResult[$k]['programacao_entrega'] = json_decode($produto['programacao'], true);
                            }else{
                                $ocsSinteseProdutoResult[$k]['programacao_entrega'] = [];
                            }

                            if (!empty($produto['codigo'])){
                               $codigoExt = $this->db
                                    ->select('codigo_externo')
                                    ->where('codigo', $produto['codigo'])
                                    ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                                    ->get('produtos_catalogo')
                                    ->row_array();
                               if (!empty($codigoExt)){
                                   $ocsSinteseProdutoResult[$k]['codigo_externo'] = $codigoExt['codigo_externo'];
                               }
                            }


                            unset($ocsSinteseProdutoResult[$k]['programacao'], $ocsSinteseProdutoResult[$k]['Id_Marca'],
                                $ocsSinteseProdutoResult[$k]['Ds_Marca'],
                                $ocsSinteseProdutoResult[$k]['Id_Produto_Sintese'],
                                $ocsSinteseProdutoResult[$k]['Id_Sintese'],
                                $ocsSinteseProdutoResult[$k]['resgatado'],
                                $ocsSinteseProdutoResult[$k]['id_confirmacao'],
                                $ocsSinteseProdutoResult[$k]['data_resgate'],
                                $ocsSinteseProdutoResult[$k]['Cd_ProdutoERP']
                            );

                        }


                        $header['produtos'] = $ocsSinteseProdutoResult;
                        //REMOVE ESSES CAMPOS DA LISTAGEM

                        unset($header['Cd_Fornecedor'], $header['id_fornecedor'], $header['Tp_Situacao'], $header['Tp_Movimento'],
                            $header['nota'], $header['chave_nf'], $header['sequencia'],
                            $header['prioridade'],
                            $header['Cd_Condicao_Pagamento'],
                            $header['Tp_Situacao'],
                            $header['Tp_Frete'],
                            $header['id_comprador'],
                            $header['forma_pagamento'],
                            $header['sequencia'],
                            $header['prioridade'],
                            $header['transaction_id'],
                            $header['id_usuario_resgate'],
                            $header['Tp_Logradouro'],
                            $header['pendente'],
                            $header['Id_Unidade_Federativa']
                        );

                        $headerArray[] = $header;
                    }

                    if ($this->db->trans_status() === FALSE) {
                        $getError = $this->db->error();

                        $this->db->trans_rollback();

                        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                            [
                                'status' => 'error',
                                'message' => $getError['message']
                            ]
                        ));
                    } else {
                        $this->db->trans_commit();
                        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                            [
                                'status' => 'success',
                                'data' => $headerArray
                            ]
                        ));
                    }

                } else {
                    $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                        [
                            'status' => 'error',
                            'message' => 'O limite de busca é de 2 dias'
                        ]
                    ));
                }
            } else {
                $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                    [
                        'status' => 'error',
                        'message' => 'Informe a data inicial, data final e id do fornecedor'
                    ]
                ));
            }
        }
    }

    public function pedidoDetalhes()
    {
        if ($this->input->method() == 'post') {
            $getDados = $this->input->post();
            $arrayWhere = [];

            if (isset($getDados['cd_ordem_compra']) && isset($_SESSION['id_fornecedor'])) {
                $arrayWhere =
                    [
                        'id_fornecedor' => $_SESSION['id_fornecedor'],
                        'Cd_Ordem_Compra' => $getDados['cd_ordem_compra']
                    ];

                $this->db->select('oc.*, c.nome_fantasia, c.razao_social');
                $this->db->from('ocs_sintese oc');
                $this->db->join('compradores c', 'c.id = oc.id_comprador');

                $this->db->where($arrayWhere);

                $ocsSinteseResult = $this->db->get()->result_array();

                $headerArray = [];

                foreach ($ocsSinteseResult as $header) {
                    //BUSCA COTAÇÃO DA ORDEM DE COMPRA, CASO RESPONDIDA NO PHARMANEXO
                    $cotacao = $this->db
                        ->select('id_forma_pagamento')
                        ->where('cd_cotacao', $header['Cd_Cotacao'])
                        ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                        ->where("id_forma_pagamento > 0")
                        ->group_by('id_forma_pagamento')
                        ->get('cotacoes_produtos')
                        ->row_array();

                    //BUSCA A FORMA DE PAGAMENTO OFERTADA NA COTAÇÃO
                    if (!empty($cotacao)) {
                        $formaPagamentoCotacao = $this->db
                            ->where('id', $cotacao['id_forma_pagamento'])
                            ->get('formas_pagamento')
                            ->row_array();
                        if (!empty($formaPagamentoCotacao)) {
                            $header['forma_pagamento_ofertada'] = $formaPagamentoCotacao['descricao'];
                        } else {
                            $header['forma_pagamento_ofertada'] = '';
                        }

                    }

                    // BUSCA A FORMA DE PAGAMENTO INFORMADA NA ORDEM DE COMPRA
                    $formaPagamentoOc = $this->db
                        ->where('id', $header['Cd_Condicao_Pagamento'])
                        ->get('formas_pagamento')
                        ->row_array();

                    if (!empty($formaPagamentoOc)) {
                        $header['forma_pagamento_pedido'] = $formaPagamentoOc['descricao'];
                    } else {
                        $header['forma_pagamento_pedido'] = $header['Cd_Condicao_Pagamento'];
                    }


                    // AJUSTA O CAMPO TELEFONE PARA MOSTRAR APENAS O NUMERO
                    if (!empty($header['Telefones_Ordem_Compra'])) {

                        $telefone = json_decode($header['Telefones_Ordem_Compra'], true);
                        $telefone = (isset($telefone['Telefone_Ordem_Compra'])) ? $telefone['Telefone_Ordem_Compra'] : $telefone;

                        $header['Telefones_Ordem_Compra'] = (isset($telefone['Nr_Telefone'])) ? $telefone['Nr_Telefone'] : $telefone;

                    }

                    // BUSCA A DESCRIÇÃO DO STATUS DA OC
                    $ocsStatusOrdemCompra = $this->db->where('codigo', $header['Status_OrdemCompra'])->get('ocs_sintese_status')->row_array();
                    $header['status_ordemCompra_descricao'] = $ocsStatusOrdemCompra['descricao'];

                    // BUSCA OS DADOS DO USUÁRIO RESPONSÁVEL PELO RESGATE DA OC
                    if (!empty($header['id_usuario_resgate'])) {
                        $header['usuario_resgate'] = $this->db->select('id, nome, usuario_externo, email')->where('id', $header['id_usuario_resgate'])->get('usuarios')->row_array();
                    }else{
                        $header['usuario_resgate'] = [];
                    }


                    //BUSCA QUAL INTEGRADOR DA OC
                    if (!empty($header['integrador'])) {
                        $header['integrador'] = $this->db->where('id', $header['integrador'])->get('integradores')->row_array()['desc'];
                    }else{
                        $header['integrador'] = [];
                    }


                    // BUSCA PRODUTOS DA OC
                    $ocsSinteseProdutoResult = $this->db->where('id_ordem_compra', $header['id'])->get('ocs_sintese_produtos')->result_array();

                    // AJUSTA CAMPOS DOS PRODUTOS DA OC
                    foreach ($ocsSinteseProdutoResult as $k => $produto) {
                        if (!empty($produto['programacao'])){
                            $ocsSinteseProdutoResult[$k]['programacao_entrega'] = json_decode($produto['programacao'], true);
                        }else{
                            $ocsSinteseProdutoResult[$k]['programacao_entrega'] = [];
                        }

                        if (!empty($produto['codigo'])){
                            $codigoExt = $this->db
                                ->select('codigo_externo')
                                ->where('codigo', $produto['codigo'])
                                ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                                ->get('produtos_catalogo')
                                ->row_array();
                            if (!empty($codigoExt)){
                                $ocsSinteseProdutoResult[$k]['codigo_externo'] = $codigoExt['codigo_externo'];
                            }
                        }


                        unset($ocsSinteseProdutoResult[$k]['programacao'], $ocsSinteseProdutoResult[$k]['Id_Marca'],
                            $ocsSinteseProdutoResult[$k]['Ds_Marca'],
                            $ocsSinteseProdutoResult[$k]['Id_Produto_Sintese'],
                            $ocsSinteseProdutoResult[$k]['Id_Sintese'],
                            $ocsSinteseProdutoResult[$k]['resgatado'],
                            $ocsSinteseProdutoResult[$k]['id_confirmacao'],
                            $ocsSinteseProdutoResult[$k]['data_resgate'],
                            $ocsSinteseProdutoResult[$k]['Cd_ProdutoERP']
                        );

                    }


                    $header['produto'] = $ocsSinteseProdutoResult;


                    //REMOVE ESSES CAMPOS DA LISTAGEM
                    unset($header['Cd_Fornecedor'], $header['id_fornecedor'], $header['Tp_Situacao'], $header['Tp_Movimento'],
                        $header['nota'], $header['chave_nf'], $header['sequencia'],
                        $header['prioridade'],
                        $header['Cd_Condicao_Pagamento'],
                        $header['Tp_Situacao'],
                        $header['Tp_Frete'],
                        $header['id_comprador'],
                        $header['forma_pagamento'],
                        $header['sequencia'],
                        $header['prioridade'],
                        $header['transaction_id'],
                        $header['id_usuario_resgate'],
                        $header['Tp_Logradouro'],
                        $header['pendente'],
                        $header['Id_Unidade_Federativa']
                    );

                    $headerArray[] = $header;
                }

                $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                    [
                        'status' => 'success',
                        'data' => $headerArray
                    ]
                ));


            } else {
                $this->output->set_content_type('application/json')->set_status_header(400)->set_output(json_encode(
                    [
                        'status' => 'error',
                        'data' => 'Informe o cd_ordem_compra'
                    ]
                ));
            }
        }
    }

    public function atualizarPedido()
    {
        if ($this->input->method() == 'post') {
            $getDados = $this->input->post();

            if (isset($getDados['status']) && isset($getDados['idPedidoPharmanexo']) && isset($_SESSION['id_fornecedor'])) {

                $mudaStatus = ['Status_OrdemCompra' => $getDados['status']];

                if (isset($getDados['nota'])) {
                    $mudaStatus['nota'] = $getDados['nota'];
                }

                $this->db->where('id_fornecedor', $_SESSION['id_fornecedor']);
                $this->db->where('id', $getDados['idPedidoPharmanexo']);
                $result = $this->db->update('ocs_sintese', $mudaStatus);

                if ($result) {
                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                        [
                            'status' => 'success',
                            'message' => 'Status alterado com sucesso'
                        ]
                    ));
                } else {
                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                        [
                            'status' => 'error',
                            'message' => 'Erro ao alterar status'
                        ]
                    ));
                }
            }
        }
    }
}


