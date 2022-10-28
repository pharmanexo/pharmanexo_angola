<?php
class Cotacao extends MY_Model
{
    protected $table = 'cotacoes_produtos';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_cotacao($id_cotacao)
    {
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        $this->db->where('id_cotacao', $id_cotacao);
        return $this->db->get('vw_cotacoes')->row_array();
    }

    public function getByCodigo($cd_cotacao)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('cd_cotacao', $cd_cotacao);
        return $this->db->get()->row_array();
    }

    public function get_cotacoes()
    {
        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        return $this->db->get('vw_cotacoes')->result_array();
    }
}
