<?php

class UpdateQtdUnidade extends MY_Controller
{
    private $fornecedor = [12, 111, 112, 115, 120, 123];

    public function __construct()
    {
        parent::__construct();
    }

    public function index_get()
    {

        $qtdUnidades = [];

        $csv = fopen('public/Files/Oncoprod/oncoprod_qtd_unid.csv', 'r');

        while (($line = fgetcsv($csv, NULL, ';')) !== false) {

            $qtdUnidades[] = [
                "codigo" => intval($line[0]),
                "qtd" => intval($line[1])
            ];
        }

        unset($qtdUnidades[0]);

        foreach ($qtdUnidades as $qtd) {

            $this->db->where_in('id_fornecedor', $this->fornecedor)
                ->where('codigo', $qtd['codigo'])
                ->set('quantidade_unidade', $qtd['qtd'])
                ->update('produtos_catalogo');

        }
    }
}