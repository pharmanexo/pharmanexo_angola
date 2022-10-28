<?php

class Biohosp extends MY_Controller_Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function index_post()
    {

        $post = file_get_contents("php://input");

        $re = '/(\w+)(:)/m';

        $subst = '\\"$1\\"$2';

        $result = preg_replace($re, $subst, $post);

        $json = str_replace("\\", "", $result);

      //  $produtos = json_decode($json, true);

        $folder = 'public/Files/Biohosp/';

        if (!is_dir($folder))
            mkdir($folder, 0777, true);

        $file = $folder . Date('Ymd') . '.json';

        $jsonProdutos = fopen($file, "w+");
        fwrite($jsonProdutos, $json);
        fclose($jsonProdutos);

        $return = [
            "response" => TRUE,
            "message" => "Rotina Atualizada!"
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($return));
    }
}
