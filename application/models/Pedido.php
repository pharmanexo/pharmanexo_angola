<?php

Class Pedido extends CI_Model
{
    private $table, $view;

    public function __construct()
    {
        parent::__construct();
        $this->table = "pedidos";
        $this->view = "vw_pedidos";
    }

    public function get_row($id)
    {
        if (!isset($id)) return null;

        return $this->db->where('id', $id)
            ->get($this->view)
            ->row_array();
    }

    public function update($id, $data)
    {
        if (!isset($id) || !isset($data)) return false;

        return $this->db->where('id', $id)
            ->update($this->view, $data);
    }
}
