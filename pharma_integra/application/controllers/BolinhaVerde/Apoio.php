<?php

class Apoio extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     *
     * Rotina para setar a Bolinha Verde nas Cotaçoes da Bionexo que Tem Depara.
     *
     * Crontab => 45 7-23 * * * curl --request GET https://pharmanexo.com.br/pharma_integra/BolinhaVerde/Bionexo
     */

    private $bio;

    public function __construct()
    {
        parent::__construct();

        $this->bio = $this->load->database('apoio', true);
    }

    public function index_get()
    {


        /**
         * Verifica os fornecedores que fazem parte da Integração da Bionexo
         */
        $fornecedores = loginBionexo();


        foreach ($fornecedores as $fornecedor) {

            $id_fornecedor = intval($fornecedor['id_fornecedor']);

            $cotacoes = $this->bio
                ->where('id_fornecedor', $id_fornecedor)
                ->where('dt_fim_cotacao > NOW()')
                ->where('visitado', 0)
                ->get('cotacoes')
                ->result_array();

            if (empty($cotacoes))
                continue;


            $endKeyCot = (count($cotacoes) - 1);

            foreach ($cotacoes as $keyCot => $cotacao) {

                $produtos = $this->bio->where('id_cotacao', $cotacao['id'])
                    ->get('cotacoes_produtos')
                    ->result_array();

                $endKeyProd = (count($produtos) - 1);

                foreach ($produtos as $keyProd => $produto) {

                    $resultIdsProdutos = $this->db->select('id_produto_sintese')
                        ->where('cd_produto', $produto['cd_produto_comprador'])
                        ->where('id_cliente', $cotacao['id_cliente'])
                        ->get('produtos_clientes_depara')
                        ->result_array();

                    if (empty($resultIdsProdutos)) {

                        if ($endKeyProd == $keyProd) {

                            $this->bio->where('id', $cotacao['id'])
                                ->set('visitado', 1)
                                ->update('cotacoes');

                            if ($endKeyCot == $keyCot) {

                                continue 3;

                            } else {

                                continue 2;
                            }

                        } else {

                            continue;
                        }
                    }

                    $ids_produto = [];

                    foreach ($resultIdsProdutos as $value) {

                        $id_produto = intval($value['id_produto_sintese']);

                        if (!in_array($id_produto, $ids_produto))
                            array_push($ids_produto, $id_produto);

                    }

                    $resultIdsSintese = $this->db->select('id_sintese')
                        ->where_in('id_produto', $ids_produto)
                        ->get('produtos_marca_sintese')
                        ->result_array();

                    if (empty($resultIdsSintese)) {

                        if ($endKeyProd == $keyProd) {

                            $this->bio->where('id', $cotacao['id'])
                                ->set('visitado', 1)
                                ->update('cotacoes');

                            if ($endKeyCot == $keyCot) {

                                continue 3;

                            } else {

                                continue 2;
                            }

                        } else {

                            continue;
                        }
                    }

                    $ids_sintese = [];

                    foreach ($resultIdsSintese as $value) {

                        $id_sintese = intval($value['id_sintese']);

                        if (!in_array($id_sintese, $ids_sintese))
                            array_push($ids_sintese, $id_sintese);

                    }

                    $select = "cat.codigo, cat.descricao, cat.apresentacao, cat.unidade,
					   cat.nome_comercial, cat.marca, cat.quantidade_unidade";

                    $resultDepara = $this->db->select($select)
                        ->distinct()
                        ->where_in('pfs.id_sintese', $ids_sintese)
                        ->where('pfs.id_fornecedor', $id_fornecedor)
                        ->where('cat.ativo', 1)
                        ->where('cat.bloqueado', 0)
                        ->from('produtos_fornecedores_sintese AS pfs')
                        ->join('produtos_catalogo AS cat', 'cat.codigo = pfs.cd_produto AND cat.id_fornecedor = pfs.id_fornecedor')
                        ->get()
                        ->result_array();

                    if (empty($resultDepara)) {

                        if ($endKeyProd == $keyProd) {

                            $this->bio->where('id', $cotacao['id'])
                                ->set('visitado', 1)
                                ->update('cotacoes');

                            if ($endKeyCot == $keyCot) {

                                continue 3;

                            } else {

                                continue 2;
                            }

                        } else {

                            continue;
                        }

                    } else {

                        $this->bio->where('id', $cotacao['id'])
                            ->set('visitado', 1)
                            ->set('oferta', 1)
                            ->update('cotacoes');

                    }
                }
            }
        }
    }
}
