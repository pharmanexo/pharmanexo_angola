<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_status_ordem_compra extends MY_Model
{
    protected $table = 'ocs_sintese_status';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function getStatus()
    {
        $this->db->select('descricao AS status_ordem_compra');
        $this->db->from($this->table);
        $this->db->order_by('descricao', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }
}

/* End of file: M_status_ordem_compra.php */
