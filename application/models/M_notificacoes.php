<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_notificacoes extends MY_Model
{
    protected $table = 'notifications';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'id';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function getNotifications()
    {

        $this->db->select("*");
        $this->db->where('id_usuario', $this->session->id_usuario);
        $this->db->where('status', 0);

        if ( isset($this->session->id_fornecedor) ) {
            
            $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        } else {

            $this->db->where('id_fornecedor is null');
        }

        $notifications = $this->db->get('notifications')->result_array();

        return $notifications;
    }

    public function readAll($id_usuario, $id_fornecedor = null)
    {

        $this->db->where('id_usuario', $id_usuario);
        $this->db->where('status', 0);

        if ( isset($id_fornecedor) ) {
            
            $this->db->where('id_fornecedor', $id_fornecedor);
        } else {

            $this->db->where('id_fornecedor is null');
        }

        if($this->db->update('notifications', ['status' => '1', 'data_leitura' => date('Y-m-d H:i:s')])) {

            return true;
        } else {

            return false;
        }
    }
}
