<?php

class Notifications extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getNotifications()
    {

        if (isset($_SESSION['id_fornecedor'])) {
            $notifications = $this->db->select("*")
                ->where("( (id_usuario = {$this->session->id_usuario} AND id_fornecedor = {$this->session->id_fornecedor}) OR id_fornecedor = {$this->session->id_fornecedor} ) AND status = 0")
                ->get('notifications')
                ->result_array();

            if (!isset($notifications) || empty($notifications)) {
                $notifications = 'NULL';
            }


            $this->output->set_content_type('application/json')->set_output(json_encode($notifications));
        }
    }

    public function removeAll()
    {

        $this->db->where('id_fornecedor', $this->session->id_fornecedor);
        if ($this->db->update('notifications', ['status' => '1'])) {
            $warning = [
                'type' => 'success',
                'message' => 'Notificações lidas.'
            ];
        } else {
            $warning = [
                'type' => 'error',
                'message' => 'Deu um erro, comunique o suporte.'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($warning));

    }

    public function notificationDeivis()
    {
        $data = date("Y-m-d", time());

        $prods = $this->db
            ->where_in('id_pfv', '30001, 30002')
            ->where('id_fornecedor', 5025)
            ->where("date(data_criacao) >= '{$data}'")
            ->where("notificado", 0)
            ->get('cotacoes_produtos')
            ->result_array();

        var_dump($prods);
        exit();


    }
}