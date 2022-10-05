<?php


class NovosProdutos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_compradores', 'comp');
        $this->load->model('m_usuarios', 'user');
    }

    public function bionexo()
    {
        $users = $this->user->getUserDepara();

        foreach ($users as $k => $user) {
            $novos = $this->comp->getNovosProdutos($user['id_usuario']);
            $encontrados = 0;

            if (!empty($novos)) {
                foreach ($novos as $novo) {
                    if ($novo > 0) {
                        $users[$k]['notificar'] = true;
                    }
                }
            }

        }
        $destinatarios = "";
        foreach ($users as $user) {

            if (isset($user['notificar'])) {
                $destinatarios .= $user['email'] . ", ";
            }
        }

        $destinatarios = rtrim($destinatarios, ', ');


        $notificar = [
            "to" => $destinatarios,
            "greeting" => "",
            "subject" => "Novos produtos sem De/Para",
            "message" => "Prezado (a). <br><br><br> Existem hospitais com novos produtos sem De/Para, verifique a lista em seu dashboard. <br> <br> Atenciosamente, <br><br> Portal Pharmanexo.",
            "oncoprod" => 0
        ];

        $this->notify->send($notificar);
    }

}
