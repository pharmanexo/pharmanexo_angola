<?php

class Pegacsv extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $handle = fopen('Estoque.CSV', "r");
        $header = fgetcsv($handle, '', ";");
        $teste = [];
        $id_fornecedor = 0;
        while($row = fgetcsv($handle, '', ";")){

            $teste[] = [
                'codigo' => $row[0],
                'lote' => $row[1],
                'validade' => $row[2],
                'estoque' => $row[3],
                'id_fornecedor' => $id_fornecedor
            ];

            $id_fornecedor ++;
        }
//        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
//            [
//                'status'=> 'success',
//                'data'=> $teste
//            ]
//        ));
        fclose($handle);

        $insert = $this->db->insert_batch('teste_lote', $teste);
        var_dump($this->db->error());
//        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($insert));
    }
}