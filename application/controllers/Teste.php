<?php
class Teste extends CI_Controller{


    public function __construct()
    {
        parent::__construct();

        $this->load->library('Customers');
    }

    public function index(){

        $this->customers->create('33.391.778/0001-28');

    }


}