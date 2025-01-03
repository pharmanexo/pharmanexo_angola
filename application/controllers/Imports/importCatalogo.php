<?php
class importCatalogo extends CI_Controller{

    public function __construct()
    {
        parent::__construct();


    }

    public function index(){
        $file = fopen('catalogo_agil.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {

                $insert[] = [
                    "codigo" => $line[0],
                    "apresentacao" => $line[1],
                    "nome_comercial" => $line[2],
                    "marca" => $line[3],
                    "unidade" => $line[4],
                    "quantidade_unidade" => $line[5],
                    "ean" => $line[7],
                    "rms" => $line[8],
                    "id_fornecedor" => 5013,
                    "ativo" => 1,
                ];
        }
        fclose($file);


        if (!$this->db->insert_batch("produtos_catalogo", $insert)) {
            var_dump($this->db->error());
        }

    }




}