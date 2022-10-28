<?php

class m_StatusFarma extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProduto($produto)
    {
        return $this->db
            ->where('id_fornecedor', $produto['id_fornecedor'])
            ->where('codigo', $produto['codigo'])
            ->get('produtos_catalogo')
            ->row_array();
    }

    public function insertProduto($produto)
    {
        return $this->db->insert('produtos_catalogo', $produto);
    }

    public function resetLotes()
    {

    }

    public function insertLotes()
    {

    }
}