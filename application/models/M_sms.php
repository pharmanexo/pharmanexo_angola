<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sms extends MY_Model
{
    protected $table = 'sms_config';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

}

/* End of file: M_cliente.php */
