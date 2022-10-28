<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_forma_pagamento_fornecedor extends MY_Model
{
    protected $table = 'formas_pagamento_fornecedores';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    public function gravar()
    {
        $this->db->trans_begin();

        $id_fornecedor      = $this->session->userdata("id_fornecedor");
        $id_tipo_venda      = $this->session->userdata("id_tipo_venda"); //1-markplace 2- integranexo 3- markplace/integranexo
        $id_forma_pagamento = $this->input->post('id_forma_pagamento');
        $elementos          = explode(',', $this->input->post('elementos'));

        $option = $this->input->post("opcao");

        if ($option == 'ESTADOS') {
            foreach ($elementos as $key => $value) {
                $data = [
                    'id_fornecedor'      => $id_fornecedor,
                    'id_estado'          => $value,
                    'id_tipo_venda'      => $id_tipo_venda,
                    'id_forma_pagamento' => $id_forma_pagamento
                ];

                $id = $this->verifyIfExists($data['id_forma_pagamento'], $data['id_estado'], 'ESTADOS');
                if ($id) {
                    $this->db->update($this->table, $data, "id = {$id}", 1);
                } else {
                    $this->db->insert($this->table, $data);
                }
            }
        } else {
            foreach ($elementos as $key => $value) {
                $data = [
                    'id_fornecedor'      => $id_fornecedor,
                    'id_cliente'          => $value,
                    'id_tipo_venda'      => $id_tipo_venda,
                    'id_forma_pagamento' => $id_forma_pagamento
                ];

                $id = $this->verifyIfExists($data['id_forma_pagamento'], $data['id_cliente'], 'CLIENTES');
                if ($id) {
                    $this->db->update($this->table, $data, "id = {$id}", 1);
                } else {
                    $this->db->insert($this->table, $data);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function excluir($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");

        $this->db->where('id', $id);
        $this->db->where('id_fornecedor', $id_fornecedor);

        return $this->db->delete('formas_pagamento_fornecedores');
    }

    public function getById($id)
    {
        $this->db->select("f.id, f.id_forma_pagamento, CONCAT(e.uf, ' - ', e.descricao) AS estado, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS f");
        $this->db->join('estados e', 'f.id_estado = e.id', 'LEFT');
        $this->db->join('usuarios u', 'f.id_cliente = u.id', 'LEFT');
        $this->db->join('compradores c', 'u.id_comprador = c.id', 'LEFT');
        $this->db->where('f.id', $id);
        $this->db->limit(1);

        return $this->db->get()->row_array();
    }

    public function getList($option)
    {
        if ($option === 'ESTADOS') {
            $this->db->select("e.id, CONCAT(e.uf, ' - ', e.descricao) as descricao");
            $this->db->from('estados e');
            $this->db->join("formas_pagamento_fornecedores f", "f.id_estado = e.id AND f.id_fornecedor = {$this->session->userdata('id_fornecedor')}", "LEFT");
            $this->db->where('f.id_estado IS NULL');
            $this->db->order_by('e.descricao', 'ASC');
        } else {
            $this->db->select("c.id, CONCAT(c.cnpj, ' - ', c.razao_social) as descricao");
            $this->db->from('compradores c');
            $this->db->join("formas_pagamento_fornecedores f", "f.id_cliente = c.id AND f.id_fornecedor = {$this->session->userdata('id_fornecedor')}", "LEFT");
            $this->db->where('f.id_cliente IS NULL');
            $this->db->order_by('c.cnpj', 'ASC');
        }

        return $this->db->get()->result_array();
    }

    private function verifyIfExists($forma_pagamento, $param, $option)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('id_forma_pagamento', $forma_pagamento);

        if ($option == 'ESTADOS') {
            $this->db->where('id_estado', $param);
        } else {
            $this->db->where('id_cliente', $param);
        }

        $this->db->where("id_fornecedor", $this->session->id_fornecedor);


        $this->db->limit(1);
        return $this->db->get()->row_array()['id'];
    }
}
