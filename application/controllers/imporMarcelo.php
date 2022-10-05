<?php
class imporMarcelo extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){

        $meuArray = Array();
        $file = fopen('marcelo.csv', 'r');
        while (($line = fgetcsv($file, null, ',')) !== false)
        {
           $meuArray[] = $line;
        }
        fclose($file);


        $f = fopen('teste.json', 'w+');
        fwrite($f, json_encode($meuArray));
        fclose($f);


        exit();

    }


}