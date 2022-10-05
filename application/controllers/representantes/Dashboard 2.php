<?php


class Dashboard extends Rep_controller
{

    private $route;
    private $views;

    public function __construct()
    {
        parent::__construct();

        $this->route = base_url("representantes/dashboard/");
        $this->views = "representantes/";

    }

    public function index()
    {

        $data['header'] = $this->tmp_rep->header([
            'title' => 'Portal do Representante'
        ]);
        $data['navbar']  = $this->tmp_rep->navbar();
        $data['sidebar'] = $this->tmp_rep->sidebar();
        $data['heading'] = $this->tmp_rep->heading();
        $data['scripts'] = $this->tmp_rep->scripts();


        $this->load->view("{$this->views}dashboard", $data);

    }
}