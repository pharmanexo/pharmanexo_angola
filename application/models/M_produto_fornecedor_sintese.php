<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_produto_fornecedor_sintese extends MY_Model
{

    protected $table = 'produtos_fornecedores_sintese';
    protected $primary_key = 'id_sintese';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
    	parent::__construct();

    	$this->table = "produtos_fornecedores_sintese";
    }

}

/* End of file .php */