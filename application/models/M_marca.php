<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_marca extends CI_Model
{

    protected $table = 'marcas';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtem o registro de marca
     *
     * @param INT ID do registro de marca
     * @return array
     */
    public function get_row($id)
    {
        if (!isset($id)) return [];

        $this->db->where("id", $id);
        return $this->db->get($this->table)->row_array();
    }

    /**
     * find
     *
     * @param   string  $fields
     * @param   string  $where
     * @param   boolean $single
     * @return  array
     */
    public function find($fields = '*', $where = NULL, $single = FALSE, $order = NULL, $group = null)
    {
        $method = ($single == TRUE) ? 'row_array' : 'result_array';

        $this->db->select($fields);
        $this->db->from($this->table);
        if (isset($where)) $this->db->where($where);
        if (isset($order)) $this->db->order_by($order);
        if (isset($group)) $this->db->group_by($group);

        $r = $this->db->get();

        return $r->$method();
    }
}