<<<<<<< HEAD
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mensagem extends MY_Model
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

    /**
     * Obtem as mensagens de um destinatario e remetente
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  array
     */
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

    /**
     * Obtem as mensagens não lidas
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  array
     */
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

    /**
     * Cria um registro de mensagem
     *
     * @param - Array columns table mensagem
     * @return  bool
     */
    public function insertMensage($array)
    {

        return $this->db->insert('mensagens', $array);
    }

    /**
     * Remove uma mensagem por ID
     *
     * @param - INT ID da mensagem
     * @return  bool
     */
    public function deleteMessage($id_message)
    {

        $delete = $this->db->where('id', $id_message)
            ->update('mensagens', ['status' => '9']);

        return $delete;
    }

    /**
     * Remove todas a mensagem de um destinatario e remetente
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  bool
     */
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

    /**
     * Atualiza o status para lido de todas as mensagens de um destinatario e remetente
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  bool
     */
    public function readAllMessages($array)
    {
       $q = $this->db
            ->where('remetente', $array['remetente'])
            ->where('destinatario', $array['destinatario'])
            ->where('status', '0')
            ->update($this->table, ['data_leitura' => date('Y-m-d H:i:s'), 'status' => '1']);

        return $q;
    }
=======
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_mensagem extends MY_Model
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

    /**
     * Obtem as mensagens de um destinatario e remetente
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  array
     */
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

    /**
     * Obtem as mensagens não lidas
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  array
     */
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

    /**
     * Cria um registro de mensagem
     *
     * @param - Array columns table mensagem
     * @return  bool
     */
    public function insertMensage($array)
    {

        return $this->db->insert('mensagens', $array);
    }

    /**
     * Remove uma mensagem por ID
     *
     * @param - INT ID da mensagem
     * @return  bool
     */
    public function deleteMessage($id_message)
    {

        $delete = $this->db->where('id', $id_message)
            ->update('mensagens', ['status' => '9']);

        return $delete;
    }

    /**
     * Remove todas a mensagem de um destinatario e remetente
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  bool
     */
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

    /**
     * Atualiza o status para lido de todas as mensagens de um destinatario e remetente
     *
     * @param - Array(String remetente, String Destinatario)
     * @return  bool
     */
    public function readAllMessages($array)
    {
       $q = $this->db
            ->where('remetente', $array['remetente'])
            ->where('destinatario', $array['destinatario'])
            ->where('status', '0')
            ->update($this->table, ['data_leitura' => date('Y-m-d H:i:s'), 'status' => '1']);

        return $q;
    }
>>>>>>> hmg
}