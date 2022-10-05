<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {

	public function index()
	{
	    parent::__construct();
	}

	public function error_404(){

	    $data = [
	        "header" => $this->template->header([
	            "title" => "Página não encontrada."
            ]),
            "img" => base_url("images/not_found.png")
        ];

	    $this->load->view('error_404', $data);
    }

    public function warning(){

        $data = [
            "header" => $this->template->header([
                "title" => "Algo deu errado."
            ]),
        ];

        $this->load->view('warning', $data);
    }


}

/* End of file Controllername.php */