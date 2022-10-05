<?php
/**
 * Criado por:  Marlon Boecker
 * Criado em: 29/05/2019 21:42
 */
class FAQ extends MY_Controller
{
    private $views;
    private $route;

    public function __construct()
    {
        parent::__construct();
        $this->views = "marketplace/";
        $this->route = "marketplace/faq/";

        $this->load->model("m_faq");
    }

    public function index(){

        $data['questions'] = $this->m_faq->get_rows();

        $page_title = "FAQ";

        // TEMPLATE
        $data['header'] = $this->tmp->header([
            'title' => 'FAQ',
            'styles' => [
            ]
        ]);
        $data['navbar'] = $this->tmp->navbar();
        $data['scripts'] = $this->tmp->scripts([
            'scripts' => [
            ]
        ]);

        $this->load->view("{$this->views}v_faq", $data);

    }
}