<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_marca extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->table = "marcas";
	}

	public function get_row($id){
		if (!isset($id)) return [];

		$this->db->where("id", $id);
		return $this->db->get($this->table)->row_array();
	}

	public function get_rows($fields = NULL, $where = NULL, $order = NULL, $limit = NULL, $offset = NULL)
	{
		$this->db->select((isset($fields)) ? $fields : "*");
		if (isset($where)) $this->db->where($where);
		if (isset($order)) $this->db->order_by($order);
		if (isset($limit)) $this->db->limit($limit);
		if (isset($offset)) $this->db->offset($offset);

		$q = $this->db->get($this->table);

		if ($q->num_rows() > 0) {
			return $q->result_array();
		} else {
			return NULL;
		}
	}


}
