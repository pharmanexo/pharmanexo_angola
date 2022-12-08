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
        $file = fopen('restricoes_hosp.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $estados = $this->db->get('estados')->result_array();

            foreach ($estados as $estado) {
                $insert[] = [
                    "id_estado" => $estado['id'],
                    "id_fornecedor" => 5046,
                    "id_tipo_venda" => 2,
                    "id_produto" => $line[0],
                    "integrador" => 1
                ];
            }
        }
        fclose($file);


        if (!$this->db->insert_batch("restricoes_produtos_clientes", $insert)) {
            var_dump($this->db->error());
        }
    }

}
