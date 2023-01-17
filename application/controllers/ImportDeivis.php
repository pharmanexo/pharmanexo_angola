<?php

class ImportDeivis extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
        $this->mix = $this->load->database('mix', true);
    }

    public function produtosGlobal()
    {
        $file = fopen('produtos_global.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $prod = $this->db
                ->where('codigo', $line[0])
                ->where('id_fornecedor', 5038)
                ->get('produtos_catalogo');

            if ($prod->num_rows() == 0){
                $linhas[] = [
                    'codigo' => $line[0],
                    'apresentacao' => $line[2],
                    'nome_comercial' => $line[1],
                    'quantidade_unidade' => soNumero($line[3]),
                    'unidade' => str_replace("1 ", "", $line[4]),
                    'marca' => $line[5],
                    'ean' => $line[6],
                    'rms' => $line[7],
                    'ativo' => 1,
                    'id_fornecedor' => 5038,
                    'ocultar_de_para' => 0
                ];
            }

        }

        $this->db->insert_batch('produtos_catalogo', $linhas);

        fclose($file);
    }


    public function importRest()
    {
        exit();
        $file = fopen('itens_hosp.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $linhas[] = $line;
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

    public function importProds()
    {
        $file = fopen('med_dez_sintese.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {
            $linhas[] = $line;
        }
        fclose($file);

        unset($linhas[0]);
        foreach ($linhas as $line) {
            var_dump($line);
            exit();

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
