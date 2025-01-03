<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_produtos_fornecedores extends MY_Model
{
    protected $table = 'produtos_fornecedores_validades';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function total_em_vendas()
    {
        $this->db->select('SUM(estoque * preco_unidade) AS total_vendas');
        $this->db->from($this->table);
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->where('validade >= NOW()');
        $query = $this->db->get();
        return $query->row_array()['total_vendas'];
    }

    public function total_produtos_vencendo()
    {
        $this->db->select('SUM(estoque) AS total');
        $this->db->from($this->table);
        $this->db->where("validade BETWEEN '" . date('Y-m-d', time()) . "' AND '" . date('Y-m-d', strtotime('+60 day')) . "'");
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->where('id_estado', $this->session->userdata('id_estado'));
        $query = $this->db->get();
        // var_dump($this->db->last_query());
        // exit;
        return $query->row_array()['total'];
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

        $this->db->from("vw_produtos_fornecedores");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_row($id)
    {
        if (!isset($id)) return [];

        $this->db->where("id", $id);

        return $this->db->get("vw_produtos_fornecedores")->row_array();
    }

    public function get_by_idSintese($id_sintese)
    {
        if (!isset($id)) return [];
        $this->db->where("id_sintese", $id_sintese);
        return $this->db->get("vw_produtos_fornecedores")->row_array();
    }

    public function totalItensPorPeriodo($periodoInicial, $periodoFinal)
    {
        $this->db->select("COUNT(0) AS total");
        $this->db->from("vw_produtos_fornecedores_validades");
        $this->db->where("validade > now()");
        $this->db->where("validade BETWEEN '{$periodoInicial}' AND '{$periodoFinal}'");
        $this->db->where('id_estado', $this->session->userdata('id_estado'));
        $this->db->where('estoque >', 0);
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $result =  $this->db->get()->row_array();

        return $result;
    }

    public function totalItensPorPeriodoAdmin($periodoInicial, $periodoFinal = null, $id_fornecedor)
    {
        $estado = $this->db->where('id', $id_fornecedor)->get('fornecedores')->row_array()['id_estado'];

        $this->db->select("COUNT(0) AS total");
        $this->db->from("vw_produtos_fornecedores_validades");
        $this->db->where("validade > now()");
        $this->db->where('estoque >', 0);
        $this->db->where('id_estado', $estado);
        $this->db->where('id_fornecedor', $id_fornecedor);

        if (!IS_NULL($periodoFinal)) {

            $this->db->where("validade BETWEEN '{$periodoInicial}' AND '{$periodoFinal}'");

        } else {

            $this->db->where("validade > '{$periodoInicial}' ");
        }


        $result = $this->db->get()->row_array();

        return $result;
    }

    public function updateDePara($data)
    {
        $codigo = $data['codigo'];
        unset($data['codigo']);

        $this->db->where("codigo", $codigo);
        $this->db->where("id_fornecedor", $this->session->id_fornecedor);

        return $this->db->update($this->table, $data);
    }


    public function get_lotes($id_fornecedor)
    {
        $query = $this->db->query("SELECT distinct(lote) as source from produtos_fornecedores_validades where id_fornecedor = {$id_fornecedor}");


        #var_dump($query->result_array());exit();
        return $query->result_array();
    }
}

/* End of file: M_produtos_fornecedores.php */
