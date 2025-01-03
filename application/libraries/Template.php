<?php
/**
 * Created by PhpStorm.
 * User: dutra
 * Date: 08/06/2019
 * Time: 22:10
 * Template padronizado
 */

class Template
{
    public function homeheader($data)
    {

        $default = [];

        return $this->CI->load->view('templates/header', array_merge($default, $data), TRUE);
    }

    public function homeheading($data)
    {

        $default = [];

        return $this->CI->load->view('templates/heading', array_merge($default, $data), TRUE);
    }

    public function homebanner($data)
    {

        $default = [];

        return $this->CI->load->view('templates/banner', array_merge($default, $data), TRUE);
    }



    public function homenavbar($data)
    {
        $logo = ASSETS_PATH . "img/123456.png";

        $default = [
            'logo' => "<img src='{$logo}' class='img-fluid' alt=''>"
        ];

        return $this->CI->load->view('templates/navbar', array_merge($default, $data), TRUE);
    }

    public function homesidebar($data)
    {

        $default = [];

        return $this->CI->load->view('templates/sidebar', array_merge($default, $data), TRUE);
    }


    public function homescripts($data)
    {

        $default = [];

        return $this->CI->load->view('templates/scripts_home', array_merge($default, $data), TRUE);
    }

    public function homefooter($data)
    {

        $default = [];

        return $this->CI->load->view('templates/footer', array_merge($default, $data), TRUE);
    }

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->CI->load->model('m_notificacoes', 'notificacao');
    }


    public function header($data = [])
    {
        $default = [];

        if ($this->CI->session->id_fornecedor == 20) {

            $ocs = $this->CI->db->where('pendente', 1)->where('id_fornecedor', $this->CI->session->id_fornecedor)->get('ocs_sintese')->result_array();
            $total = count($ocs);

            if ($total > 0){

                $default['alertOC'] = ['type' => 'warning', 'message' => "Existe(m) {$total} pedido(s) aguardando aprovação"];
            }

        }

        // warning Message
        if ($this->CI->session->has_userdata('warning')) {
            $default['warning'] = json_encode($this->CI->session->warning);
            $this->CI->session->unset_userdata('warning');
        }

        $data = array_merge($data, $default);


        return $this->CI->load->view('template/header', $data, TRUE);
    }

    public function navbar($data = [])
    {
        $logo_n = $this->CI->session->logo;
        $id_usuario = $this->CI->session->id_usuario;

        $logo = "/images/usuarios/{$id_usuario}/{$logo_n}";

        $default = [
            'logo' => base_url((!empty($logo_n)) ? $logo : "/images/usuarios/no-user.png")
        ];


        $this->CI->session->set_userdata('usuario_imagem', $default['logo']);

        $data['notificacoes'] = $this->CI->notificacao->getNotifications();
        $data['read_all'] = base_url('dashboard/readAll');

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/navbar', $data, TRUE);
    }

    public function sidebar($data = [], $view = 'sidebar_painel')
    {
        $default = [
            'routes' => $this->build_menu()
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view("template/{$view}", $data, TRUE);
    }

    public function scripts($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/scripts', $data, TRUE);
    }

    public function heading($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/heading', $data, TRUE);
    }

    public function footer($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/footer', $data, TRUE);
    }

    public function menu_correio($data = [])
    {

        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/menu_correio', $data, TRUE);


    }

    public function fragment($view, $data)
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view($view, $data, TRUE);
    }

    private function build_menu()
    {
        $routes = $this->CI->session->routes;
        $menu = [];
        $url = $this->CI->uri->uri_string();

        foreach ($routes as $key => $route) {
            if ($url == $route['url']) {
                $routes[$key]['class'] = 'navigation__active';
            }
        }

        foreach ($routes as $r) {
            // sub-menu
            if (is_null($r['id_parente'])) {
                $menu[$r['id']] = $r;
                $menu[$r['id']]['reference'] = str_replace(" ", "", $r['rotulo']) . $r['id'];
            }
        }

        foreach ($routes as $r) {
            // sub-menu
            if (intval($r['id_parente']) > 0) {

                // if( isset($this->CI->session->id_usuario) && $this->CI->session->id_usuario != 187 && $r['id'] == 159  ) {

                //     continue;
                // } 


                $menu[$r['id_parente']]['submenu'][] = $r;
            }
        }


        foreach ($menu as $key => $r) {

            if (isset($r['submenu'])) {

                if (in_array('navigation__active', array_column($r['submenu'], 'class'))) {

                    $menu[$key]['class'] = 'navigation__sub--active';
                }


            }

        }

//        var_dump($menu);exit();

        return $menu;
    }

}

class TemplateMP
{
    public function __construct()
    {
        $this->CI = &get_instance();
    }


    public function header($data = [])
    {
        $default = [
            'title' => 'Home',
            'tipo_usuario' => $this->CI->session->userdata("tipo_usuario"),
            'logado' => $this->CI->session->userdata("logado")
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/marketplace/header', $data, TRUE);
    }

    public function navbar($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/marketplace/navbar', $data, TRUE);
    }

    public function sidebar($data = [], $view = 'sidebar_painel')
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view("template/marketplace/{$view}", $data, TRUE);
    }

    public function scripts($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/marketplace/scripts', $data, TRUE);
    }

    public function heading($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/marketplace/heading', $data, TRUE);
    }


