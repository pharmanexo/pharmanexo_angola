<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_historico_ordem_compra extends MY_Model
{
    protected $table = 'historico_ordem_compra';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    # code...

}

/* End of file: M_historico_ordem_compra.php */
