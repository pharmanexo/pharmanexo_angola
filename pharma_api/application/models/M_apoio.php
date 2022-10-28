<?php

class M_apoio extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProdutoRespondidos($data)
    {
        if (isset($data['cd_cotacao'])) {
            $this->db->where('cd_cotacao', $data['cd_cotacao']);
        }

        if (isset($data['id_cliente'])) {
            $this->db->where('id_cliente', $data['id_cliente']);
        }

        if (isset($data['id_fornecedor'])) {
            $this->db->where('id_fornecedor', $data['id_fornecedor']);
        }

        return $this->db->get('cotacoes_produtos')->result_array();
    }

    public function getOC($data)
    {
        if (empty($data)) return false;

        if (isset($data['cd_ordem_compra'])) {
            $this->db->where('Cd_Ordem_Compra', $data['cd_ordem_compra']);
        }

        if (isset($data['id_cliente'])) {
            $this->db->where('id_comprador', $data['id_cliente']);
        }

        if (isset($data['id_fornecedor'])) {
            $this->db->where('id_fornecedor', $data['id_fornecedor']);
        }

        $r =$this->db->get('ocs_sintese')->row_array();

        return $r ;
    }


    public function getProdPed($data)
    {
        if (empty($data)) return false;

        if (isset($data['id_ordem_compra'])) {
            $this->db->where('id_ordem_compra', $data['id_ordem_compra']);
        }

        if (isset($data['id_confirmacao'])) {
            $this->db->where('id_confirmacao', $data['id_confirmacao']);
        }

        $r =$this->db->get('ocs_sintese_produtos')->result_array();

        return $r ;
    }

    public function insertCabecalho($data)
    {
        if (empty($data)) return false;
        return $this->db->insert('ocs_sintese', $data);
    }

    public function insertProds($data)
    {
        if (empty($data)) return false;
        return $this->db->insert_batch('ocs_sintese_produtos', $data);
    }

}
