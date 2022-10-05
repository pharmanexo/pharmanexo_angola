<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_restricao_produto_cotacao extends CI_Model
{
    protected $table = 'restricoes_produtos_cotacoes';

    public function __construct()
    {
        parent::__construct();
    }

    public function find($id_fornecedor, $id_produto_sintese, $cd_produto_comprador, $cd_cotacao, $restricao = null)
    {
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('id_produto_sintese', $id_produto_sintese);
        $this->db->where('cd_produto_comprador', $cd_produto_comprador);
        $this->db->where('cd_cotacao', $cd_cotacao);

        if ( $restricao != null ) {
            $this->db->where('restricao', 1);
        } else {
             $this->db->where('restricao', 0);
        }

        return $this->db->get('restricoes_produtos_cotacoes')->row_array();
    }

    public function gravar($data)
    {
    	if ( $this->db->insert('restricoes_produtos_cotacoes', $data) ) {
        	return true;
        } else {
        	return false;
        }
    }

    public function excluir($cd_cotacao, $id_fornecedor)
    {
    	$this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor_logado', $id_fornecedor);

        $delete = $this->db->delete('restricoes_produtos_cotacoes');
       
        if ( $delete ) {
        	return true;
        } else {
        	return false;
        }
    }
}
