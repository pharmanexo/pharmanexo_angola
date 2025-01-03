<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_configuracao_marca_comprador extends MY_Model
{

    protected $table = 'configuracao_marca_comprador';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca o registro pelo comprador e fornecedor
     *
     * @param - INT ID do comprador
     * @param - INT ID do fornecedor
     * @return int/false
     */
    public function verifyExist($id_cliente, $id_fornecedor)
    {
    	$this->db->where("id_fornecedor", $id_fornecedor);
    	$this->db->where("id_cliente", $id_cliente);
    	$config = $this->db->get($this->table)->row_array();

    	if ( isset($config) && !empty($config) ) {
    		
    		return $config['id'];
    	} else {

    		return false;
    	}
    }
}