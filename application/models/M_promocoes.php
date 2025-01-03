<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_promocoes extends MY_Model
{
    protected $table = 'vendas_diferenciadas';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();

        $this->load->model('m_estados', 'estado');
        $this->load->model('m_estoque', 'estoque');
        $this->load->model('m_usuarios', 'usuario');
    }

    public function getById($id)
    {
        $this->db->select('v.id');
        $this->db->select('v.id_tipo_venda');
        $this->db->select('v.desconto_percentual');
        $this->db->select('v.comissao');
        $this->db->select('v.quantidade');
        $this->db->select('v.tipo');
        $this->db->select('v.lote');
        $this->db->select('v.dias');
        $this->db->select('w.produto_descricao');
        $this->db->select("CONCAT(e.uf, ' - ', e.descricao) AS estado");
        $this->db->select("CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS v");
        $this->db->join('estados e', 'v.id_estado = e.id', 'LEFT');
        $this->db->join('compradores c', 'v.id_cliente = c.id', 'LEFT');
        $this->db->join('vw_produtos_fornecedores w', 'v.id_produto = w.id', 'INNER');
        $this->db->where('v.id', $id);
        $this->db->where('v.promocao', 1);
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function verificarSeExisteVenda($id_produto, $param, $option)
    {
        $this->db->select('*');
        $this->db->from('vendas_diferenciadas');

        if ($option === 'ESTADOS') {
            $this->db->where('id_estado', $param);
        } else {
            $this->db->where('id_cliente', $param);
        }

        $this->db->where('id_produto', $id_produto);
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row_array()['id'];
    }

    public function excluir($codigo)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->where('codigo', $codigo);
        $this->db->where('promocao', '1');
        $this->db->where('id_fornecedor', $id_fornecedor);
        return $this->db->delete($this->table);
    }
}

/* End of file: M_venda_diferenciada.php */
