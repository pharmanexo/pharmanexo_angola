<?php

class Financeiro extends CI_Model
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function verifyOc($oc)
    {
        return $this->db->where('Cd_Ordem_Compra', $oc)
            ->where('pendente', 0)
            ->get('ocs_sintese')
            ->row_array();
    }

    public function outPutOc($type, $message, $data = [])
    {
        $return =
            [
                'type' => $type,
                'message' => $message,
                'data' => $data
            ];

        $this->output->set_content_type('application/json')->set_output(json_encode($return));

    }

    public function getOcResgatada($arrayFornecedor)
    {

        return $this->db->where('pendente', 1)
            ->where_in('id_fornecedor', $arrayFornecedor)
            ->order_by('Cd_Fornecedor')
            ->order_by('Dt_Ordem_Compra')
            ->get('ocs_sintese')
            ->result_array();

    }

    public function insertNotaFiscal($array)
    {

        return $this->db->insert('notas_fiscais', $array);
    }

    public function insertNfProdutos($array)
    {

        return $this->db->insert_batch('notas_fiscais_produtos', $array);

    }

    public function checkEan($array)
    {

        $result = $this->db->select('codigo')
            ->where_in('id_fornecedor', $array['fornecedor'])
            ->where('ean', $array['ean'])
            ->group_by('codigo')
            ->get('produtos_catalogo')
            ->row_array();

        return intval($result['codigo']);

    }

    public function RestartOc($array)
    {

        return $this->db->where('id_fornecedor', $array['id_fornecedor'])
            ->where('Cd_Ordem_Compra', $array['oc'])
            ->set('pendente', 0)
            ->update('ocs_sintese');

    }
}


