<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class oncoprod extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dimaster()
    {
        $file = fopen('materiais.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ',')) !== false) {

            $codigo = $line[0];
            $ean = $line[1];
            $rms = $line[2];
            $nome_comercial = $line[3];
            $marca = $line[4];
            $unidade = $line[5];
            $quantidade_unidade = $line['6'];


            $insert[] = [
                "codigo" => $codigo,
                "marca" => $marca,
                "nome_comercial" => $nome_comercial,
                "id_fornecedor" => 1002,
                "ativo" => 1,
                "bloqueado" => 0,
                "quantidade_unidade" => $quantidade_unidade,
                "unidade" => $unidade,
                "rms" => $rms,
                "ean" => $ean
            ];


        }
        fclose($file);

        if (!$this->db->insert_batch("produtos_catalogo", $insert)) {
            var_dump($this->db->error());
        }
    }

    public function teste()
    {
        $file = fopen('convetido.csv', 'r');
        $insert = [];
        $txt = fopen('sem_id_produto.csv', 'w');

        while (($line = fgetcsv($file, null, ',')) !== false) {
            $codigo = $line[0];
            $id_fornecedor = $line[1];
            $id_produto_sintese = $line[3];

            $ids_sintese = $this->db->select('id_sintese')->where("id_produto = {$id_produto_sintese}")->get('produtos_marca_sintese')->result_array();

            if (empty($ids_sintese)) {

                fwrite($txt, "{$codigo},115,0,{$id_produto_sintese} \n");

            }
        }
        fclose($file);
        fclose($txt);
    }

    public function importSintese()
    {
        $file = fopen('sintese.csv', 'r');
        $insert = [];

        while (($line = fgetcsv($file, null, ';')) !== false) {

            $grupo = trim($line[0], ' ');
            $produto = trim($line[1], ' ');
            $uf = trim($line[2], ' ');
            $marca = trim($line[3], ' ');
            $id_sintese = trim($line[4], ' ');
            $id_marca = trim($line[5], ' ');
            $id_produto = trim($line[6], ' ');

            $exist = $this->db->where("id_produto", $id_produto)->where("id_sintese", $id_sintese)->get('produtos_marca_sintese')->row_array();


            if (is_null($exist)) {
                /*$insert[] = [
                    "id_sintese" => $id_sintese,
                    "id_produto" => $id_produto,
                    "id_marca" => $id_marca,
                    "descricao" => $produto,
                    "grupo" => $grupo,
                    "marca" => $marca
                ];*/

                var_dump($exist);
                exit();
            }

        }
        fclose($file);


        var_dump($insert);
        exit();
    }

    public function index()
    {

        // $produtos = $this->db->get('produtos_oncoprod')->result_array();

        // foreach ($produtos as $produto) {
        //     $id_marca = $this->db->query("SELECT id, marca FROM marcas WHERE marca like '%{$produto['laboratorio']}'")->row_array();

        //     $data = [
        //         "id_fornecedor" => 120,
        //         "codigo" => $produto['codigo'],
        //         "nome_comercial" => $produto['principio_ativo'],
        //         "descricao" => $produto['descricao'],
        //         "apresentacao" => $produto['descricao'],
        //         "quantidade_unidade" => $produto['quantidade_embalagem'],
        //         "ncm" => $produto['ncm'],
        //         "ean" => $produto['ean'],
        //         "id_marca" => (isset($id_marca['id']) ? $id_marca['id'] : 0),
        //         "marca" => $id_marca['marca']
        //     ];

        //     $estados = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27];

        //     foreach ($estados as $estado) {
        //         $data['id_estado'] = $estado;
        //         $this->db->insert("produtos_fornecedores_validades", $data);

        //     }
        // }
    }

    public function marcas()
    {

        $marcas = $this->db->query("SELECT DISTINCT laboratorio from produtos_oncoprod")->result_array();

        foreach ($marcas as $k => $marca) {

            $id_marca = $this->db->query("SELECT * FROM marcas WHERE marca like '%{$marca['laboratorio']}'")->row_array();

            echo $marca['laboratorio'] . ' - ' . $id_marca['id'] . '<br>';

        }

    }

    public function importarOncoprod()
    {

        $file = fopen('tabela_oncoprod.csv', 'r');
        $insert = [];
        $i = 0;
        $line1 = 0;
        $line2 = 0;
        // var_dump(fgetcsv($file, null, ';')[0]); exit();

        while (($line = fgetcsv($file, null, ';')) !== false) {


            $insert[] = $line;

        }

        $empresas = $insert[0];

        $estados = $insert[1];

        $ins = [];
        unset($insert[0], $insert[1]);

        foreach ($insert as $key => $value) {

            $dt = [];

            foreach ($value as $indice => $valor) {

                $emp = $empresas[$indice];

                switch ($emp) {
                    case "ONCOPROD RS":
                        $emp = 12;
                        break;
                    case "ONCOPROD SP":
                        $emp = 115;
                        break;
                    case "ONCOPROD PE":
                        $emp = 123;
                        break;
                    case "NORPROD CE":
                        $emp = 789;
                        break;
                    case "HOSPLOG DF":
                        $emp = 120;
                        break;
                    case "ONCOPROD ES":
                        $emp = 112;
                        break;
                }

                $data = [
                    'campo' => $emp,
                    'codigo' => (isset($dt[0])) ? $dt[0]['preco'] : 0,
                    'preco' => $valor,
                    'estados' => explode('/', $estados[$indice])
                ];

                foreach ($data['estados'] as $k => $v) {

                    $id_estado = $this->db->where('uf', $v)->get('estados')->row_array();

                    $data['estados'][$k] = $id_estado['id'];

                }

                $dt[] = $data;
            }


            for ($i = 1; $i < count($dt); $i++) {

                $aux = $dt[$i];

                foreach ($aux['estados'] as $j => $vv) {

                    $ins[] = [
                        'codigo' => $aux['codigo'],
                        'id_estado' => $vv,
                        'preco_unitario' => dbNumberFormat(str_replace("RS", "", $aux['preco'])),
                        'id_fornecedor' => $aux['campo'],
                    ];

                }
            }

            // $this->db->insert_batch('produtos_preco', $ins);
            var_dump($ins);
            exit();
        }

        $data = [];
    }


    public function atualizaQtdUnidade()
    {

        $produtos = $this->db->query("SELECT codigo, quantidade_unidade FROM produtos_catalogo where id_fornecedor = 112")->result_array();

        foreach ($produtos as $produto) {
            $this->db->where('id_fornecedor', 123);
            $this->db->where('codigo', $produto['codigo']);

            $this->db->update('produtos_catalogo', ['quantidade_unidade' =>  $produto['quantidade_unidade']]);
        }

    }

}
