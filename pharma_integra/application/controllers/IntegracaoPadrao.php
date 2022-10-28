<?php

class IntegracaoPadrao extends MY_Controller_Auth
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    private $arrayMovEstoque = [];
    private $arrayProdsCatalogo = [];
    private $arrayProdsLote = [];
    private $arrayProdsPreco = [];
    private $fornecedor = [];

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
         * Preços
         */

        $result = [];

        foreach ($produtos as $produto) {

            foreach ($produto as $prod) {

                $cnpj = $prod['cnpj'];

                if (!is_numeric($cnpj) || strlen($cnpj) <> 14) {

                    $result[] = [
                        "cpnj" => $cnpj,
                        "action" => "insert",
                        "status" => FALSE,
                        "message" => "Verifique se o CNPJ esta correto!"
                    ];
                    continue;
                }

                $cnpj = mask($cnpj, '##.###.###/####-##');

                $id_fornecedor = $this->db->where('cnpj', $cnpj)
                    ->get('fornecedores')
                    ->row_array()['id'];

                $id_fornecedor = intval($id_fornecedor);

                if ($id_fornecedor == 0) {

                    $result[] = [
                        "cpnj" => $cnpj,
                        "action" => "insert",
                        "status" => FALSE,
                        "message" => "Cnpj não Localizado!"
                    ];
                    continue;
                }

                if (!in_array($id_fornecedor, $this->fornecedor))
                    $this->fornecedor[] = $id_fornecedor;

                if (!isset($prod['lotes']))
                    continue;

                $lotes = arrayFormat($prod['lotes']);

                foreach ($lotes as $lote) {

                    $this->arrayMovEstoque[] =
                        [
                            "id_fornecedor" => $id_fornecedor,
                            "descricao" => trim($prod['descricao']),
                            "nome_comercial" => trim($prod['nome_comercial']),
                            "codigo" => trim($prod['codigo']),
                            "apresentacao" => trim($prod['apresentacao']),
                            "quantidade" => trim($lote['estoque']),
                            "unidade" => trim($prod['unidade']),
                            "marca" => trim($prod['marca']),
                            "rms" => trim((empty($prod['rms'])) ? NULL : $prod['rms']),
                            "qtd_unidade" => trim($prod['quantidade_unidade']),
                            "lote" => trim($lote['lote']),
                            "validade" => $lote["validade"],
                        ];

                    $this->arrayProdsLote[] =
                        [
                            "id_fornecedor" => $id_fornecedor,
                            "lote" => trim($lote['lote']),
                            "local" => NULL,
                            "codigo" => intval($prod['codigo']),
                            "estoque" => intval($lote['estoque']),
                            "validade" => dateFormat($lote["validade"], "Y-m-d")
                        ];

                }

                $this->arrayProdsCatalogo[] =
                    [
                        "id_fornecedor" => $id_fornecedor,
                        "codigo" => intval($prod['codigo']),
                        "rms" => trim((empty($prod['rms'])) ? NULL : $prod['rms']),
                        "apresentacao" => trim($prod['apresentacao']),
                        "marca" => trim($prod['marca']),
                        "descricao" => trim($prod['descricao']),
                        "unidade" => trim($prod['unidade']),
                        "quantidade_unidade" => intval($prod['quantidade_unidade']),
                        "nome_comercial" => trim($prod['nome_comercial']),
                        "ativo" => 1,
                        "bloqueado" => 0
                    ];

                $precos_uf = [];
                $preco_br = [];

                if (isset($prod['precos_uf']))
                    $precos_uf = $prod['precos_uf'];

                if (isset($prod['preco_br']))
                    $preco_br = $prod['preco_br'];

                if (!empty($precos_uf)) {

                    foreach ($precos_uf as $key => $preco) {

                        $id_estado = $this->db->where('uf', $key)
                            ->get('estados')
                            ->row_array()['id'];

                        if (IS_NULL($id_estado))
                            continue;

                        $this->arrayProdsPreco[] =
                            [
                                "id_fornecedor" => $id_fornecedor,
                                "id_estado" => intval($id_estado),
                                "codigo" => intval($prod['codigo']),
                                "preco_unitario" => number_format(floatval(strtr($preco, ',', '.')), 4, '.', '')
                            ];


                    }
                }

                $tam_precos_uf = count($this->arrayProdsPreco);

                if ($tam_precos_uf >= 27) {
                    continue;
                } else {

                    if (!empty($preco_br)) {

                        $this->arrayProdsPreco[] =
                            [
                                "id_fornecedor" => $id_fornecedor,
                                "id_estado" => NULL,
                                "codigo" => intval($prod['codigo']),
                                "preco_unitario" => number_format(floatval(strtr($preco_br, ',', '.')), 4, '.', '')
                            ];

                    }
                }
            }
        }
    }

    protected function index_post()
    {

        try {

            $produtos = $this->_post_args; //POST

            /**
             * Chama a Função para criar os arrays com os dados dos produtos.
             */
            $this->mountArraysProds($produtos);

            /**
             * Verifica se o fornecedor faz parte da rotina de integração.
             * Inicia a contagem do tempo da Rotina.
             */
             if (!$this->Engine->startIn('BEGIN', $this->fornecedor, time()))
                 exit();

            //  $this->db->trans_start();

            /**
             * Insere todos os dados dos produtos na tabela de log.
             * Movimentação de Estoque
             */
            $this->db->insert_batch('movimentacao_estoque', $this->arrayMovEstoque);

            unset($this->arrayMovEstoque);

            /**
             * Limpa toda a tabela de estoque do fornecedor.
             */
            $this->Engine->cleanStockIn($this->fornecedor);

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

            unset($this->arrayProdsCatalogo);

            /**
             * Para cada preco, verifica se já existe no Banco de dados e se não é zerado.
             * Insere o preço na tabela de produtos preço.
             */
            foreach ($this->arrayProdsPreco as $prodPrec) {

                if ($this->Engine->checkPrice($prodPrec) && !(floatval($prodPrec['preco_unitario']) == 0)) {
                    $this->db->insert('produtos_preco', $prodPrec);
                }
            }

            unset($this->arrayProdsPreco);

            /**
             * Finaliza a contagem do tempo da Rotina.
             */
            $this->Engine->startIn('END', $this->fornecedor, time(), NULL);

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
