<?php
/**
 * Criado por:  Marlon Boecker
 * Criado em: 29/05/2019 21:42
 */
class Informativos extends MY_Controller
{
    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();
        $this->views = "marketplace/informativos/";
        $this->route = "marketplace/informativos/";

        $this->load->model("m_faq");
    }

    public function quem_somos(){

        $page_title = "Sobre Nós";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => $page_title,
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}sobre", $data);
    }

    public function fale_conosco(){

        $page_title = "Sobre Nós";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => $page_title,
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}fale_conosco", $data);
    }

    public function seguranca(){

        $page_title = "Sobre Nós";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => $page_title,
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}seguranca", $data);
    }

    public function termos_uso(){

        $page_title = "Sobre Nós";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => $page_title,
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}termos_uso", $data);
    }
}