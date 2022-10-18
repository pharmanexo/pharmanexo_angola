<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_pedido extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function CountAll()
    {
        $id_cliente = $this->session->userdata("id_usuario");
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $produto = $this->input->post('product');
        $marca = $this->input->post('marca');
        $this->db->select(" count(DISTINCT ppf.id) AS numrows FROM pedidos ped INNER JOIN pedidos_produtos_fornecedores ppf ON ppf.id_pedido=ped.id INNER JOIN carrinhos c ON c.id=ped.id_carrinho INNER JOIN produtos_fornecedores pf ON pf.id=ppf.id_produto INNER JOIN marcas m ON m.id = pf.id_marca   INNER JOIN produtos p ON p.id=pf.id_produto ", false);
        if ($tipo_usuario == 1) $this->db->where('pf.id_fornecedor', $this->session->userdata('id_fornecedor')); //somente para fornecedores
        $this->db->where('ped.id_cliente', $id_cliente);
        $this->db->where('c.status', 'Em Analise');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        if ($produto != null) $this->db->like('p.produto_descricao', $produto, 'both');
        $consulta = $this->db->get();
        return $consulta;
    }

    public function produtosAnalise($inicio, $qnt_result_pg)
    {
        $id_cliente = $this->session->userdata("id_usuario");
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $marca = $this->input->post('marca');
        $produto = $this->input->post('product');
        $this->db->select(" ppf.*, p.produto_descricao,du.razao_social,  m.marca, pf.id_sintese, pf.id_produto, pf.id_estado,  pf.codigo,  pf.porcentagem_campanha, pf.ativo, pf.preco, pf.preco_unidade, pf.estoque, pf.quantidade_unidade FROM pedidos ped INNER JOIN pedidos_produtos_fornecedores ppf ON ppf.id_pedido=ped.id INNER JOIN carrinhos c ON c.id=ped.id_carrinho INNER JOIN produtos_fornecedores pf ON pf.id=ppf.id_produto INNER JOIN marcas m ON m.id = pf.id_marca   INNER JOIN produtos p ON p.id=pf.id_produto INNER JOIN  usuarios u ON u.id=ped.id_fornecedor INNER JOIN dados_usuarios du ON du.id=u.id_dados_usuario ", false);
        $this->db->where('ped.id_cliente', $id_cliente);
        $this->db->where('c.status', 'Em Analise');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        if ($produto != null) $this->db->like('p.produto_descricao', $produto, 'both');
        $this->db->limit($qnt_result_pg, $inicio);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function buscaProduto($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $tipo_usuario = $this->session->userdata("tipo_usuario");

        $this->db->select(" p.produto_descricao, pf.id, pf.id_marca, m.marca, pf.id_sintese, pf.id_produto, pf.id_estado,  pf.codigo,  pf.porcentagem_campanha, pf.ativo, pf.preco, pf.preco_unidade, pf.estoque AS quantidade, pf.quantidade_unidade from produtos_fornecedores pf INNER JOIN marcas m ON m.id = pf.id_marca INNER JOIN produtos p ON p.id = pf.id_produto ", false);
        if ($tipo_usuario == 1) $this->db->where('pf.id_fornecedor', $id_fornecedor); //somente para fornecedores
        $this->db->where('pf.ativo', 1);
        $this->db->where('pf.aprovado', 1);
        $this->db->where('pf.id', $id);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function marcasFornecedor()
    {
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $marca = $this->input->post('marca');
        $this->db->select(" DISTINCT pf.id_marca, m.marca from produtos_fornecedores pf INNER JOIN marcas m ON m.id=pf.id_marca ", false);
        if ($tipo_usuario == 1) $this->db->where('pf.id_fornecedor', $id_fornecedor); //somente para fornecedores 
        $this->db->order_by('m.marca');
        if ($marca != null) $this->db->where('pf.id_marca', $marca);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
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

        $this->db->from('vw_pedidos');

        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_row($id)
    {
        if (!isset($id)) return [];

        $this->db->where("id", $id);

        return $this->db->get('vw_pedidos')->row_array();
    }

    public function get_itens($fields = "*", $where = NULL, $order = NULL, $start = NULL, $offset = NULL)
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

        $this->db->from('vw_pedidos_produtos');

        $query = $this->db->get();

        return $query->result_array();
    }


    /**
     * Dados para widget do dashboard
     *
     * @param   int  id fornecedor
     * @return  array
     */
    public function total_pedidos_aberto($id_fornecedor = null)
    {
        $this->db->flush_cache();
        
        $this->db->select('SUM(total) AS total');
        $this->db->from('vw_pedidos');

        if ($id_fornecedor != null) {
            $this->db->where('id_fornecedor', $id_fornecedor);
        }

        $this->db->where('status', 0);
        $query = $this->db->get();
        return $query->row_array()['total'];
    }
}
