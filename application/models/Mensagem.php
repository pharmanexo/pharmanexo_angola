<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mensagem extends MY_Model
{
    protected $table = 'mensagens';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = 'data_envio';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function getMessages($array)
    {
        $mensagens = $this->db
            ->group_start()
            ->where('remetente', $array['remetente'])
            ->where('destinatario', $array['destinatario'])
            ->where('status <>', '9')
            ->group_end()
            ->or_group_start()
            ->where('remetente', $array['destinatario'])
            ->where('destinatario', $array['remetente'])
            ->where('status <>', '9')
            ->group_end()
            ->order_by('data_enviado ASC')
            ->get('mensagens')
            ->result_array();

        return $mensagens;
    }

    public function getMsgUnRead($array)
    {
        $mensagens = $this->db
            ->select('COUNT(*) as qtd_msg')
            ->where('status', '0')
            ->where('remetente', $array['remetente'])
            ->where('destinatario', $array['destinatario'])
            ->get('mensagens')
            ->row_array();

        return $mensagens;
    }

    public function insertMensage($array)
    {

        $insert = $this->db->insert('mensagens', $array);

        return $insert;

    }

    public function deleteMessage($id_message)
    {

        $delete = $this->db->where('id', $id_message)
            ->update('mensagens', ['status' => '9']);

        return $delete;
    }

    public function deleteAllMessage($array)
    {

        $deleteAll = $this->db->group_start()
            ->where('remetente', $array['remetente'])
            ->where('destinatario', $array['destinatario'])
            ->group_end()
            ->or_group_start()
            ->where('remetente', $array['destinatario'])
            ->where('destinatario', $array['remetente'])
            ->group_end()
            ->update('mensagens', ['status' => '9']);

        return $deleteAll;
    }

    public function readAllMessages($array)
    {
       $q = $this->db
            ->where('remetente', $array['remetente'])
            ->where('destinatario', $array['destinatario'])
            ->where('status', '0')
            ->update($this->table, ['data_leitura' => date('Y-m-d H:i:s'), 'status' => '1']);

      return $q;
    }

}


