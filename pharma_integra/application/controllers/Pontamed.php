<?php

class Pontamed extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */
    private $fornecedor = 5018;

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


            $dataValidade = dbDateFormat($produto["DATA_VENCIMENTO_LOTE"]);
            $codigo = intval(preg_replace('/[^0-9]/', '', $produto['CODIGO_ITEM']));

            $this->arrayMovEstoque[] = [
                "id_fornecedor" => $this->fornecedor,
                "nome_comercial" => $produto['NOME_ITEM'],
                "codigo" => $codigo,
                "apresentacao" => $produto['NOME_ITEM_COMERCIAL'],
                "quantidade" => $produto['QUANTIDADE_DISP_LOTE'],
                "unidade" => $produto['UNIDADE_ITEM'],
                "marca" => $produto['FABRICANTE_ITEM'],
                "rms" => (empty($produto['REGISTRO_ANVISA'])) ? NULL : $produto['REGISTRO_ANVISA'],
                "qtd_unidade" => 1,
                "lote" => $produto['LOTE'],
                "validade" => $dataValidade,
                'ean' => $produto['CODIGO_BARRAS_ITEM']
            ];

            $this->arrayProdsCatalogo[] = [
                "codigo" => $codigo,
                "codigo_externo" => $produto['CODIGO_ITEM'],
                "rms" => (empty($produto['REGISTRO_ANVISA'])) ? NULL : $produto['REGISTRO_ANVISA'],
                "apresentacao" => '',
                "marca" => $produto['FABRICANTE_ITEM'],
                "unidade" => $produto['UNIDADE_ITEM'],
                "quantidade_unidade" => $produto['CAIXA_UNIDADES'],
                "nome_comercial" => $produto['NOME_ITEM'] . " ". $produto['NOME_ITEM_COMERCIAL'],
                "id_fornecedor" => $this->fornecedor,
                "ativo" => 1,
                "bloqueado" => 0,
                'ean' => $produto['CODIGO_BARRAS_ITEM']
            ];

            $id_marca = $this->Engine->checkIdMarca(array("marca" => $produto['FABRICANTE_ITEM'], "id_fornecedor" => $this->fornecedor));

            if ($id_marca != 0) {

                $this->arrayIdsMarca[] = [
                    "codigo" => intval($codigo),
                    "id_marca" => $id_marca
                ];
            }

            $est = $produto['QUANTIDADE_DISP_LOTE'];


            $this->arrayProdsLote[] = [
                "lote" => $produto['LOTE'],
                "local" => NULL,
                "codigo" => intval($codigo),
                "id_fornecedor" => $this->fornecedor,
                "estoque" => ($est > 0) ? (intval($est)/intval($produto['CAIXA_UNIDADES'])) : 0,
                "validade" => $dataValidade
            ];

            foreach ($produto as $k => $item) {
                if (strpos($k, "PRECO_") !== false) {
                    $this->arrayProdsPrecoUF[] = [
                        "codigo" => intval($codigo),
                        "id_fornecedor" => $this->fornecedor,
                        "id_estado" => preg_replace('/[^\d\-]/', '', $k),
                        "preco_unitario" => number_format(floatval(strtr($item, ',', '.')), 4, '.', '')
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
        /*  if (!$this->Engine->start('BEGIN', $fornecedor, time()))
              exit();*/

        $post = file_get_contents("php://input");
        //  $post = file_get_contents('pontamed_novo.json');

        $f = fopen('pontamed_novo.json', 'w+');
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
        //   $this->db->insert_batch('movimentacao_estoque', $arrayMovEstoque);

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
            if ($this->Engine->checkLot($prodLot) && $prodLot['estoque'] > 0) {
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

            $this->db->where('id_fornecedor', $fornecedor)->delete('produtos_precos');
            $this->db->where('id_fornecedor', $fornecedor)->delete('produtos_precos_max');

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
