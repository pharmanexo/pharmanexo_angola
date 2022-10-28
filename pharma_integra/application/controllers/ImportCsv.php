<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class ImportCsv extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Engine');

        $this->mix = $this->load->database('mix', true);
    }

    public function testeJson()
    {
        $json = file_get_contents('hospidrogas.json');
        $data = json_decode($json, true);

        $this->output->set_content_type('application/json')->set_output($json);
    }

    public function index()
    {
        $precos = $this->db->query('SELECT codigo,
       id_fornecedor,
       id_estado,
       preco_unitario,
       data_criacao

FROM pharmanexo.produtos_preco pp

WHERE pp.id_estado IS NOT NULL

    /*AND pp.codigo = 3516*/

  AND pp.data_criacao = (SELECT MAX(p.data_criacao)
                         FROM pharmanexo.produtos_preco p
                         WHERE p.codigo = pp.codigo
                           and p.id_fornecedor = pp.id_fornecedor
                           AND p.id_estado = pp.id_estado)

UNION

SELECT codigo,
       id_fornecedor,
       id_estado,
       preco_unitario,
       data_criacao

FROM pharmanexo.produtos_preco pp

WHERE pp.id_estado IS NULL

    /*AND pp.codigo = 3516*/

  AND pp.data_criacao = (SELECT MAX(p.data_criacao)
                         FROM pharmanexo.produtos_preco p
                         WHERE p.codigo = pp.codigo
                           and p.id_fornecedor = pp.id_fornecedor
                           AND p.id_estado IS NULL)

ORDER BY id_fornecedor, codigo, id_estado, preco_unitario')
            ->result_array();

        foreach ($precos as $preco) {

            $this->db->insert('produtos_preco_max', $preco);

        }

    /*    $file = 'public/Files/condicao_pagamento.csv';

        $csv = fopen($file, 'r');

        $condicao = [];

        while (($line = fgetcsv($csv, NULL, ',')) !== false) {

            $condicao[] =
                [
                    "id" => intval($line[0]),
                    "descricao" => $line[1],
                    "qtd_dias" => intval($line[2])
                ];
        }

        unset($condicao[0]);

        foreach ($condicao as $item) {

            $check = $this->db->where('id', $item['id'])
                ->limit(1)
                ->get('formas_pagamento')
                ->row_array();

            if(IS_NULL($check)) {

                $insert = $this->db->insert('formas_pagamento', $item);

                if($insert) {

                    $query = "INSERT INTO

                                    formas_pagamento_depara

                                     (cd_formaga_pagamento,
                                     id_formaga_pagamento,
                                     descricao,
                                     integrador,
                                     qtd_dias)
                                     VALUES
                                    (
                                     " . "'" . $item['id'] . "',
                                     " . "'" . $item['id'] . "',
                                     " . "'" . $item['descricao'] . "',
                                     " . "'" . 1 . "',
                                     " . "'" . $item['qtd_dias'] . "'
                                    )";

                    $this->db->query($query);

                }
            }
      } */
    }
}


