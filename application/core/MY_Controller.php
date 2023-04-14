<?php

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

      /*  $this->load->model('Grupo_usuario_rota', 'gur');

        date_default_timezone_set('America/Sao_Paulo');
        $this->form_validation->set_error_delimiters('<span>', '</span>');

        // $this->defineMatriz();

        $logado = $this->session->userdata("logado");

        if ($logado != 1) {
            $this->session->sess_destroy();
            redirect(base_url('login'));
        }

        $routes = $this->session->routes;
        $url = $_SERVER['REQUEST_URI'];

        $check = false;

        foreach ($routes as $route) {

            //exceções
            if (strpos($url, '/fornecedor/usuarios/perfil')) {
                $check = true;
                break;
            } else {
                if (strpos($url, $route['url']) > 0) {
                    $check = true;
                    break;
                }
            }

        }

        if (!$check) {
            $array = array(
                'type' => 'warning',
                'message' => 'Acesso não autorizado, consulte o administrador da empresa.'
            );

            $this->session->set_userdata('warning', $array);

            redirect(base_url('dashboard'));
        }*/

    }

    public function __destruct()
    {
        $this->db->close();
    }
}

class Adesao extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION['dados']) || $_SESSION['validLogin'] == false) {
            redirect(base_url());
        }


    }

}

class Admin_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('America/Sao_Paulo');
        $this->form_validation->set_error_delimiters('<span>', '</span>');

        $this->defineMatriz();

        $logado = $this->session->userdata("logado");
        $administrador = $this->session->userdata("administrador");

        if ($logado != 1 || $administrador != 1) {
            $this->session->sess_destroy();
            redirect(base_url('login'));
        }
    }

    public function __destruct()
    {
        $this->db->close();
    }
}

class Rep_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('America/Sao_Paulo');
        $this->form_validation->set_error_delimiters('<span>', '</span>');

        $this->defineMatriz();

        $logado = $this->session->userdata("logado");
        if ($logado != 1) {
            $this->session->sess_destroy();
            redirect(base_url('representantes/login'));
        }

    }

    public function __destruct()
    {
        $this->db->close();
    }
}

class Conv_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('America/Sao_Paulo');
        $this->form_validation->set_error_delimiters('<span>', '</span>');

        $this->defineMatriz();

        $logado = $this->session->userdata("validLogin");

        if (!$logado) {
            $this->session->sess_destroy();
            redirect(base_url('/login'));
        }

    }

    public function __destruct()
    {
        $this->db->close();
    }
}
