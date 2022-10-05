<?php

class Dimaster extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();


    }

    public function index()
    {
        echo sha1('135494');

    }

    public function Catalogo()
    {
        $file = fopen('catalogo_dimaster.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $line[0] = str_replace('.', '', $line[0]);

            $exist = $this->db
                ->where('codigo', $line[0])
                ->where('id_fornecedor', '1002')
                ->get('produtos_catalogo')
                ->row_array();


            if (empty($exist)) {
                $insert[] = [
                    "codigo" => $line[0],
                    "ean" => $line[1],
                    "rms" => $line[2],
                    "nome_comercial" => $line[6] . ' - ' .$line[3],
                    "apresentacao" => $line[4],
                    "marca" => $line[5],
                    "descricao" => $line[6],
                    "unidade" => $line[7],
                    "quantidade_unidade" => $line[8],
                    "id_fornecedor" => "1002"
                ];
            }


        }
        fclose($file);


        var_dump($insert);
        exit();


        if (!$this->db->insert_batch("produtos_catalogo", $insert)) {
            var_dump($this->db->error());
        }

    }


    public function Estoque()
    {
        $file = fopen('estoque_dimaster.csv', 'r');
        $insert = [];

        // LIMPA O ESTOQUE ATUAL
        $this->db->query("DELETE FROM produtos_lote WHERE id_fornecedor = '1002'");

        while (($line = fgetcsv($file, null, ',')) !== false) {
            $line[2] = str_replace('.', '/', $line[2]);
            $line[3] = str_replace('.', '/', $line[3]);

            $insert[] = [
                "codigo" => str_replace('.', '', $line[0]),
                "lote" => $line[1],
                "validade" => dbDateFormat($this->mask($line[3], '##/##/#####')),
                "estoque" => str_replace('.', '.', intval($line[4])),
                "id_fornecedor" => 1002,
            ];

        }

        fclose($file);

        if (!$this->db->insert_batch("produtos_lote", $insert)) {
            var_dump($this->db->error());
        }
    }

    function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; ++$i) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }

}
