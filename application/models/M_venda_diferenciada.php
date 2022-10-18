<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_venda_diferenciada extends MY_Model
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

    /**
     * Remove o registro de vendas_diferenciadas por ID
     *
     * @param  INt Id da venda_diferenciada
     * @return bool
     */
    public function excluir($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->where('id', $id);
        $this->db->where('promocao', '0');
        $this->db->where('id_fornecedor', $id_fornecedor);
        return $this->db->delete('vendas_diferenciadas');
    }

    /**
     * Obtem o registro da venda diferenciada por ID
     *
     * @param  INT ID da venda_diferenciada
     * @return array
     */
    public function getById($id)
    {
        $this->db->select('v.id');
        $this->db->select('v.id_tipo_venda');
        $this->db->select('v.desconto_percentual');
        $this->db->select('v.comissao');
        $this->db->select('v.tipo');
        $this->db->select('v.codigo');
        $this->db->select("CONCAT(w.nome_comercial, ' - ', w.apresentacao) as produto_descricao");
        $this->db->select("CONCAT(e.uf, ' - ', e.descricao) AS estado");
        $this->db->select("CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS v");
        $this->db->join('estados e', 'v.id_estado = e.id', 'LEFT');
        $this->db->join('compradores c', 'v.id_cliente = c.id', 'LEFT');
        $this->db->join('produtos_catalogo w', 'v.codigo = w.codigo', 'INNER');
        $this->db->where('v.id', $id);
        $this->db->limit(1);

        return $this->db->get()->row_array();
    }

    /**
     * Verifica se o registro existe no banco
     *
     * @param  INT codigo do produto
     * @param  INT Id do fornecedor
     * @param  String lote do produto
     * @param  String condição where de estado ou comprador
     * @param  INT flag venda diferenciada com promoção
     * @return false/id registro
     */
    public function verificarExistente($codigo, $id_fornecedor, $lote, $where, $promocao = null)
    {

        $this->db->select('id');
        $this->db->where("codigo", $codigo);
        $this->db->where("lote", $lote);
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where($where);

        if ( isset($promocao) ) {
            
            $this->db->where('promocao', 1);
        }

        $vd = $this->db->get($this->table)->row_array();
        
        if ( isset($vd) && !empty($vd) ) {
            
            return $vd['id'];
        } else {

            return false;
        }
    }
}

/* End of file: M_venda_diferenciada.php */
