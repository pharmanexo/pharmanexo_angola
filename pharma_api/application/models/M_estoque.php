<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_estoque extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function CountAll()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $produto = $this->input->post('product');
        $marca = $this->input->post('marca');
        $this->db->select("*");
        if ($tipo_usuario == 1) $this->db->where('id_fornecedor', $id_fornecedor); //somente para fornecedores
        $this->db->where('ativo', 1);
        $this->db->where('aprovado', 1);
        $this->db->where('estoque >', 0);
        $this->db->where('id_estado', $this->session->id_estado);
        $this->db->where('validade >', date('Y-m-d'));
        if ($produto != null) $this->db->like('produto_descricao', $produto, 'both');
        if ($marca != null) $this->db->where('id_marca', $marca);
        $consulta = $this->db->get();
        return $consulta;
    }

    public function produtosFornecedor($inicio, $qnt_result_pg)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $marca = $this->input->post('marca');
        $produto = $this->input->post('product');
        $this->db->select(" CONCAT(pfv.nome_comercial,' - ',pfv.apresentacao) as produto_descricao, pfv.id, pfv.id_marca, m.marca, pfv.id_sintese, pfv.id_produto, pfv.id_estado, pfv.codigo, pfv.porcentagem_campanha, pfv.ativo, (pfv.preco_unidade*pfv.quantidade_unidade) as preco, pfv.preco_unidade, pfv.estoque AS quantidade, pfv.validade, pfv.quantidade_unidade FROM produtos_fornecedores_validades pfv INNER JOIN marcas m ON m.id = pfv.id_marca  ", false);
        if ($tipo_usuario == 1) $this->db->where('pfv.id_fornecedor', $id_fornecedor); //somente para fornecedores
        $this->db->where('pfv.ativo', 1);
        $this->db->where('pfv.aprovado', 1);
        $this->db->where('pfv.estoque >', 0);
        $this->db->where('pfv.id_estado',  $this->session->id_estado);
        $this->db->where('pfv.validade >', date('Y-m-d'));
        if ($marca != null) $this->db->where('pfv.id_marca', $marca);
        if (isset($produto) && !empty($produto)) $this->db->having("produto_descricao like '%{$produto}%' ");
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

        $this->db->select("*");
        if ($tipo_usuario == 1) $this->db->where('id_fornecedor', $id_fornecedor); //somente para fornecedores
        $this->db->where('ativo', 1);
        $this->db->where('aprovado', 1);
        $this->db->where('id', $id);
        return $this->db->get('vw_produtos_fornecedores')->row_array();
    }

    public function marcasFornecedor()
    {
        $tipo_usuario = $this->session->userdata("tipo_usuario");
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $marca = $this->input->post('marca');
        $this->db->select(" DISTINCT pfv.id_marca, m.marca from produtos_fornecedores_validades pfv  INNER JOIN marcas m ON m.id=pfv.id_marca ", false);
        if ($tipo_usuario == 1) $this->db->where('pfv.id_fornecedor', $id_fornecedor); //somente para fornecedores 
        $this->db->order_by('m.marca');
        $this->db->where('pfv.ativo', 1);
        $this->db->where('pfv.aprovado', 1);
        $this->db->where('pfv.estoque >', 0);
        $this->db->where('pfv.id_estado', 8);
        $this->db->where('pfv.validade >', date('Y-m-d'));
        if ($marca != null) $this->db->where('pfv.id_marca', $marca);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }

    public function excluir($id)
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->where('id', $id);
        $this->db->where('id_fornecedor', $id_fornecedor);
        // $this->db->delete('estoque');
        $this->db->set('ativo', 0, FALSE);
        $this->db->update('produtos_fornecedores_valiades');
        return;
    }

    public function variacaoValidades()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("(SELECT count(TIMESTAMPDIFF(MONTH, now(), pf1.validade )) FROM precos_fornecedores pf1 WHERE TIMESTAMPDIFF(MONTH, now(), pf1.validade )BETWEEN 1 AND 2 AND pf1.estado ='ES' and  pf1.id_fornecedor = " . $id_fornecedor . ") validade_1_2,(SELECT count(TIMESTAMPDIFF(MONTH, now(), pf2.validade )) FROM precos_fornecedores pf2 WHERE TIMESTAMPDIFF(MONTH, now(), pf2.validade )BETWEEN 3 AND 6 AND pf2.estado ='ES' and   pf2.id_fornecedor =  " . $id_fornecedor . ") validade_3_6, (SELECT count(TIMESTAMPDIFF(MONTH, now(), pf3.validade )) FROM precos_fornecedores pf3 WHERE TIMESTAMPDIFF(MONTH, now(), pf3.validade )BETWEEN 7 AND 12 AND pf3.estado ='ES' and   pf3.id_fornecedor =  " . $id_fornecedor . ") validade_7_12, (SELECT count(TIMESTAMPDIFF(MONTH, now(), pf4.validade )) FROM precos_fornecedores pf4 WHERE TIMESTAMPDIFF(MONTH, now(), pf4.validade )BETWEEN 12 AND 18 AND pf4.estado ='ES' and   pf4.id_fornecedor =  " . $id_fornecedor . ") validade_13_18, (SELECT count(TIMESTAMPDIFF(MONTH, now(), pf5.validade )) FROM precos_fornecedores pf5 WHERE TIMESTAMPDIFF(MONTH, now(), pf5.validade )> 18 AND pf5.estado ='ES' and   pf5.id_fornecedor =  " . $id_fornecedor . ") validade_18 ", false);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }
    //
    public function valorVariacaoValidades()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("(SELECT sum(TIMESTAMPDIFF(MONTH, now(), pf1.validade ) * pf1.quantidade*pf1.preco_unidade) FROM precos_fornecedores pf1 WHERE TIMESTAMPDIFF(MONTH, now(), pf1.validade )BETWEEN 1 AND 2 AND pf1.estado ='ES' and  pf1.id_fornecedor = " . $id_fornecedor . ") validade_1_2,(SELECT sum(TIMESTAMPDIFF(MONTH, now(), pf2.validade ) * pf2.quantidade * pf2.preco_unidade) FROM precos_fornecedores pf2 WHERE TIMESTAMPDIFF(MONTH, now(), pf2.validade )BETWEEN 3 AND 6 AND pf2.estado ='ES' and   pf2.id_fornecedor =  " . $id_fornecedor . ") validade_3_6, (SELECT sum(TIMESTAMPDIFF(MONTH, now(), pf3.validade )* pf3.quantidade * pf3.preco_unidade) FROM precos_fornecedores pf3 WHERE TIMESTAMPDIFF(MONTH, now(), pf3.validade )BETWEEN 7 AND 12 AND pf3.estado ='ES' and   pf3.id_fornecedor =  " . $id_fornecedor . ") validade_7_12, (SELECT sum(TIMESTAMPDIFF(MONTH, now(), pf4.validade ) * pf4.quantidade * pf4.preco_unidade) FROM precos_fornecedores pf4 WHERE TIMESTAMPDIFF(MONTH, now(), pf4.validade )BETWEEN 12 AND 18 AND pf4.estado ='ES' and   pf4.id_fornecedor = " . $id_fornecedor . ") validade_13_18, (SELECT sum(TIMESTAMPDIFF(MONTH, now(), pf5.validade ) * pf5.quantidade *pf5.preco_unidade) FROM precos_fornecedores pf5 WHERE TIMESTAMPDIFF(MONTH, now(), pf5.validade )> 18 AND pf5.estado ='ES' and   pf5.id_fornecedor = " . $id_fornecedor . ") validade_18", false);
        $consulta = $this->db->get();
        if ($consulta->num_rows() > 0)
            return $consulta;
        else
            return false;
    }
    //
    public function totalEstoque()
    {
        $id_fornecedor = $this->session->userdata("id_fornecedor");
        $this->db->select("SUM(estoque) As quantidade FROM produtos_fornecedores_validades  ", false);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('pfv.ativo', 1);
        $this->db->where('pfv.aprovado', 1);
        $this->db->where('pfv.estoque >', 0);
        $this->db->where('pfv.id_estado', 8);
        $this->db->where('pfv.validade >', date('Y-m-d'));
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

        $this->db->from("vw_produtos");

        $query = $this->db->get();

        return $query->result_array();
    }


    public function getTotalItems()
    {
        $this->db->select('SUM(p.estoque) AS total, e.descricao, e.uf, e.id');
        $this->db->from('produtos_fornecedores_validades p');
        $this->db->join('estados e', 'p.id_estado = e.id', 'INNER');
        $this->db->where('p.id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->where('p.aprovado', 1);
        $this->db->where('p.ativo', 1);
        $this->db->where('p.validade >', date('Y-m-d'));
        $this->db->group_by('e.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTotalCotacoes()
    {
        $this->db->select('COUNT(*) AS cotacao, uf_comprador');
        $this->db->from('cotacoes_produtos');
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->group_by('uf_comprador');

        return $this->db->get()->result_array();
    }

    public function getValorCotacoes()
    {
        $this->db->select("SUM(qtd_solicitada * preco_oferta) AS total, uf_comprador AS uf");
        $this->db->from('cotacoes_produtos');
        $this->db->where('submetido', 1);
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->group_by('uf_comprador');

        return $this->db->get()->result_array();
    }

    public function totalCotacoesAberto()
    {
        $this->db->select('SUM(qtd_solicitada * preco_oferta) AS total');
        $this->db->from('cotacoes_produtos');
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        $this->db->where('submetido', 0);

        return $this->db->get()->row_array()['total'];
    }
}
