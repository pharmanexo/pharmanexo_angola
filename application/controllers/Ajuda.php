<?php
class Ajuda extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $page_title = "Ajuda";

        $data['header'] = $this->template->header(['title' => $page_title, 'styles' => []]);
        $data['navbar'] = $this->template->navbar();
        $data['sidebar'] = $this->template->sidebar();
        $data['heading'] = $this->template->heading(['page_title' => $page_title]);
        $data['scripts'] = $this->template->scripts([]);

       $this->load->view('page_help',$data);

    }
}