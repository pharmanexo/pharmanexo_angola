<?php
class M_pedido_rep_prod extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function totalPedidosAbertos()
    {

        $this->db->select('sum(prp.total) as total');
        $this->db->where("pr.id_representante = {$this->session->id} and pr.id_fornecedor = {$this->session->id_fornecedor} and pr.situacao = 1");
        $this->db->from('pedidos_representantes_produtos as prp');
        $this->db->join('pedidos_representantes pr', ' pr.id = prp.id_pedido');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidosEnviados()
    {

        $this->db->select('sum(prp.total) as total');
        $this->db->where("pr.id_representante = {$this->session->id} and pr.id_fornecedor = {$this->session->id_fornecedor} and situacao = 2");
        $this->db->from('pedidos_representantes_produtos as prp');
        $this->db->join('pedidos_representantes pr', ' pr.id = prp.id_pedido');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidosFaturados()
    {

        $this->db->select('sum(prp.total) as total');
        $this->db->where("pr.id_representante = {$this->session->id} and pr.id_fornecedor = {$this->session->id_fornecedor} and situacao = 4");
        $this->db->from('pedidos_representantes_produtos as prp');
        $this->db->join('pedidos_representantes pr', ' pr.id = prp.id_pedido');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidosCancelados()
    {

        $this->db->select('sum(prp.total) as total');
        $this->db->where("pr.id_representante = {$this->session->id} and pr.id_fornecedor = {$this->session->id_fornecedor} and situacao = 5");
        $this->db->from('pedidos_representantes_produtos as prp');
        $this->db->join('pedidos_representantes pr', ' pr.id = prp.id_pedido');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidoAprovado($id)
    {
        $this->db->select('sum(prp.total) as total');
        $this->db->where("pr.id", $id);
        $this->db->from('pedidos_representantes_produtos as prp');
        $this->db->join('pedidos_representantes pr', ' pr.id = prp.id_pedido');

        return $this->db->get()->row_array()['total'];
    }

    public function getProdutosPedidos($id){

        $this->db->select('*');
        $this->db->where("prp.id_pedido", $id);
        $this->db->from('pedidos_representantes_produtos as prp');

        return $this->db->get()->result_array();


    }
}