<?php
class m_carrinho extends CI_Model{
    private $table;
    public function __construct()
    {
        parent::__construct();

        $this->table = "vw_carrinhos";
    }


    public function get_row($id)
    {
        if (!isset($id)) return [];

        $this->db->where("chave", $id);

        return $this->db->get($this->table)->row_array();
    }

    public function get_rows($fields = "*", $where = NULL, $order = NULL, $start = NULL, $offset = NULL)
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

        $this->db->from($this->table);

        $query = $this->db->get();

        return $query->result_array();
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

        $this->db->from($this->table . "_produtos");

        $query = $this->db->get();

        return $query->result_array();
    }

}