<?php

class Auditor{

    private $db;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->db = $this->CI->db;

    }

    /**
     * Função para gerar log de ações do sistema
     *
     * @param - String nome da ação
     * @param - String nome do modulo da ação
     * @param - Array da ação
     *
     * @return CI_Controller
     */
    public function setlog($action, $module, $data)
    {
        $data = [
            'action' => $action,
            'module' => $module,
            'url' => "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}",
            'id_usuario' => $this->CI->session->id_usuario,
            'origin' => $_SERVER['REMOTE_ADDR'],
            'message' => json_encode($data),
        ];

        return $this->db->insert("ci_logs", $data);
    }
}