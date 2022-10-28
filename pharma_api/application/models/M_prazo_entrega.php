<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_prazo_entrega extends MY_Model
{
    protected $table = 'prazos_entrega';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    public function listar_prazo_entrega_estado()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("pe.id, pe.prazo, e.uf, e.descricao, pe.id_estado FROM prazos_entrega pe INNER JOIN estados e ON e.id = pe.id_estado ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_cliente', NULL);
        $consulta = $this->db->get();

        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function listar_prazo_entrega_cnpj()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select(" pe.prazo, pe.id, u.id_dados_usuario, u.id_endereco, u.tipo_usuario, u.cnpj, u.ativo, du.razao_social, du.integracao FROM prazos_entrega pe INNER JOIN  usuarios u ON u.id=pe.id_cliente INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario INNER JOIN enderecos e ON e.id=u.id_endereco ", false);
        $this->db->where('pe.id_fornecedor', $id_fornecedor);
        $this->db->where('pe.id_estado', NULL);
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
        $id_tipo_venda = $this->session->userdata("id_tipo_venda"); //1-markplace 2- integranexo 3- markplace/integranexo
        $prazo_entrega = $this->input->post('prazo');
        $elementos     = explode(',', $this->input->post('elementos'));

        $option = $this->input->post("opcao");

        if ($option == 'ESTADOS') {

            $dataNovo = [];
            $dataAtualizacao = [];

            foreach ($elementos as $key => $value) {
                $id = $this->verifyIfExists($prazo_entrega, $value, 'ESTADOS');

                if ($id) {
                    
                    $dataAtualizacao[] = [
                        'id' => $id,
                        'id_fornecedor' => $id_fornecedor,
                        'id_estado'    => $value,
                        'id_tipo_venda' => $id_tipo_venda,
                        'prazo'         => $prazo_entrega
                    ];

                } else {

                    $dataNovo[] = [
                        'id_fornecedor' => $id_fornecedor,
                        'id_estado'    => $value,
                        'id_tipo_venda' => $id_tipo_venda,
                        'prazo'         => $prazo_entrega
                    ];

                }
            }
    
            if (!empty($dataNovo)) 
                $this->db->insert_batch($this->table, $dataNovo);
        
            if (!empty($dataAtualizacao)) 
                $this->db->update_batch($this->table, $dataAtualizacao, 'id');

        } else {

            $dataNovo = [];
            $dataAtualizacao = [];

            foreach ($elementos as $key => $value) {

                $id = $this->verifyIfExists($prazo_entrega, $value, 'CLIENTES');

                if ($id) {
                    
                    $dataAtualizacao[] = [
                        'id' => $id,
                        'id_fornecedor' => $id_fornecedor,
                        'id_cliente'    => $value,
                        'id_tipo_venda' => $id_tipo_venda,
                        'prazo'         => $prazo_entrega
                    ];

                } else {

                    $dataNovo[] = [
                        'id_fornecedor' => $id_fornecedor,
                        'id_cliente'    => $value,
                        'id_tipo_venda' => $id_tipo_venda,
                        'prazo'         => $prazo_entrega
                    ];

                }
            }

            if (!empty($dataNovo)) 
                $this->db->insert_batch($this->table, $dataNovo);
            
            if (!empty($dataAtualizacao)) 
                $this->db->update_batch($this->table, $dataAtualizacao, 'id');
            
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

        return $this->db->delete('prazos_entrega');
    }

    public function getById($id)
    {
        $this->db->select("p.id, p.prazo, CONCAT(e.uf, ' - ', e.descricao) AS estado, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("{$this->table} AS p");
        $this->db->join('estados e', 'p.id_estado = e.id', 'LEFT');
        $this->db->join('usuarios u', 'p.id_cliente = u.id', 'LEFT');
        $this->db->join('compradores c', 'u.id_comprador = c.id', 'LEFT');

        $this->db->where('p.id', $id);
        $this->db->where('p.id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->limit(1);

        return $this->db->get()->row_array();
    }

    public function getList($option)
    {
        if ($option === 'ESTADOS') {
            $this->db->select("e.id, CONCAT(e.uf, ' - ', e.descricao) as descricao");
            $this->db->from('estados e');
            // $this->db->join("prazos_entrega p", "p.id_estado = e.id AND p.id_fornecedor = {$this->session->userdata('id_fornecedor')}", "LEFT");
            // $this->db->where('p.id_estado IS NULL');
            $this->db->order_by('e.descricao', 'ASC');
        } else {
            $this->db->select("c.id, CONCAT(c.cnpj, ' - ', c.razao_social) as descricao");
            $this->db->from('compradores c');
            // $this->db->join("prazos_entrega p", "p.id_cliente = c.id AND p.id_fornecedor = {$this->session->userdata('id_fornecedor')}", "LEFT");
            // $this->db->where('p.id_cliente IS NULL');
            $this->db->order_by('c.cnpj', 'ASC');
        }

        return $this->db->get()->result_array();
    }

    private function verifyIfExists($prazo, $param, $option)
    {
        $this->db->select('*');
        $this->db->from($this->table);

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
