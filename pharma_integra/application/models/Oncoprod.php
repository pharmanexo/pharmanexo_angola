<?php

class Oncoprod extends MY_Model
{
    /**
     * @author : Chule Cabral
     * Data: 27/11/2020
     */

    private $urlClient;

    public function __construct()
    {

        parent::__construct();


        if ($this->config->item('db_config')['wb_oncoprod'] == 'teste') {
            $this->urlClient = $this->config->item('db_config')['url_oncoprod_teste'];
        } else {
            $this->urlClient = $this->config->item('db_config')['url_oncoprod'];
        }


        #  $this->urlClient = $this->config->item('db_config')['url_oncoprod'];


        $this->load->model('Financeiro');

    }

    private function mountArrayProds($params)
    {
        $arr = [];

        foreach ($params['produtos'] as $produto) {

            $precoOferta = str_replace('.', ',', $produto['preco']);

            $arr[] =
                [
                    'estabelecimentoId' => intval($params['estabelecimento']),
                    'itemId' => intval($produto['codigo']),
                    'pedidoItemPrecoTabela' => $precoOferta,
                    'pedidoItemQuantidade' => intval($produto['quantidade'])
                ];
        }
        return $arr;
    }

    public function index_oncoprod($data)
    {

        $oc = $data['cod_oc'];


        $cd_comprador = preg_replace('/[^\d\-]/', '', $data['cnpj']);


        # ID resgatada da sintese
        $id_forma_pagamento = $data['id_forma_pagamento'];

        /**
         * Verifica se a OC já foi resgatada.
         */
        $verify_oc = $this->Financeiro->verifyOc($oc);

        if (!IS_NULL($verify_oc)) {

            $this->Financeiro->outPutOc('error', 'Houve um erro ao resgatar o pedido! Pedido ja resgatado.');

            exit();
        }

        $soapClient = new SoapClient($this->urlClient, ['trace' => true]);

        $verificaCliente = $soapClient->VerificaClienteExiste(['cnpj' => $cd_comprador]);

        $dadosCliente = json_decode(json_encode($verificaCliente), true);

        $tipoEndereco = NULL;

        if (!boolval($dadosCliente['VerificaClienteExisteResult']['ok'])) {

            $this->Financeiro->outPutOc('error', 'Cliente nao existe no Fornecedor, verifique o CNPJ!');

            exit();
        }

        $tipoEndereco = $dadosCliente['VerificaClienteExisteResult']['dadosCliente']['Enderecos']['EnderecoEntrada']['tipoEnderecoId'];

        $validaComprador = $soapClient->ValidaStatusCliente(['cnpj' => $cd_comprador]);

        $statusCliente = json_decode(json_encode($validaComprador), true);

        $checkStatusCliente = $statusCliente['ValidaStatusClienteResult']['Ok'];

        if (!$checkStatusCliente) {

            $this->Financeiro->outPutOc('error', 'Cliente com restricao no Fornecedor, verifique o CNPJ!');

            exit();
        }

        $itensPedido = $this->mountArrayProds([
            "estabelecimento" => substr($data['id_fornecedor'], 1),
            "produtos" => $data['products']
        ]);

        $arr =
            [
                'pedido' => [

                    'Pedido' =>
                        [
                            'PedidoEntrada' =>
                                [
                                    'ClienteCNPJ' => $cd_comprador,
                                    'PedidoNumeroClienteIntegracao' => $oc,
                                    //  'condicaoPagamentoId' => 35,
                                    'condicaoPagamentoId' => $id_forma_pagamento,
                                    'itens' => ["PedidoItemEntrada" => $itensPedido],
                                    'pedidoObservacoes' => 'PHN - ' . $oc,
                                    'pedidoObservacoesNotaFiscal' => 'PHN - ' . $oc,
                                    'sistemaEntrada' => 'pharmanexo',
                                    'tipoEnderecoId' => $tipoEndereco,
                                    'unidadeNegocioId' => 'PJ',
                                    'usuarioIdIntegracao' => $data['usuario']
                                ]
                        ]
                ]
            ];


        $incluirPedido = $soapClient->IncluirPedido($arr);

        $filename = $oc . "_" . $data['id_fornecedor'] . "_" . time() . ".xml";
        $file = $soapClient->__getLastRequest();
        $f = fopen($_SERVER['DOCUMENT_ROOT'] . '/pharma_integra/public/oc/oncoprod/' . $filename, 'w+');
        fwrite($f, $file);
        fclose($f);


        $resultIncluirPedido = json_decode(json_encode($incluirPedido), true);


        if (boolval($resultIncluirPedido['IncluirPedidoResult']['pedidos']['RetornoPedidoBody']['pedidoOk'])) {

            $pedidoId = $resultIncluirPedido['IncluirPedidoResult']
            ['pedidos']['RetornoPedidoBody']['pedidosOnco']['PedidoStatus']['pedidoId'];

            $this->db->where('Cd_Ordem_Compra', $oc)
                ->set('transaction_id', $pedidoId)
                ->set('pendente', 0)
                ->update('ocs_sintese');

            $this->Financeiro->outPutOc('success', 'Pedido resgatado com sucesso!', ['pedido' => $pedidoId]);
        } else {
            $this->Financeiro->outPutOc('error', $resultIncluirPedido['IncluirPedidoResult']['pedidos']['RetornoPedidoBody']['pedidoMensagem']);
        }
    }

}
