<?php

class CatalogoCsv extends CI_Controller
{

    private $turnOn = TRUE;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Engine');
    }

    public function index()
    {

        $file = 'public/Files/catalogo_dimaster.csv';

        $csv = fopen($file, 'r');

        $produtos = [];

        while (($line = fgetcsv($csv, NULL, ',')) !== false) {

            $id_fornecedor = 1002;
            $codigo = intval(str_replace('.', '', $line[0]));
            $ean = empty($line[1]) ? NULL : trim($line[1]);
            $rms = empty($line[2]) ? NULL : trim($line[2]);
            $nome_comercial = empty($line[3]) ? NULL : trim($line[3]);
            $apresentacao = empty($line[4]) ? NULL : trim($line[4]);
            $marca = empty($line[5]) ? NULL : trim($line[5]);
            $descricao = empty($line[6]) ? NULL : trim($line[6]);
            $unidade = empty($line[7]) ? NULL : trim($line[7]);
            $qtd_unidade = intval($line[8]);

            $produtos[] =
                [
                    "id_fornecedor" => $id_fornecedor,
                    "codigo" => $codigo,
                    "ean" => $ean,
                    "rms" => $rms,
                    "ativo" => 1,
                    "nome_comercial" => $nome_comercial . ' - ' . $descricao,
                    "apresentacao" => $apresentacao,
                    "descricao" => $descricao,
                    "marca" => $marca,
                    "unidade" => $unidade,
                    "quantidade_unidade" => $qtd_unidade
                ];
        }

        unset($produtos[0]);

        if ($this->turnOn) {

            foreach ($produtos as $prodCat) {

                if ($this->Engine->checkCatalog($prodCat)) {

                    $this->db->insert('produtos_catalogo', $prodCat);

                } else {

                    $this->Engine->activeCatalog($prodCat);
                }
            }
        }
    }

    public function importDimaster()
    {
        $file = 'public/Files/estoque_dimaster.csv';
        $data = [];

        try {
            $csv = fopen($file, 'r');
            while (($line = fgetcsv($csv, NULL, ',')) !== false) {


                $data[] = [
                    'codigo' => str_replace(".", "", $line[0]),
                    'lote' => (empty($line[1])) ? time() : $line[1],
                    'local' => '',
                    'validade' => dbDateFormat($line[3]),
                    'id_fornecedor' => '1002',
                    'estoque' => $line[4],
                ];

            }

            if (!empty($data)) {

                $v = $this->db->query("DELETE FROM produtos_lote WHERE id_fornecedor = 1002");

                if ($v) {
                    $this->db->insert_batch("produtos_lote", $data);
                }

            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }
}


