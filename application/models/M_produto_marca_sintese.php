<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_produto_marca_sintese extends MY_Model
{

	protected $table = 'produtos_marca_sintese';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }



}

/* End of file .php */