<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comissionamento extends MY_Model
{
    protected $table = 'comissionamento_pharmanexo';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function getComissao($id_fornecedor)
    {
        # code...
    }
}

/* End of file: Comissionamento.php */
