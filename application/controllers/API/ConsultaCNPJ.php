<?php

class ConsultaCNPJ extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
    }


    public function get($cnpj){

        $content = file_get_contents("https://www.receitaws.com.br/v1/cnpj/{$cnpj}");

        $this->output->set_content_type('application/json')->set_output($content);

    }
}