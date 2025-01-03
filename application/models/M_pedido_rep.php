<?php
class M_pedido_rep extends MY_Model{

    public function __construct()
    {
        parent::__construct();

        $this->table = 'pedidos_representantes';
        $this->primary_key = 'id';
    }

    public function totalPedidoAprovado($id_pedido)
    {
        $this->db->select('sum(total) as total');
        $this->db->where("id_pedido = {$id_pedido} AND status = 1");
        $total = $this->db->get('pedidos_representantes_produtos')->row_array()['total'];

        return isset($total) ? $total : 0;
    }

}