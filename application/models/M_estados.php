<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_estados extends MY_Model
{
    protected $table = 'estados';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'uf';
    protected $order_direction = 'ASC';

    public function todosEstados()
    {
        $consulta = $this->db->get('estados');
        if ($consulta->num_rows() > 0) {
            return $consulta;
        } else {
            return false;
        }
    }

    public function getList()
    {
        $this->db->select("e.id, CONCAT(e.uf, ' - ', e.descricao) AS estado");
        $this->db->from('estados AS e');
        return $this->db->get()->result_array();
    }
}
