<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_produto_cliente_depara extends MY_Model
{

    protected $table = 'produtos_clientes_depara';
    protected $primary_key = 'id_produto_sintese';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
    	parent::__construct();

    	$this->table = "produtos_clientes_depara";
    }

}

/* End of file .php */