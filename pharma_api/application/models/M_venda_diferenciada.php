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

    public function gravar()
    {

        $this->db->trans_begin();

        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $id_tipo_venda = $this->session->userdata("id_tipo_venda");
        $elementos = explode(',', $this->input->post('opcoes'));

        $desconto = floatval(str_replace(',', '.', str_replace('.', '', $this->input->post("desconto"))));
        $comissao = floatval(str_replace(',', '.', str_replace('.', '', $this->input->post("comissao"))));

        $dias = $this->input->post('dias');
        $quantidade = $this->input->post('quantidade');
        $lote = $this->input->post('lote');

        $opcao_produto = $this->input->post('restricao');
        $option = $this->input->post('selectElements');

        $produtos = ($opcao_produto == 'TODOS') ? $this->estoque->get_rows('id', "id_fornecedor = {$id_fornecedor}") : explode(',', $this->input->post('produtos'));

        $dados = [];
        if ($option == 'ESTADOS') {
            foreach ($produtos as $p) {
                foreach ($elementos as $e) {
                    $dados = [
                        'id_fornecedor'       => $id_fornecedor,
                        'id_estado'           => $e,
                        'id_tipo_venda'       => $id_tipo_venda,
                        'id_produto'          => (isset($p['id'])) ? $p['id'] : $p,
                        'desconto_percentual' => $desconto,
                        'comissao'            => $comissao,
                        'quantidade'          => $quantidade,
                        'lote'                => $lote,
                        'dias'                => $dias
                    ];

                    $id = $this->verificarSeExisteVenda($dados['id_produto'], $dados['id_estado'], 'ESTADOS');

                    if ($id) {
                        $this->db->update($this->table, $dados, "id = {$id}", 1);
                    } else {
                        $this->db->insert($this->table, $dados);
                    }
                }
            }
        } else {
            foreach ($produtos as $p) {
                foreach ($elementos as $c) {
                    $dados = [
                        'id_fornecedor'       => $id_fornecedor,
                        'id_cliente'          => $c,
                        'id_tipo_venda'       => $id_tipo_venda,
                        'id_produto'          => (isset($p['id'])) ? $p['id'] : $p,
                        'desconto_percentual' => $desconto,
                        'comissao'            => $comissao,
                        'quantidade'          => $quantidade,
                        'lote'                => $lote,
                        'dias'                => $dias
                    ];

                    $id = $this->verificarSeExisteVenda($dados['id_produto'], $dados['id_cliente'], 'CLIENTES');

                    if ($id) {
                        $this->db->update($this->table, $dados, "id = {$id}", 1);
                    } else {
                        $this->db->insert($this->table, $dados);
                    }
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
        $this->db->where('codigo', $id);
        $this->db->where('promocao', '0');
        $this->db->where('id_fornecedor', $id_fornecedor);
        return $this->db->delete('vendas_diferenciadas');
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
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function verificarSeExisteVenda($id_produto, $param, $option, $id_fornecedor = null)
    {
        $this->db->select('*');
        $this->db->from('vendas_diferenciadas');

        if ($option === 'ESTADOS') {
            $this->db->where('id_estado', $param);
        
        } elseif($option === 'CODIGO') {
            $this->db->where('codigo', $param);
        
        } else {
            $this->db->where('id_cliente', $param);
        }

        $this->db->where('id_produto', $id_produto);
        $fornecedor_id = (!IS_NULL($id_fornecedor)) ? $id_fornecedor : $this->session->userdata('id_fornecedor');
        $this->db->where('id_fornecedor', $fornecedor_id);
        $this->db->where_not_in('regra_venda', 1);
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row_array()['id'];
    }

    public function listarEstadosRestantes()
    {
        $this->db->select("e.id, CONCAT(e.uf, ' - ', e.descricao) AS estado");
        $this->db->from("estados AS e");
        #$this->db->where("e.id NOT IN (( SELECT id_estado FROM vendas_diferenciadas WHERE vendas_diferenciadas.id_fornecedor = {$this->session->userdata('id_fornecedor')} ))");
        $this->db->order_by("e.descricao", "ASC");

        return $this->db->get()->result_array();
    }

    public function listaClientesRestantes()
    {
        $this->db->select("c.id, CONCAT(c.cnpj, ' - ', c.razao_social) AS cliente");
        $this->db->from("compradores AS c");
      #  $this->db->where("c.id NOT IN (( SELECT id_cliente FROM vendas_diferenciadas WHERE vendas_diferenciadas.id_fornecedor = {$this->session->userdata('id_fornecedor')} AND id_cliente IS NOT NULL ))");
        $this->db->order_by("c.razao_social", "ASC");

        return $this->db->get()->result_array();
    }
}

/* End of file: M_venda_diferenciada.php */
