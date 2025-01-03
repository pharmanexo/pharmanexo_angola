<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_restricao_produto_cotacao2 extends CI_Model
{
    protected $table = 'restricoes_produtos_cotacoes';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtem as restrições do produto integrador
     *
     * @param - String nome do integrador
     * @param - Int ID do fornecedor
     * @param - String numero da cotacao
     * @param - String codigo do produto no integrador
     * @param - Int ID do produto sintese
     * @return  Array/null
     */
    public function find($integrador, $id_fornecedor, $cd_cotacao, $cd_produto_comprador, $id_produto_sintese = null)
    {

        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('integrador', $integrador);
        $this->db->where('cd_produto_comprador', $cd_produto_comprador);

        if ( strtoupper($integrador) == 'SINTESE' ) {

            $this->db->where('id_produto_sintese', $id_produto_sintese);
        }

        return $this->db->get('restricoes_produtos_cotacoes')->row_array();
    }

    /**
     * Salva a restrição de um produto na cotação
     *
     * @param - Array
     * @return  bool
     */
    public function gravar($data)
    {
    	if ( $this->db->insert('restricoes_produtos_cotacoes', $data) ) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
     * Exclui todas as restrições de uma cotação
     *
     * @param - String numero da cotação
     * @param - INT Id do fornecedor
     * @return  bool
     */
    public function excluir($cd_cotacao, $id_fornecedor)
    {
    	$this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);

        $delete = $this->db->delete('restricoes_produtos_cotacoes');

        if ( $delete ) {
        	return true;
        } else {
        	return false;
        }
    }

    /**
     * Remove a restricao de um produto e insere um nova
     *
     * @param - Array post
     * @param - INT Id do fornecedor
     * @return  bool
     */
    public function renewProductRestriction($post, $id_fornecedor)
    {

        # Exclui o registro do produto
        $this->deleteProductRestriction($post['integrador'], $post['cd_cotacao'], $id_fornecedor, $post['cd_produto_comprador'], $post['id_produto_sintese']);

        # Caso exista alguma restrição do produto, insere
        if ( isset($post['restricao']) || isset($post['ol']) || isset($post['sem_estoque']) ) {

            $novo = [
                'integrador' => $post['integrador'],
                'cd_cotacao' => $post['cd_cotacao'],
                'id_fornecedor' => $id_fornecedor,
                'id_usuario' => $this->session->id_usuario,
                'cd_produto_comprador' => $post['cd_produto_comprador'],
                'id_produto_sintese' => ( $post['integrador'] == 'SINTESE' ) ? $post['id_produto_sintese'] : null,
                'ol' => ( isset($post['ol']) ) ? 1 : 0,
                'sem_estoque' => ( isset($post['sem_estoque']) ) ? 1 : 0,
                'restricao' => ( isset($post['restricao']) ) ? 1 : 0,
                'estoque' => $post['estoque']
            ];

            $this->db->insert($this->table, $novo);
        }

        return true;
    }

    /**
     * Remove a restricao de um produto
     *
     * @param - String nome do integrador
     * @param - String numero da cotação
     * @param - INT Id do fornecedor
     * @param - String codigo do produto comprador
     * @param - INT ID do produto sintese
     * @return  bool
     */
    public function deleteProductRestriction($integrador, $cd_cotacao, $id_fornecedor, $cd_produto_comprador, $id_produto_sintese = null)
    {

        # Exclui o registro do produto
        $this->db->where('cd_cotacao', $cd_cotacao);
        $this->db->where('id_fornecedor', $id_fornecedor);
        $this->db->where('cd_produto_comprador', $cd_produto_comprador);

        if ( $integrador == 'SINTESE' ) {

            $this->db->where('id_produto_sintese', $id_produto_sintese);
        } else {

            $this->db->where('id_produto_sintese is null');
        }

        $del = $this->db->delete($this->table);

        if ( $del ) {

            return true;
        } else {

            return false;
        }
    }
}
