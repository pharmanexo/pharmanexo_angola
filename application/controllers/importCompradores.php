<?php

class importCompradores extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $file = fopen('sta_casa_maceio.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {

            $insert[] = [
                'codigo' => $line[0]
            ];

        }
        fclose($file);


        var_dump($insert);
        exit();
    }


}