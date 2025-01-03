<?php

class Faturas extends Rep_controller
{

    private $route;
    private $urlselect2;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("pharma/faturas/");
        $this->views = "pharma/faturas/";


    }

    /**
     * Redireciona para a função form
     *
     * @return redirect
     */
    public function index()
    {

        $data['header'] = $this->tmp_rep->header(['title' => 'Faturas']);
        $data['navbar'] = $this->tmp_rep->navbar();
        $data['sidebar'] = $this->tmp_rep->sidebar();
        $data['heading'] = $this->tmp_rep->heading(['page_title' => 'Faturas']);
        $data['scripts'] = $this->tmp_rep->scripts([
            'scripts' => [
                'https://cdn.jsdelivr.net/npm/apexcharts'
            ]
        ]);

        $this->load->view("{$this->views}main", $data);

    }

}
