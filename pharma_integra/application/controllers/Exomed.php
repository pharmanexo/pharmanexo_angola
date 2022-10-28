<?php

class Exomed extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */
    private $fornecedor = 180;

    private $arrayMovEstoque = [];
    private $arrayProdsCatalogo = [];
    private $arrayProdsLote = [];
    private $arrayProdsPrecoPE = [];
    private $arrayProdsPrecoBR = [];
    private $arrayIdsMarca = [];

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Engine');
    }

    private function mountArraysProds($produtos)
    {

        /**
         * Varre o arquivo JSON e monta os Objetos dos produtos.
         * Movimentação de Estoque;
         * Catalogo;
         * Estoque;
         * Preços BR;
         * Preços PE.
         */

        foreach ($produtos as $produto) {

            $this->arrayMovEstoque[] = [
                "id_fornecedor" => $this->fornecedor,
                "produto" => $produto['DESCRICAO'],
                "nome_comercial" => $produto['NOME_COMERCIAL'],
                "codigo" => $produto['CODIGO'],
                "apresentacao" => $produto['APRESENTACAO'],
                "quantidade" => $produto['QUANT_CAIXA'],
                "unidade" => $produto['UNIDADE'],
                "marca" => $produto['MARCA'],
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "qtd_unidade" => $produto['QUANTIDADE_UNIDADE'],
                "lote" => $produto['LOTE'],
                "validade" => dateFormat($produto["VENCIMENTO"], "Y-m-d"),
                "preco_unitario" => $produto['PE_UNITARIO'],
                "preco_unitario_outros_uf" => $produto['OUTROS_ESTADOS_UNITARIO'],
                "estado" => "PE"
            ];

            $this->arrayProdsCatalogo[] = [
                "codigo" => intval($produto['CODIGO']),
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "apresentacao" => $produto['APRESENTACAO'],
                "marca" => $produto['MARCA'],
                "descricao" => $produto['DESCRICAO'],
                "unidade" => $produto['UNIDADE'],
                "quantidade_unidade" => intval($produto['QUANTIDADE_UNIDADE']),
                "nome_comercial" => $produto['NOME_COMERCIAL'],
                "id_fornecedor" => $this->fornecedor,
                "ativo" => 1,
                "bloqueado" => 0
            ];

            $id_marca = $this->Engine->checkIdMarca(array("marca" => $produto['MARCA'], "id_fornecedor" => $this->fornecedor));

            if ($id_marca != 0) {

                $this->arrayIdsMarca[] = [
                    "codigo" => intval($produto['CODIGO']),
                    "id_marca" => $id_marca
                ];
            }

            $this->arrayProdsLote[] = [
                "lote" => $produto['LOTE'],
                "local" => NULL,
                "codigo" => intval($produto['CODIGO']),
                "id_fornecedor" => $this->fornecedor,
                "estoque" => intval($produto['QUANT_CAIXA']),
                "validade" => dateFormat($produto["VENCIMENTO"], "Y-m-d")
            ];

            $this->arrayProdsPrecoPE[] = [
                "codigo" => intval($produto['CODIGO']),
                "id_fornecedor" => $this->fornecedor,
                "id_estado" => 17,
                "preco_unitario" => number_format(floatval(strtr($produto['PE_UNITARIO'], ',', '.')), 4, '.', '')
            ];

            $this->arrayProdsPrecoBR[] = [
                "codigo" => intval($produto['CODIGO']),
                "id_fornecedor" => $this->fornecedor,
                "id_estado" => NULL,
                "preco_unitario" => number_format(floatval(strtr($produto['OUTROS_ESTADOS_UNITARIO'], ',', '.')), 4, '.', '')
            ];
        }
    }

    protected function index_post()
    {
        $fornecedor = $this->fornecedor;

        /**
         * Verifica se o fornecedor faz parte da rotina de integração.
         * Inicia a contagem do tempo da Rotina.
         */
        if (!$this->Engine->start('BEGIN', $fornecedor, time()))
            exit();

        $post = file_get_contents("php://input");
        //  $post = file_get_contents('Exomed.json');

        $f = fopen('exomed_novo.json', 'w+');
        fwrite($f, $post);
        fclose($f);

        $produtos = json_decode($post, true);


        /**
         * Chama a Função para criar os arrays com os dados dos produtos.
         */
        $this->mountArraysProds($produtos);

        $arrayMovEstoque = $this->arrayMovEstoque;
        $arrayProdsLote = multi_unique($this->arrayProdsLote);
        $arrayProdsPrecoPE = multi_unique($this->arrayProdsPrecoPE);
        $arrayProdsPrecoBR = multi_unique($this->arrayProdsPrecoBR);
        $arrayProdsCatalogo = multi_unique($this->arrayProdsCatalogo);
        $arrayIdsMarca = multi_unique($this->arrayIdsMarca);


        /**
         * Insere todos os dados dos produtos na tabela de log.
         * Movimentação de Estoque
         */
        $this->db->insert_batch('movimentacao_estoque', $arrayMovEstoque);

        /**
         * Limpa toda a tabela de estoque do fornecedor.
         */
        $this->Engine->cleanStock($fornecedor);

        /**
         * Para cada lote, verifica se é maior que zero.
         * Para cada lote, verifica se já foi inserido.
         * Insere o lote no Banco de Dados.
         */
        foreach ($arrayProdsLote as $prodLot) {

            if ($this->Engine->checkLot($prodLot) && $prodLot['estoque'] > 0)
                $this->db->insert('produtos_lote', $prodLot);
        }

        /**
         * Para cada preco PE, verifica se já existe no Banco de dados e se não é zerado.
         * Insere o preço na tabela de produtos preço.
         */
        foreach ($arrayProdsPrecoPE as $prodPrecPE) {

            if ($this->Engine->checkPrice($prodPrecPE) && !(floatval($prodPrecPE['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco', $prodPrecPE);
            }
        }

        /**
         * Para cada preco BR, verifica se já existe no Banco de dados e se não é zerado.
         * Insere o preço na tabela de produtos preço.
         */
        foreach ($arrayProdsPrecoBR as $prodPrecBR) {

            if ($this->Engine->checkPrice($prodPrecBR) && !(floatval($prodPrecBR['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco', $prodPrecBR);
            }
        }

        /**
         * Para cada produto, verifica se já está cadastrado no Catálogo.
         * Insere o produto no Banco de Dados se não estiver no catálogo.
         * Se o produto já for cadastrado, efetua um update no mesmo com a função activeCatalog.
         */
        foreach ($arrayProdsCatalogo as $prodCat) {

            if ($this->Engine->checkCatalog($prodCat)) {

                $this->db->insert('produtos_catalogo', $prodCat);

            } else {

                $this->Engine->activeCatalog($prodCat);
            }
        }

        /**
         * Finaliza a contagem do tempo da Rotina.
         */
        $this->Engine->start('END', $fornecedor, time(), NULL);
    }
}