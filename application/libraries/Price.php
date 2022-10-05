<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 *
 * @author    chulesantos
 * @date      24/03/2020
 * @license   https://pharmanexo.com.br
 *
 *
 */
class Price

{
    private $oncoprod = [12, 111, 112, 115, 120, 123, 126];
    private $db;

    function __construct()
    {
        $this->CI = &get_instance();

        $this->db = $this->CI->db;
    }

    private function getUFsWithPrices($fornecedor)
    {
        return $this->db->select('(CASE WHEN id_estado IS NULL THEN 0 ELSE id_estado END) id_estado')
            ->where('id_fornecedor', $fornecedor)
            ->group_by('id_estado')
            ->get('produtos_preco')
            ->result_array();
    }

    private function getPriceProd($params)
    {
        $this->db->select("pp.preco_unitario");
        $this->db->from('produtos_preco_max pp');
        $this->db->where('pp.codigo', $params['codigo']);
        $this->db->where('pp.id_fornecedor', $params['id_fornecedor']);
        $this->db->where("pp.id_estado {$params['estado']}");

        return $this->db->get()->row_array();
    }

    private function getQtdUnidade($params)
    {

        return $this->db->select('quantidade_unidade')->where('id_fornecedor', $params['id_fornecedor'])
            ->where('codigo', $params['codigo'])
            ->group_by('quantidade_unidade')
            ->get('produtos_catalogo')
            ->row_array()['quantidade_unidade'];
    }

    public function getPrice($params)
    {

        $id_fornecedor = intval($params['id_fornecedor']);
        $codigo = intval($params['codigo']);
        $id_estado = intval($params['id_estado']);

        $checkQtdUnidade = $this->getQtdUnidade([
            "codigo" => $codigo,
            "id_fornecedor" => $id_fornecedor
        ]);

        if ( $checkQtdUnidade != null && $checkQtdUnidade != '' && $checkQtdUnidade != 0 ) {

            $qtd_unidade = $checkQtdUnidade;

        } else {
            $qtd_unidade = 1;
        }

        $estadosPrecoFornecedor = $this->getUFsWithPrices($id_fornecedor);
        $estadosFornecedor = [];

        foreach ($estadosPrecoFornecedor as $item)
            array_push($estadosFornecedor, intval($item['id_estado']));

        $priceFalse = '0.0000';

        if (in_array($id_estado, $estadosFornecedor)) {

            $estado = "= {$id_estado}";

        } elseif (in_array(0, $estadosFornecedor)) {

            $estado = "is null";

        } else {

            $estado = NULL;
        }

        if (!IS_NULL($estado)) {

            $newParams = [
                "codigo" => $codigo,
                "id_fornecedor" => $id_fornecedor,
                "estado" => $estado
            ];

            $value = $this->getPriceProd($newParams);

            if (IS_NULL($value)) {

                return $priceFalse;

            } else {

                $price = floatval($value["preco_unitario"]);

                $newPrice = 0;

                # TODO => Preço Caixa OncoProd, Hospidrogas e BioHosp

                if (in_array($id_fornecedor, $this->oncoprod)) {

                    $newPrice = ($price / $qtd_unidade);

                } else if (($id_fornecedor == 20) || ($id_fornecedor == 104)) {

                    $newPrice = ($price / $qtd_unidade);

                } else {

                    $newPrice = $price;
                }

                return $newPrice;
            }

        } else {

            return $priceFalse;
        }
    }
}
