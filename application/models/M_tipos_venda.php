<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_tipos_venda extends MY_Model
{
    protected $table = 'tipos_venda';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }
}

