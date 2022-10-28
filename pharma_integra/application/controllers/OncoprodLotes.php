<?php

class OncoprodLotes extends MY_Controller
{
    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $arrayMovEstoque = [];
    private $arrayProdsCatalogo = [];
    private $arrayProdsLote = [];
    private $arrayIdsMarca = [];
    private $urlClient;
    private $fornecedor = [12, 111, 112, 115, 120, 123, 126];

    public function __construct()
    {
        parent::__construct();

        /**
         * Dados conexão SOAP Oncoprod
         */
        $this->urlClient = 'http://oncoweb.oncoprod.com.br/KraftSalesIntegradorPharmaNexo/IntegradorPharmanexo.svc?wsdl';

        $this->load->model('Engine');
    }

    private function mountArraysProds($produtos)
    {

        /**
         * Varre o arquivo JSON e monta os Objetos dos produtos.
         * Movimentação de Estoque;
         * Catalogo;
         * Estoque.
         */

        foreach ($produtos as $produto) {

            $fornecedor_id = intval("1" . $produto['Loja']);

            $this->arrayMovEstoque[] = [
                "id_fornecedor" => $fornecedor_id,
                "produto" => $produto['Produto'],
                "nome_comercial" => $produto['NomeComercial'],
                "codigo" => $produto['ItemId'],
                "apresentacao" => $produto['Apresentacao'],
                "quantidade" => intval($produto['QuantidadeLote']),
                "unidade" => $produto['unidadeMedida'],
                "marca" => $produto['Fabricante'],
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "qtd_unidade" => 1,
                "lote" => $produto['Lote'],
                "validade" => dateFormat($produto["Validade"], "Y-m-d")
            ];

            $this->arrayProdsCatalogo[] = [
                "codigo" => intval($produto['ItemId']),
                "descricao" => $produto['Produto'],
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "apresentacao" => $produto['Apresentacao'],
                "marca" => $produto['Fabricante'],
                "unidade" => $produto['unidadeMedida'],
                "nome_comercial" => $produto['NomeComercial'],
                "id_fornecedor" => $fornecedor_id,
                "ativo" => 1,
                "bloqueado" => 0
            ];

            $id_marca = $this->Engine->checkIdMarca(array("marca" => $produto['Fabricante'], "id_fornecedor" => $fornecedor_id));

            if ($id_marca != 0) {

                $this->arrayIdsMarca[] = [
                    "codigo" => intval($produto['ItemId']),
                    "id_marca" => $id_marca
                ];
            }

            $quantidade = 0;

            if (!intval($produto['Quantidade'] <= 0)) {
                $quantidade = intval($produto['QuantidadeLote']);
            }

            $this->arrayProdsLote[] = [
                "lote" => $produto['Lote'],
                "local" => NULL,
                "codigo" => intval($produto['ItemId']),
                "id_fornecedor" => $fornecedor_id,
                "estoque" => $quantidade,
                "validade" => dateFormat($produto["Validade"], "Y-m-d")
            ];
        }
    }

    protected function index_get()
    {

        /**
         * Verifica se o fornecedor faz parte da rotina de integração.
         * Inicia a contagem do tempo da Rotina.
         */
        if (!$this->Engine->startIn('BEGIN', $this->fornecedor, time()))
            exit();

        $client = new SoapClient($this->urlClient);

        $result = $client->__soapCall('RetornarSaldo', array(), NULL);

        $stdClass = json_decode(json_encode($result), true)['RetornarSaldoResult'];

        $produtos = json_decode($stdClass, true)['itens'];

        /**
         * Chama a Função para criar os arrays com os dados dos produtos.
         */
        $this->mountArraysProds($produtos);

        $arrayMovEstoque = $this->arrayMovEstoque;
        $arrayProdsCatalogo = multi_unique($this->arrayProdsCatalogo);
        $arrayProdsLote = multi_unique($this->arrayProdsLote);
        $arrayIdsMarca = multi_unique($this->arrayIdsMarca);


        if(empty($arrayMovEstoque)){
            exit();
        }

        //    $this->db->trans_start();

        /**
         * Insere todos os dados dos produtos na tabela de log.
         * Movimentação de Estoque
         */
        $this->db->insert_batch('movimentacao_estoque', $arrayMovEstoque);

        /**
         * Limpa toda a tabela de estoque de todas as OncoProds
         */
        $this->Engine->cleanStockIn($this->fornecedor);

        /**
         * Para cada lote, verifica se é maior que zero.
         * Para cada lote, verifica se já foi inserido.
         * Insere o lote no Banco de Dados.
         */
        foreach ($arrayProdsLote as $prodLot) { #MODIFICADO 29/07/2020 @chulesantos

            if (in_array($prodLot['id_fornecedor'], $this->fornecedor)) {
                if ($this->Engine->checkLot($prodLot) && intval($prodLot['estoque']) > 0) #MODIFICADO 29/07/2020 @chulesantos
                    $this->db->insert('produtos_lote', $prodLot);
            }
        }

        /**
         * Para cada produto, verifica se já está cadastrado no Catálogo.
         * Insere o produto no Banco de Dados se não estiver no catálogo.
         * Se o produto já for cadastrado, efetua um update no mesmo com a função activeCatalog.
         *
         * Foreach Interno para duplicar os produtos para todas Filiais.
         */
        foreach ($arrayProdsCatalogo as $prodCat) {

            if ($this->Engine->checkCatalog($prodCat)) {
                if (in_array($prodCat['id_fornecedor'], $this->fornecedor)) {

                    foreach ($this->fornecedor as $for) {

                        $prodCat['id_fornecedor'] = $for;

                        $this->db->insert('produtos_catalogo', $prodCat);

                    }
                }

            } else {

                if (in_array($prodCat['id_fornecedor'], $this->fornecedor))
                    $this->Engine->activeCatalog($prodCat);
            }
        }

        /**
         * Finaliza a contagem do tempo da Rotina.
         */
        $this->Engine->startIn('END', $this->fornecedor, time());

    }
}