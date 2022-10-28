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

    public function checkPrice($array): bool
    {
        /**
         * Verifica se o preço já foi inserido no Banco de Dados.
         */

        $vigencia = $this->db->select_max('data_criacao')
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            #->where('id_estado', $array['id_estado'])
            ->get('produtos_preco')
            ->row_array()['data_criacao'];

        $preco = $this->db->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->where('preco_unitario', $array['preco_unitario'])
            #->where('id_estado', $array['id_estado'])
            ->where('data_criacao', $vigencia)
            ->get('produtos_preco')
            ->row_array();

        return IS_NULL($preco);
    }


    public function insertProduto($produto)
    {
        return $this->db->insert('produtos_catalogo', $produto);
    }

    public function resetLotes()
    {
        return $this->db->where('id_fornecedor', 5031)->delete('produtos_lote');
    }

    public function insertLotes($lotes)
    {
        $this->db->insert_batch('produtos_lote', $lotes);
    }

    public function insertPrecos($precos)
    {
        $this->db->insert_batch('produtos_preco', $precos);
    }
}
