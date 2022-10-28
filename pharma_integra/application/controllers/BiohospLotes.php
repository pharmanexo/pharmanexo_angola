<?php

class BiohospLotes extends MY_Controller_Auth
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $fornecedor = 104;

    private $arrayMovEstoque = [];
    private $arrayProdsCatalogo = [];
    private $arrayProdsLote = [];

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
         * Estoque.
         */

        foreach ($produtos as $produto) {

            foreach ($produto as $prod) {

                $countLotes = 0;
                $estoqueLotes = 0;
                $estoqueGeral = 0;
                $tamArrayLotes = 0;
                $temp = 0;

                if (!isset($prod['lotes']))
                    continue;

                $lotes = arrayFormat($prod['lotes']);

                foreach ($lotes as $lote) {

                    $countLotes++;

                    $this->arrayMovEstoque[] = [
                        "id_fornecedor" => $this->fornecedor,
                        "produto" => NULL,
                        "nome_comercial" => trim($prod['nomeComercial']),
                        "codigo" => trim($prod['codigo']),
                        "apresentacao" => trim($prod['apresentacao']),
                        "quantidade" => trim($prod['estoque']),
                        "unidade" => trim($prod['unidade']),
                        "marca" => trim($prod['marca']),
                        "rms" => trim((empty($prod['rms'])) ? NULL : $prod['rms']),
                        "qtd_unidade" => trim($prod['quant_unidade']),
                        "lote" => trim($lote['ds_lote']),
                        "validade" => $lote["validade"],
                    ];

                    $estoqueGeral = intval($prod['estoque']);

                    $this->arrayProdsLote[] = [
                        "lote" => trim($lote['ds_lote']),
                        "local" => NULL,
                        "codigo" => intval($prod['codigo']),
                        "id_fornecedor" => $this->fornecedor,
                        "estoque" => intval($lote['quantidade']),
                        "validade" => dateFormat($lote["validade"], "Y-m-d")
                    ];

                    /**
                     * Faz a soma da quantidade de estoque do produto.
                     */
                    $estoqueLotes += intval($lote['quantidade']);
                }

                /**
                 * Quantidade de lotes de um produto.
                 */
                $tamArrayLotes = count($this->arrayProdsLote);

                $this->arrayProdsCatalogo[] = [
                    "codigo" => intval($prod['codigo']),
                    "rms" => trim((empty($prod['rms'])) ? NULL : $prod['rms']),
                    "apresentacao" => trim($prod['apresentacao']),
                    "marca" => trim($prod['marca']),
                    "descricao" => NULL,
                    "unidade" => trim($prod['unidade']),
                    "quantidade_unidade" => intval($prod['quant_unidade']),
                    "nome_comercial" => trim($prod['nomeComercial']),
                    "id_fornecedor" => $this->fornecedor,
                    "ativo" => 1,
                    "bloqueado" => 0
                ];

                /**
                 * A movimentação da Empresa Biohosp é muito grande.
                 * Eles não conseguem ter controle real da quantidade de estoque por produto.
                 * Eles só tem a certeza de quantos produtos existem, mas não sabem de qual Estoque é.
                 * Nossa integração verifica se a soma total de todos os estoque é igual a quantidade informada.
                 * Se a soma for diferente da quantidade, pegamos a soma de todos os estoques e dividimos pela quantidade de lotes.
                 * E o valor resultante da divisão colocamos em cada lote, para ter uma ideia mais ou menos dessa movimentação.
                 */
                if (!($estoqueGeral == $estoqueLotes)) {

                    $newQuantidade = (int)($estoqueGeral / $countLotes);
                    $restoEstoque = $estoqueGeral % $countLotes;

                    $temp = ($tamArrayLotes - $countLotes);

                    for ($i = $temp; $i < $tamArrayLotes; $i++) {

                        $this->arrayProdsLote[$i]['estoque'] = $newQuantidade;
                    }
                    $this->arrayProdsLote[$temp]['estoque'] += $restoEstoque;
                }
            }
        }
    }

    protected function index_post()
    {

        try {

            /**
             * Verifica se o fornecedor faz parte da rotina de integração.
             * Inicia a contagem do tempo da Rotina.
             */
            if (!$this->Engine->start('BEGIN', $this->fornecedor, time()))
                exit();

            $post = file_get_contents("php://input");

            /**
             * A TI da Biohosp, não tem poder 100% em cima do ERP da empresa,
             * por esse motivo o arquivo JSON vem todos desconfigurado, pela má
             * configuração do sistema.
             * O tratamento de Strings é necessário para a extruturação
             * do arquivo JSON.
             */
            $re = '/(\w+)(:)/m';

            $subst = '\\"$1\\"$2';

            $result = preg_replace($re, $subst, $post);

            $json = str_replace("\\", "", $result);

            $produtos = json_decode($json, true);

            /**
             * Chama a Função para criar os arrays com os dados dos produtos.
             */
            $this->mountArraysProds($produtos);

            //  $this->db->trans_start();

            /**
             * Insere todos os dados dos produtos na tabela de log.
             * Movimentação de Estoque
             */
            $this->db->insert_batch('movimentacao_estoque', $this->arrayMovEstoque);

            /**
             * Apaga o objeto de movimentação de estoque para liberar memória.
             */
            unset($this->arrayMovEstoque);

            /**
             * Limpa toda a tabela de estoque do fornecedor.
             */
            $this->Engine->cleanStock($this->fornecedor);

            /**
             * Para cada lote, verifica se é maior que zero.
             * Para cada lote, verifica se já foi inserido.
             * Insere o lote no Banco de Dados.
             */
            foreach ($this->arrayProdsLote as $prodLot) {

                if ($this->Engine->checkLot($prodLot) && $prodLot['estoque'] > 0) {
                    if ($prodLot['estoque'] > 0)
                        $this->db->insert('produtos_lote', $prodLot);
                }
            }

            /**
             * Apaga o objeto de estoque para liberar memória.
             */
            unset($this->arrayProdsLote);

            /**
             * Para cada produto, verifica se já está cadastrado no Catálogo.
             * Insere o produto no Banco de Dados se não estiver no catálogo.
             * Se o produto já for cadastrado, efetua um update no mesmo com a função activeCatalog.
             */
            foreach ($this->arrayProdsCatalogo as $prodCat) {

                if ($this->Engine->checkCatalog($prodCat)) {

                    $this->db->insert('produtos_catalogo', $prodCat);

                } else {

                    $this->Engine->activeCatalog($prodCat);
                }
            }

            /**
             * Apaga o objeto de catalogo para liberar memória.
             */
            unset($this->arrayProdsCatalogo);

            /**
             * Finaliza a contagem do tempo da Rotina.
             */
            $this->Engine->start('END', $this->fornecedor, time(), NULL);

            $return = [
                "response" => TRUE,
                "message" => "Estoque Atualizado!"
            ];

        } catch (Exception $e) {

            $return = [
                "response" => FALSE,
                "message" => $e->getMessage()
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($return));
    }
}
