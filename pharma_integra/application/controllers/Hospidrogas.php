<?php

class Hospidrogas extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */
    private $fornecedor = 20;

    private $arrayMovEstoque = [];
    private $arrayProdsCatalogo = [];
    private $arrayProdsLote = [];
    private $arrayProdsPrecoUF = [];
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

            $d = explode('T', $produto["VALIDADE"]);
            $dataValidade = $d[0];


            $this->arrayMovEstoque[] = [
                "id_fornecedor" => $this->fornecedor,
                "produto" => $produto['DESCRICAO'],
                "nome_comercial" => $produto['NOME_COMERCIAL'],
                "codigo" => $produto['CODIGO'],
                "apresentacao" => $produto['APRESENTACAO'],
                "quantidade" => $produto['ESTOQUE'],
                "unidade" => $produto['UNIDADE'],
                "marca" => $produto['MARCA'],
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "qtd_unidade" => 1,
                "lote" => $produto['LOTE'],
                "validade" => $dataValidade,
            ];

            $this->arrayProdsCatalogo[] = [
                "codigo" => intval($produto['CODIGO']),
                "rms" => (empty($produto['RMS'])) ? NULL : $produto['RMS'],
                "apresentacao" => $produto['APRESENTACAO'],
                "marca" => $produto['MARCA'],
                "descricao" => $produto['DESCRICAO'],
                "unidade" => $produto['UNIDADE'],
                "quantidade_unidade" => intval(1),
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

            $est = $produto['ESTOQUE'];



            $this->arrayProdsLote[] = [
                "lote" => $produto['LOTE'],
                "local" => NULL,
                "codigo" => intval($produto['CODIGO']),
                "id_fornecedor" => $this->fornecedor,
                "estoque" => ($est > 0) ? intval($produto['ESTOQUE']) : 0,
                "validade" => $dataValidade
            ];

            $this->arrayProdsPrecoBR[] = [
                "codigo" => intval($produto['CODIGO']),
                "id_fornecedor" => $this->fornecedor,
                "id_estado" => NULL,
                "preco_unitario" => number_format(floatval(strtr($produto['PRECO_ES'], ',', '.')), 4, '.', '')
            ];



            foreach ($produto as $k => $prod)
            {
                if (strpos($k, 'PRECO') !== FALSE){

                    $ex = explode('_',$k);

                    $this->arrayProdsPrecoUF[] = [
                        "codigo" => intval($produto['CODIGO']),
                        "id_fornecedor" => $this->fornecedor,
                        "id_estado" => getEstado($ex[1])['id'],
                        "preco_unitario" => number_format(floatval(strtr($produto[$k], ',', '.')), 4, '.', '')
                    ];

                }
            }

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
       // $post = file_get_contents('hospidrogas.json');

        $f = fopen('hospidrogas.json', 'w+');
        fwrite($f, $post);
        fclose($f);

        $produtos = json_decode($post, true);


        /**
         * Chama a Função para criar os arrays com os dados dos produtos.
         */
        $this->mountArraysProds($produtos);

        $arrayMovEstoque = $this->arrayMovEstoque;
        $arrayProdsLote = multi_unique($this->arrayProdsLote);
        $arrayProdsPrecoUF = multi_unique($this->arrayProdsPrecoUF);
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
            if ($this->Engine->checkLot($prodLot) && $prodLot['estoque'] > 0){
                $t = $this->db->insert('produtos_lote', $prodLot);
            }

        }


        /**
         * Para cada preco BR, verifica se já existe no Banco de dados e se não é zerado.
         * Insere o preço na tabela de produtos preço.
         */
       /* foreach ($arrayProdsPrecoBR as $prodPrecBR) {

            if ($this->Engine->checkPrice($prodPrecBR) && !(floatval($prodPrecBR['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco', $prodPrecBR);
            }
        }*/

        foreach ($arrayProdsPrecoUF as $prodPrecUF) {

            if ($this->Engine->checkPrice($prodPrecUF) && !(floatval($prodPrecUF['preco_unitario']) == 0)) {
                $this->db->insert('produtos_preco', $prodPrecUF);
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
