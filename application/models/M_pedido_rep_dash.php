<?php
class M_pedido_rep_dash extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function totalPedidosAbertos()
    {

        $this->db->select('count(0) as total');
        $this->db->where("pr.id_fornecedor = {$this->session->id_fornecedor}");
        $this->db->from('pedidos_representantes as pr');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidosEnviados()
    {

        $this->db->select('sum(prp.total) as total');
        $this->db->where("pr.id_fornecedor = {$this->session->id_fornecedor}");
        $this->db->from('pedidos_representantes_produtos as prp');
        $this->db->join('pedidos_representantes pr', ' pr.id = prp.id_pedido');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidosFaturados($rep = null)
    {

        $this->db->select('sum(prp.total) as total');
        $this->db->where("pr.id_fornecedor = {$this->session->id_fornecedor} and situacao in (4,6) and prp.status = 1");

        if (!is_null($rep)){
            $this->db->where('pr.id_representante', $rep);
        }

        $this->db->from('pedidos_representantes_produtos as prp');
        $this->db->join('pedidos_representantes pr', ' pr.id = prp.id_pedido');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidosCancelados()
    {

        $this->db->select('count(0) as total');
        $this->db->where("pr.id_fornecedor = {$this->session->id_fornecedor} and pr.situacao = 5");
        $this->db->from('pedidos_representantes as pr');

        return $this->db->get()->row_array()['total'];
    }

    public function totalPedidosParciais()
    {

        $this->db->select('count(0) as total');
        $this->db->where("pr.id_fornecedor = {$this->session->id_fornecedor} and pr.situacao = 6");
        $this->db->from('pedidos_representantes as pr');

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