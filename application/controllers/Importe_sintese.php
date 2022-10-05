<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Importe_sintese extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function importMarcas()
    {
        $file = fopen('tabela_marcas.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $get = $this->db->where('id', $line[0])->get('marcas')->row_array();

            if (!isset($get) || is_null($get) || empty($get)) {
                $insert[] = [
                    "id" => $line[0],
                    "marca" => $line[1],
                    "cnpj" => $line[2],
                ];
            }
        }
        fclose($file);

        var_dump($insert);
        exit();

        /*if (!$this->db->insert_batch("marcas", $insert)) {
            var_dump($this->db->error());
        }*/
    }

    public function importMarcas2()
    {
        $file = fopen('tabela_marcas.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $get = $this->db->where('id', $line[0])->get('marcas')->row_array();

            $marcaSintese = strtolower(str_replace(' ', '', $line[1]));
            $marcaBanco = strtolower(str_replace(' ', '', $get['marca']));

            if ($marcaSintese != $marcaBanco){
                echo "{$marcaSintese} > {$marcaBanco} <br>";
            }


            /*if (is_null($get)) {
                $insert[] = [
                    "id" => $line[0],
                    "marca" => $line[1],
                    "cnpj" => $line[2],
                ];
            }*/


        }
        fclose($file);


        /*if (!$this->db->insert_batch("marcas", $insert)) {
            var_dump($this->db->error());
        }*/
    }


    public function cond_pagamento()
    {
        $file = fopen('cond_pagto.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {

            $get = $this->db->where('cnpj', $line[0])->get('formas_pagamento')->row_array();

            if (is_null($get)) {
                $insert[] = [
                    "id" => $line[0],
                    "descricao" => $line[2],
                    "qtd_dias" => $line[3],
                    "ativo" => 1,

                ];
            }


        }
        fclose($file);


        if (!$this->db->insert_batch("formas_pagamento", $insert)) {
            var_dump($this->db->error());
        }
    }

    public function import_products()
    {
        $file = fopen('produtos_sintese.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $get = $this->db->where('id_produto', $line[0])->get('produtos_marca_sintese')->row_array();

            if (is_null($get)) {
                $insert[] = [
                    "id_produto" => $line[0],
                    "descricao" => $line[2],
                    "id_sintese" => $line[3],
                    "marca" => $line[4],
                    "id_marca" => $line[5],
                ];
            }


        }
        fclose($file);


        var_dump($insert);exit();

       /* if (!$this->db->insert_batch("produtos_marca_sintese", $insert)) {
            var_dump($this->db->error());
        }*/
    }

    public function import_oc(){
        $file = fopen('ocs_op_2019.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $insert = [
                'cliente' => $line[0],
                'cnpj' => $line[1],
                'uf' => $line[2],
                'oc' => $line[3],
                'fornecedor' => $line[5],
                'cnpj_fornecedor' => $line[6],
                'cor_erp' => $line[7],
                'produto' => $line[8],
                'unidade' => $line[9],
                'qtd' => intval($line[10]),
                'embalagem' => $line[11],
                'preco' => $line[12],
                'marca' => $line[13],
                ];

            $insert['data_oc'] = rtrim($line[4], substr($line[4],-4));

            $this->db->insert('ordens_compra_sintese', $insert);

        }
        fclose($file);

    }

}
