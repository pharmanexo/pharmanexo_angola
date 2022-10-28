<?php

class MY_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        switch (MY_ENV) {

            case 'development':
                $this->db = $this->load->database('pharmahmg', true);
                break;

            case 'production':
                $this->db = $this->load->database('default', true);
                break;

            default:
                $this->db = $this->load->database('pharmahmg', true);
                break;
        }
    }
}