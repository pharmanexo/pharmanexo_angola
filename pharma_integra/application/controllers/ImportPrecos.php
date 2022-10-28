<?php

class ImportPrecos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $file = 'precos_mill.csv';
        $id_fornecedor = 5033;

        $csv = fopen($file, 'r');

        $qtd_unid = [];
        $precos = [];

        while (($line = fgetcsv($csv, NULL, ',')) !== false) {


            $qtd_unid[] = [
                'id_fornecedor' => $id_fornecedor,
                'codigo' => $line[0],
                'quantidade_unidade' => $line[7]
            ];

           $p1 = dbNumberFormat(trim($line[3]));
           $p2 = dbNumberFormat(trim($line[4]));

            if ($p1 > 0) {
                $precos[] = [
                    'id_fornecedor' => $id_fornecedor,
                    'id_estado' => 8,
                    'codigo' => $line[0],
                    'preco_unitario' => $p1
                ];
            }

            if ($p2 > 0) {
                $precos[] = [
                    'id_fornecedor' => $id_fornecedor,
                    'id_estado' => 19,
                    'codigo' => $line[0],
                    'preco_unitario' => $p2
                ];
            }
        }

        $this->db->insert_batch('produtos_preco', $precos);

        foreach ($qtd_unid as $q) {

            $data = [
                'quantidade_unidade' => $q['quantidade_unidade']
            ];

            $this->db->where('codigo', $q['codigo']);
            $this->db->where('id_fornecedor', $q['id_fornecedor']);

            $this->db->update('produtos_catalogo', $data);

        }

    }

    public function depara()
    {
        $file = 'depara.csv';
        $id_fornecedor = 5032;

        $csv = fopen($file, 'r');
        $insert = [];
        while (($line = fgetcsv($csv, NULL, ',')) !== false) {
           $insert[] = [
               'id_sintese' => $line[0],
               'id_pfv' => $line[1],
               'id_usuario' => $line[2],
               'cd_produto' => $line[3],
               'id_catalogo' => $line[5],
               'id_fornecedor' => $id_fornecedor
           ];
        }

        $this->db->insert_batch('produtos_fornecedores_sintese', $insert);
    }

}