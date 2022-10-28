<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fornecedor extends MY_Model
{
    protected $table = 'fornecedores';
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

/* End of file: Fornecedor.php */
