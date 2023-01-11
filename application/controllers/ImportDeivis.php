<?php

class ImportDeivis extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
        $this->mix = $this->load->database('mix', true);
    }

    public function importRest()
    {

        echo password_hash('Londricir122@', 1);
        exit();

        $file = fopen('itens_hosp.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {

            $linhas[] = $line;

            /*  $codigo = utf8_decode($line[0]);
              if ($codigo == 'CODPROD'){
                  var_dump($line);
                  exit();
              }

             exit();

           */
        }
        fclose($file);

        unset($linhas[0]);
        foreach ($linhas as $line) {
            $insert[] = [
               "codigo" => intval($line[0]),
               "descricao" => trim($line[1]),
               "unidade" => trim($line[2]),
               "marca" => trim($line[3]),
               "quantidade" => intval($line[4]),
               "lote" => trim($line[5]),
               "validade" => date("Y-m-d", strtotime($line[6])),
               "preco" => dbNumberFormat(trim($line[7])),
            ];
        }

        $this->db->insert_batch("promocoes_convidados", $insert);
    }

}
