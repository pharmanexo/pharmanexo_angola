<?php

class OncoexoExtra extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Engine');
    }

    public function index()
    {

        $file = 'public/Files/produtos_oncoexo_pe.csv';

        $csv = fopen($file, 'r');

        $produtos = [];
        $precos_pe = [];
        $precos_br = [];
        $lotes = [];

        while (($line = fgetcsv($csv, NULL, ',')) !== false) {

            $dia = mt_rand(1, 30);
            (strlen($dia) == 1) ? $dia = '0' . $dia : $dia = $dia;
            $mes = mt_rand(1, 12);
            (strlen($mes) == 1) ? $mes = '0' . $mes : $mes = $mes;
            $ano = mt_rand(2021, 2023);

            $validade = $ano . '-' . $mes . '-' . $dia;

            $produtos[] = [
                "id_fornecedor" => 25,
                "codigo" => intval($line[0]),
                "rms" => NULL,
                "nome_comercial" => $line[1],
                "apresentacao" => $line[2],
                "marca" => $line[3],
                "quantidade_unidade" => $line[6],
            ];

            $lotes[] = [
                "id_fornecedor" => 25,
                "codigo" => intval($line[0]),
                "lote" => "XFIXO",
                "local" => NULL,
                "estoque" => 10000,
                "fixo" => 1,
                "validade" => $validade
            ];

            $precos_pe[] = [
                "id_fornecedor" => 25,
                "codigo" => intval($line[0]),
                "id_estado" => 17,
                "preco_unitario" => number_format(floatval(trim($line[4])), 4, '.', ',')

            ];

            $precos_br[] = [
                "id_fornecedor" => 25,
                "codigo" => intval($line[0]),
                "id_estado" => NULL,
                "preco_unitario" => number_format(floatval(trim($line[5])), 4, '.', ',')
            ];
        }

        unset($produtos[0]);
        unset($precos_pe[0]);
        unset($precos_br[0]);
        unset($lotes[0]);

        if (FALSE) {
            foreach ($produtos as $prodCat) {

                if ($this->Engine->checkCatalog($prodCat)) {

                    $this->db->insert('produtos_catalogo', $prodCat);

                } else {
                    $this->Engine->activeCatalog($prodCat);
                }
            }
        }

        if (TRUE) {
            foreach ($lotes as $prodLot) {

                if ($this->Engine->checkLot($prodLot) && $prodLot['estoque'] > 0)
                    $this->db->insert('produtos_lote', $prodLot);
            }
        }

        if (FALSE) {
            foreach ($precos_pe as $preco_pe) {

                if ($this->Engine->checkPrice($preco_pe) && !(floatval($preco_pe['preco_unitario']) == 0)) {
                    $this->db->insert('produtos_preco', $preco_pe);
                }
            }
        }

        if (FALSE) {
            foreach ($precos_br as $preco_br) {

                if ($this->Engine->checkPrice($preco_br) && !(floatval($preco_br['preco_unitario']) == 0)) {
                    $this->db->insert('produtos_preco', $preco_br);
                }
            }
        }
    }
}


