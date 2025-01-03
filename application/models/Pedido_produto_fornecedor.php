<?php

Class Pedido_produto_fornecedor extends MY_Model
{
    protected $table, $view;

    public function __construct()
    {
        parent::__construct();
        $this->table = "pedidos_produtos_fornecedores";
        $this->view = "vw_pedidos_produtos";
    }

    public function get_row($id)
    {
        if (!isset($id)) return null;

        return $this->db->where('id', $id)
            ->get($this->view)
            ->row_array();
    }

    public function atualizar($id, $data)
    {
        if (!isset($id) || !isset($data)) return false;

        return $this->db->where('id', $id)
            ->update($this->table, $data);
    }
}