    public function footer($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/marketplace/footer', $data, TRUE);
    }
}

class TemplateCC
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }


    public function header($data = [])
    {
        $default = [];

        // warning Message
        if ($this->CI->session->has_userdata('warning')) {
            $default['warning'] = json_encode($this->CI->session->warning);
            $this->CI->session->unset_userdata('warning');
        }

        $data = array_merge($data, $default);


        return $this->CI->load->view('compra-coletiva/template/header', $data, TRUE);
    }

    public function navbar($data = [])
    {
        $default = [
            'logo' => 'https://www.pharmanexo.com.br/images/img/logo-white.png'
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view('compra-coletiva/template/navbar', $data, TRUE);
    }

    public function sidebar($data = [], $view = 'sidebar_painel')
    {
        $default = [
            'routes' => $this->build_menu()
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view("compra-coletiva/template/{$view}", $data, TRUE);
    }

    public function scripts($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('compra-coletiva/template/scripts', $data, TRUE);
    }

    public function heading($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('compra-coletiva/template/heading', $data, TRUE);
    }


    public function footer($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('compra-coletiva/template/footer', $data, TRUE);
    }

    private function build_menu()
    {
        $routes = $this->CI->session->routes;
        $menu = [];

        foreach ($routes as $r){
            // sub-menu
            if (is_null($r['id_parente'])) {
                $menu[$r['id']] = $r;
                $menu[$r['id']]['reference'] = str_replace(" ", "", $r['rotulo']) . $r['id'];
            }
        }

        foreach ($routes as $r) {
            // sub-menu
            if (intval($r['id_parente']) > 0) {
//                # Gambiarra Eric
//                if( isset($this->CI->session->id_usuario) && $this->CI->session->id_usuario != 187 && $r['id'] == 159  ) {
//
//                    continue;
//                }

                $menu[$r['id_parente']]['submenu'][] = $r;
            }
        }

        #var_dump($menu);exit();

        return $menu;
    }

}

class TemplateRep
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }


    public function header($data = [])
    {
        $default = [];

        // warning Message
        if ($this->CI->session->has_userdata('warning')) {
            $default['warning'] = json_encode($this->CI->session->warning);
            $this->CI->session->unset_userdata('warning');
        }

        $data = array_merge($data, $default);


        return $this->CI->load->view('template/rep/header', $data, TRUE);
    }

    public function navbar($data = [])
    {
        $logo_n = $this->CI->session->logo;
        $id_usuario = $this->CI->session->id_usuario;

        $logo = "/images/usuarios/{$id_usuario}/{$logo_n}";

        $uri = $this->CI->uri->segment_array();

        if ($uri['1'] == 'pharma'){
            $logosys = base_url('images/img/BRPharma_small.png');
        }else{
            $logosys = base_url('images/img/123456.png');
        }

        $default = [
            'logo' => base_url((!empty($logo_n)) ? $logo : "/images/usuarios/no-user.png"),
            'logoSistema' => $logosys
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/rep/navbar', $data, TRUE);
    }

    public function sidebar($data = [], $view = 'sidebar_painel')
    {

        if (isset($_SESSION['pharma']) && $_SESSION['pharma'] == 1){

            $data['cliente'] = $this->CI->db->where('id', $_SESSION['id_cliente'])->get('compradores')->row_array();
        }

        $default = [
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view("template/rep/{$view}", $data, TRUE);
    }

    public function scripts($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/rep/scripts', $data, TRUE);
    }

    public function heading($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/rep/heading', $data, TRUE);
    }


    public function footer($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/rep/footer', $data, TRUE);
    }


}

class TemplateConv
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }


    public function header($data = [])
    {
        $default = [];

        // warning Message
        if ($this->CI->session->has_userdata('warning')) {
            $default['warning'] = json_encode($this->CI->session->warning);
            $this->CI->session->unset_userdata('warning');
        }

        $data = array_merge($data, $default);


        return $this->CI->load->view('template/conv/header', $data, TRUE);
    }

    public function navbar($data = [])
    {
        $logo_n = $this->CI->session->logo;
        $id_usuario = $this->CI->session->id_usuario;

        $logo = "/images/usuarios/{$id_usuario}/{$logo_n}";

        $uri = $this->CI->uri->segment_array();

        if ($uri['1'] == 'pharma'){
            $logosys = base_url('images/img/BRPharma_small.png');
        }else{
            $logosys = base_url('images/img/123456.png');
        }

        $default = [
            'logo' => base_url((!empty($logo_n)) ? $logo : "/images/usuarios/no-user.png"),
            'logoSistema' => $logosys
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/conv/navbar', $data, TRUE);
    }

    public function sidebar($data = [], $view = 'sidebar_painel')
    {

        if (isset($_SESSION['pharma']) && $_SESSION['pharma'] == 1){

            $data['cliente'] = $this->CI->db->where('id', $_SESSION['id_cliente'])->get('compradores')->row_array();
        }

        $default = [
        ];

        $data = array_merge($data, $default);

        return $this->CI->load->view("template/conv/{$view}", $data, TRUE);
    }

    public function scripts($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/conv/scripts', $data, TRUE);
    }

    public function heading($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/conv/heading', $data, TRUE);
    }


    public function footer($data = [])
    {
        $default = [];

        $data = array_merge($data, $default);

        return $this->CI->load->view('template/conv/footer', $data, TRUE);
    }


}