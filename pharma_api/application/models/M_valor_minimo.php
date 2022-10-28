<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_valor_minimo extends MY_Model
{
    protected $table = 'valor_minimo_cliente';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function listar_valor_minimo_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("vm.id, vm.valor_minimo, e.uf,e.descricao, vm.id_estado FROM valor_minimo_cliente vm INNER JOIN  estados e ON e.id = vm.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
        $consulta = $this->db->get();

        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function listar_valor_minimo_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select(" vm.valor_minimo, vm.id, u.id_dados_usuario, u.id_endereco, u.tipo_usuario, u.cnpj, u.ativo , du.razao_social, du.integracao FROM valor_minimo_cliente vm INNER JOIN  usuarios u ON u.id=vm.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('vm.id_fornecedor', $id_fornecedor);
        $this->db->where('vm.id_estado', NULL);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function gravar()
    {
        $this->db->trans_begin();

        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda");
        $valor_minimo = dbNumberFormat($this->input->post('valor_minimo'));
        $desconto = dbNumberFormat($this->input->post('desconto_padrao'));

        $option = $this->input->post('opcao');

        // var_dump($this->input->post()); exit();

        if ($option === 'ESTADOS') {
            $estados = explode(',', $this->input->post('elementos'));
            foreach ($estados as $key => $value) {
                $data = [
                    'id_fornecedor' => $id_fornecedor,
                    'id_estado'     => $value,
                    'id_tipo_venda' => $id_tipo_venda,
                    'valor_minimo'  => $valor_minimo,
                    'desconto_padrao'  => $desconto,
                ];

                $id = $this->verifyIfExists($data['valor_minimo'], $data['id_estado'], 'ESTADOS');
                if ($id) {
                    $this->db->update($this->table, $data, "id = {$id}", 1);
                } else {
                    $this->db->insert($this->table, $data);
                }
            }
        } else {
            $clientes = explode(',', $this->input->post('elementos'));
            foreach ($clientes as $key => $value) {
                $data = [
                    'id_fornecedor' => $id_fornecedor,
                    'id_cliente'    => $value,
                    'id_tipo_venda' => $id_tipo_venda,
                    'valor_minimo'  => $valor_minimo,
                    'desconto_padrao'  => $desconto,
                ];

                $id = $this->verifyIfExists($data['valor_minimo'], $data['id_cliente'], 'CLIENTES');
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
        return $this->db->delete('valor_minimo_cliente');
    }

    public function getById($id)
    {
        $this->db->select("v.id, v.desconto_padrao, v.valor_minimo, CONCAT(e.uf, ' - ', e.descricao) AS estado, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS v");
        $this->db->join('estados e', 'v.id_estado = e.id', 'LEFT');
        $this->db->join('usuarios u', 'v.id_cliente = u.id', 'LEFT');
        $this->db->join('compradores c', 'u.id_comprador = c.id', 'LEFT');
        $this->db->where('v.id', $id);
        $this->db->where('v.id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function getList($option)
    {
        if ($option === 'ESTADOS') {
            $this->db->select("e.id, CONCAT(e.uf, ' - ', e.descricao) as descricao");
            $this->db->from('estados e');
            $this->db->join("valor_minimo_cliente vmc", "vmc.id_estado = e.id AND vmc.id_fornecedor = {$this->session->userdata('id_fornecedor')}", "LEFT");
            $this->db->where('vmc.id_estado IS NULL');
            $this->db->order_by('e.descricao', 'ASC');
        } else {
            $this->db->select("c.id, CONCAT(c.cnpj, ' - ', c.razao_social) as descricao");
            $this->db->from('compradores c');
            $this->db->join("valor_minimo_cliente vmc", "vmc.id_cliente = c.id AND vmc.id_fornecedor = {$this->session->userdata('id_fornecedor')}", "LEFT");
            $this->db->where('vmc.id_cliente IS NULL');
            $this->db->order_by('c.cnpj', 'ASC');
        }

        return $this->db->get()->result_array();
    }

    private function verifyIfExists($valor_minimo, $param, $option)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('valor_minimo', $valor_minimo);

        if ($option == 'ESTADOS') {
            $this->db->where('id_estado', $param);
        } else {
            $this->db->where('id_cliente', $param);
        }

        $this->db->where("id_fornecedor", $this->session->id_fornecedor);


        $this->db->limit(1);
        $query = $this->db->get();

        return $query->row_array()['id'];
    }
}
