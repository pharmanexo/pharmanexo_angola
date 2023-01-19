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
        $file = fopen('sint_dez.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {
            var_dump($line);
            exit();
            $linhas[] = $line;
        }
        fclose($file);

        unset($linhas[0]);
        foreach ($linhas as $line) {
            var_dump($line);
            exit();

            $insert[] = [
                "codigo" => intval($line[0]),
                "produtos" => trim($line[1]),
                "unidade" => trim($line[2]),
                "marca" => trim($line[3]),
                "valor" => intval($line[4]),
                "qtd_embalagem" => trim($line[5]),
                "qtd_solicitada" => date("Y-m-d", strtotime($line[6])),
                "cotacao" => dbNumberFormat(trim($line[7])),
                "cotacao" => dbNumberFormat(trim($line[7])),
                "cotacao" => dbNumberFormat(trim($line[7])),
                "cotacao" => dbNumberFormat(trim($line[7])),
            ];
        }

        $this->db->insert_batch("promocoes_convidados", $insert);
    }

    public function importPontamed()
    {
        $file = fopen('compradores_pontamed.csv', 'r');
        $linhas = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {
            $linhas[] = $line;
        }
        fclose($file);

        unset($linhas[0]);
        foreach ($linhas as $line) {

            $cnpj = $line[0];
            $comprador = $this->db->select('id')->where('cnpj', $cnpj)->get('compradores')->row_array();

            $insert[] = [
                "id_fornecedor" => intval(5018),
                "id_cliente" => $comprador['id'],
                'consultor' => $line[5],
                'alerta_abertura' => 1
            ];

        }

        $this->db->insert_batch("email_notificacao", $insert);
    }

    public function importMapa()
    {
        $file = fopen('mapa_oncoprod_2023.csv', 'r');
        $insert = [];

        $lojas = [
            'DF' => 126,
            'ES' => 112,
            'PE' => 123,
            'RJ' => 127,
            'RS' => 12,
            'SP14' => 125,
            'SP15' => 115,
        ];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $estado = $this->db
                ->where('uf', $line[3])
                ->get('estados')
                ->row_array();


            $insert[] = [
                'icms' => $line[0],
                'classe' => $line[1],
                'origem' => $line[2],
                'id_estado' => $estado['id'],
                'id_fornecedor' => $lojas[$line[4]],
                'loja1' => (!empty($line[5])) ? $lojas[$line[5]] : NULL,
                'loja2' => (!empty($line[6])) ? $lojas[$line[6]] : NULL,
                'uf' => $line[4]
            ];
        }
        fclose($file);

        $this->db->insert_batch("mapa_logistico", $insert);
    }

}
