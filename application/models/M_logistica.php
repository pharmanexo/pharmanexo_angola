<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_logistica extends MY_Model
{
    protected $table = 'ordens_compra';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function getDetails($id)
    {
        $this->db->select('o.ordem_compra, u.nome_fantasia, u.razao_social, u.cnpj, u.cidade, u.estado, o.valor_total, o.data_emissao, p.descricao as condicao_pagamento');
        $this->db->from('ordens_compra o');
        $this->db->join('vw_clientes u', 'u.id = o.id_cliente', 'INNER');
        $this->db->join('formas_pagamento p', 'o.condicao_pagamento = p.id', 'INNER');
        $this->db->where('o.id', $id);
        $this->db->where('o.id_fornecedor', $this->session->userdata('id_fornecedor'));

        $query = $this->db->get();
        return $query->row_array();
    }

    public function getProdutoOC($oc)
    {
        $this->db->select("p.codigo, p.produto_descricao, poc.qtd_solicitada, poc.preco as preco, (poc.qtd_solicitada * preco) as total");
        $this->db->from('produtos_ordem_compra poc');
        $this->db->join("vw_produtos_fornecedores p", "poc.id_produto = p.id");
        $this->db->where("poc.id_ordem_compra", $oc);

        return $this->db->get()->result_array();
    }

    /**
     * Dados para widget do dashboard
     *
     * @param   int  id fornecedor
     * @return  array
     */
    public function total_ordem_compras($id_fornecedor = null)
    {

        $mes = date('m', time());
        $ano = date('Y', time());

        # Obtem o valor total dos produtos enviados e aprovados com ordem de compra
        $this->db->select("SUM(cp.preco_marca * cp.qtd_solicitada) valor_total");
        $this->db->from('ocs_sintese ocs');
        $this->db->join('ocs_sintese_produtos oc_prod', 'oc_prod.id_ordem_compra = ocs.id');
        $this->db->join('cotacoes_produtos cp', 
           "cp.id_fornecedor = ocs.id_fornecedor AND
            cp.cd_cotacao = ocs.Cd_Cotacao AND
            cp.cd_produto_comprador = oc_prod.Cd_Produto_Comprador AND
            cp.id_produto = oc_prod.Id_Produto_Sintese
        ");

        if ($id_fornecedor != null) {

            $this->db->where('ocs.id_fornecedor', $id_fornecedor);
        }

        $this->db->where("MONTH(ocs.Dt_Ordem_Compra) = '{$mes}' AND YEAR(ocs.Dt_Ordem_Compra) = '{$ano}'");
        $this->db->where('ocs.pendente', 0);
        $this->db->where('cp.submetido', 1);
        return $this->db->get()->row_array()['valor_total'];
    }
}

/* End of file: M_logistica.php */
