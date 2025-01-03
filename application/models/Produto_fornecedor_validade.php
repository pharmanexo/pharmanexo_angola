<?php

class Produto_fornecedor_validade extends MY_Model{


    protected $table = 'produtos_fornecedores_validades';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_row($id)
    {
        if (!isset($id)) return [];

        $this->db->where("id_sintese", $id);

        return $this->db->get('vw_produtos_fornecedores_sintese')->row_array();
    }

    public function get_itens($fields = "*", $where = NULL, $order = NULL, $start = NULL, $offset = NULL)
    {
        $this->db->select($fields);

        if (isset($where)) {
            $this->db->where($where);
        }

        if (isset($order)) {
            $this->db->order_by($order);
        }

        if (isset($start)) {
            if (isset($offset)) {
                $this->db->limit($offset, $start);
            } else {
                $this->db->limit($start);
            }
        }

        $this->db->from('vw_produtos_fornecedores_sintese');

        $query = $this->db->get();

        return $query->result_array();
    }

}