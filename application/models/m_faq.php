<?php
/**
 * Criado por:  Marlon Boecker
 * Criado em: 29/05/2019 22:03
 */
class M_faq extends CI_Model{
    public function __construct()
    {
        parent::__construct();

        $this->table = "faq_questions";
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

    public function get_row($id)
    {
        if (!isset($id)) return [];

        $this->db->where("id", $id);

        return $this->db->get($this->table)->row_array();
    }
}