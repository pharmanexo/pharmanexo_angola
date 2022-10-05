<?php

class Dashboard extends CI_Controller
{
    private $views;
    private $route;
    private $bio;

    public function __construct()
    {
        parent::__construct();

        $this->views = "admin/bionexo/dashboard";
        $this->route = base_url("admin/bionexo/dashboard");

        $this->bio = $this->load->database('bionexo', true);

        $this->load->model('m_compradores', 'compradores');
        $this->load->model('m_usuarios', 'usuario');
        $this->load->model("m_responsavel_depara", "resp");

    }

    public function index()
    {

        $page_title = "Dashboard De/Para Bionexo";

        # TEMPLATE
        $data['header'] = $this->template->header(['title' => $page_title]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts();

        $data['urlClose'] = "{$this->route}/close/";
        $novos = $this->compradores->getNovosProdutos();

        if ($this->session->nivel == 3) {
            $users = $this->usuario->getUserDepara();

            foreach ($users as $k => $user) {
                $m = $this->usuario->getMetaUser($user['id_usuario'], false, true);
                $users[$k]['total'] = 0;
                foreach ($m as $value) {
                    if ($value['id_usuario'] == $user['id_usuario']) {
                        $users[$k]['total'] = $value['total'];
                    }
                }

                $history = [];
                $month = $this->usuario->getMetaUser($user['id_usuario'], false, true, true);
                $hospitais = $this->usuario->getHospitaisAbertos($user['id_usuario'], 2);
                $hospitais_f = $this->usuario->getHospitaisFinalizados($user['id_usuario'], 2);
                $total = $this->usuario->getTotalHospitais($user['id_usuario'], 2);


                foreach ($month as $mm) {
                    $mm['mes_nome'] = nameMonth($mm['mes']);
                    $history[$mm['ano']][$mm['mes']] = $mm;
                }

                $dia = $this->usuario->getMetaUser($user['id_usuario'], true, false);

                if (!empty($hospitais)) {
                    foreach ($hospitais as $l => $hosp) {
                        $dados = $this->compradores->getDadosCatalogo($hosp['id']);
                        $hospitais[$l] = array_merge($hosp, $dados);
                    }
                }

                if (!empty($hospitais_f)) {
                    foreach ($hospitais_f as $h => $hosp) {
                        $dados = $this->compradores->getDadosCatalogo($hosp['id']);
                        $hospitais_f[$h]['novos'] = $dados['sem'];
                    }
                }

                $users[$k]['dia'] = (empty($dia['total'])) ? 0 : $dia['total'];
                $users[$k]['historico'] = $history;
                $users[$k]['hospitais'] = $hospitais;
                $users[$k]['finalizados'] = $hospitais_f;
                $users[$k]['total_hosp'] = $total;


            }

            $data['meta'] = $users;


        }

        $this->load->view("{$this->views}/admin", $data);
    }

    public function close($idCliente, $idUsuario)
    {
        if ($this->session->nivel == 3) {
            $aberto = $this->resp->find('*', "id_cliente = {$idCliente} AND id_usuario = {$idUsuario} and fim is null and integrador = 2", true);


            if (!empty($aberto)) {
                if ($this->resp->update(['id' => $aberto['id'], 'fim' => date('Y-m-d H:i:s', time())])) {
                    $warning = ['type' => 'success', 'message' => 'De/para finalizado!'];
                } else {
                    $warning = ['type' => 'warning', 'message' => 'Não existe um de/para iniciado para esse hospital'];
                }
            } else {
                $warning = ['type' => 'warning', 'message' => 'Não existe um de/para iniciado para esse hospital'];
            }
            $redir = $this->route;
        } else {
            $warning = ['type' => 'warning', 'message' => 'Somente administradores podem acessar essa função'];
            $redir = base_url('dashboard');
        }

        $this->session->set_userdata('warning', $warning);
        redirect($redir, 'refresh');

    }
}
