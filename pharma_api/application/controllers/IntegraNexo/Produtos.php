<?php

class Produtos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**TODO acrescentar o transaction para o tratamento correto em caso de erro */

    public function index()
    {
//        var_dump($_SESSION['id_fornecedor']);
//        exit();
        $this->db->where('id_fornecedor', $_SESSION['id_fornecedor']);
        $produtos = $this->db->get('produtos_catalogo')->result_array();

        $listaProduto = [];

        foreach ($produtos as $produto) {
            $listaProduto['produtos'][] =
                [
                    'codigo' => $produto['codigo'],
                    'codigo_externo' => $produto['codigo_externo'],
                    'nome_comercial' => $produto['nome_comercial'],
                    'ean' => $produto['ean'],
                    'quantidade_unidade' => $produto['quantidade_unidade']
                ];
        }

        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($listaProduto));
    }

    public function atualizarEstoque()
    {
        if ($this->input->method() == 'post') {

            $getDados = json_decode(file_get_contents("php://input"), true);
            if (isset($getDados)) {
                $this->db->trans_begin();

                $salvaNovoEsotque = [];

                foreach ($getDados['produtos'] as $produto) {
                    $salvaNovoEsotque[] =
                        [
                            'codigo' => intval(preg_replace('/[^0-9]/', '', $produto['codigo'])),
                            'estoque' => $produto['estoque'],
                            'lote' => $produto['lote'],
                            'id_fornecedor' => $_SESSION['id_fornecedor'],
                            'validade' => dbDateFormat($produto['validade'])
                        ];
                }

                $this->db->where('id_fornecedor', $_SESSION['id_fornecedor'])->delete('produtos_lote');

                $this->db->insert_batch('produtos_lote', $salvaNovoEsotque);

                if ($this->db->trans_status() === FALSE) {
                    $getError = $this->db->error();

                    $this->db->trans_rollback();

                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                        [
                            'status' => 'error',
                            'message' => $getError['message']
                        ]
                    ));
                } else {
                    $this->db->trans_commit();

                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Estoque atualizado com sucesso'
                    ]));
                }
            }
        }
    }

    public function cadastrarProduto()
    {
        if ($this->input->method() == 'post') {
            $json = file_get_contents("php://input");

            $getDados = json_decode($json, true);
            $salvaProduto = [];

            $f = fopen("{$_SESSION['id_fornecedor']}.json", 'w+');
            fwrite($f, $json);
            fclose($f);

            if (isset($getDados)) {

                foreach ($getDados['produtos'] as $produto) {

                    $codigo = intval(preg_replace('/[^0-9]/', '', $produto['codigo']));

                    $result = $this->db
                        ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                        ->where('codigo', $codigo)
                        ->get('produtos_catalogo');

                    if ($result->num_rows() < 1) {

                        $salvaProduto[] = [
                            'codigo' => $codigo,
                            'codigo_externo' => isset($produto['codigo_externo']) ? $produto['codigo_externo'] : $produto['codigo'],
                            'nome_comercial' => $produto['nome_comercial'],
                            'marca' => isset($produto['marca']) ? $produto['marca'] : '',
                            'quantidade_unidade' => $produto['quantidade_unidade'],
                            'unidade' => isset($produto['unidade']) ? $produto['unidade'] : '',
                            'ean' => isset($produto['ean']) ? $produto['ean'] : '',
                            'rms' => isset($produto['rms']) ? $produto['rms'] : '',
                            'id_fornecedor' => $_SESSION['id_fornecedor'],
                            "apresentacao" => $produto['apresentacao'],
                            "id_marca" => isset($produto['id_marca']) ? $produto['id_marca'] : '',
                            "bloqueado" => (intval($produto['ativo']) == 1) ? 0 : 1,
                            "ativo" => (isset($produto['ativo']) ? $produto['ativo'] : ''),
                        ];

                    } else {

                        $update = [
                            'nome_comercial' => $produto['nome_comercial'],
                            'marca' => $produto['marca'],
                            'quantidade_unidade' => $produto['quantidade_unidade'],
                            'unidade' => $produto['unidade'],
                            "bloqueado" => (intval($produto['ativo']) == 1) ? 0 : 1,
                            "ativo" => (isset($produto['ativo']) ? $produto['ativo'] : ''),
                        ];

                        $this->db
                            ->where('codigo', $codigo)
                            ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                            ->update("produtos_catalogo", $update);


                    }
                }
                if (!empty($salvaProduto)) {
                    $this->db->insert_batch('produtos_catalogo', $salvaProduto);

                }
                $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                    [
                        'status' => 'success',
                        'message' => "Produto(s) cadastrado(s) com sucesso"
                    ]
                ));

            } else {
                $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                    [
                        'status' => 'error',
                        'message' => "Nenhum produto foi informado"
                    ]
                ));
            }
        }
    }

    public function listarPrecos()
    {
        $this->db->select("ppm.codigo, pc.codigo_externo, ppm.id_estado, preco_unitario, uf");
        $this->db->from("produtos_preco_max ppm");
        $this->db->join('estados', 'ppm.id_estado = estados.id', 'left');
        $this->db->join('produtos_catalogo pc', 'ppm.codigo = pc.codigo and ppm.id_fornecedor = pc.id_fornecedor', 'left');
        $this->db->where('ppm.id_fornecedor', $_SESSION['id_fornecedor']);
        $result = $this->db->get()->result_array();

     /*   var_dump($this->db->last_query());
        exit();*/

        $listaPrecos = [];
        $resultOutput = [];

        //Aqui agrupa os dados
        foreach ($result as $produto) {
            $listaPrecos['precos'][$produto['codigo']][] =
                [
                    'id_estado' => $produto['id_estado'],
                    'codigo_externo' => $produto['codigo_externo'],
                    'preco_unitario' => $produto['preco_unitario'],
                    'uf' => $produto['uf']
                ];
        }
        //Aqui formata a saida
        if (!empty($listaPrecos['precos'])){
            foreach ($listaPrecos['precos'] as $key => $newList) {
                $resultOutput[] = [
                    "codigo" => $key,
                    "precos" => $newList
                ];
            }
        }

        $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($resultOutput));
    }

    public function atualizarPrecos()
    {
        if ($this->input->method() == 'post') {

            $getDados = json_decode(file_get_contents("php://input"), true);
            $estados = $this->db->get('estados')->result_array();
            $estadosArray = [];
            foreach ($estados as $estado) {
                $estadosArray[$estado['uf']] = $estado;
            }


            if (isset($getDados)) {
                $this->db->trans_begin();

                $salvaNovoPrecos = [];

                foreach ($getDados['produtos'] as $produto) {

                    if ($_SESSION['id_fornecedor'] == 5039) {
                        $prod = $this->db
                            ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                            ->where('codigo', intval(preg_replace('/[^0-9]/', '', $produto['codigo'])))
                            ->get('produtos_catalogo')
                            ->row_array();

                        if (!empty($prod['quantidade_unidade'])){
                            $produto['preco_unitario'] = ($produto['preco_unitario'] / $prod['quantidade_unidade']);
                        }

                    }

                    if ($produto['id_estado'] == 'BR') {
                        $salvaNovoPrecos[] =
                            [
                                'codigo' => intval(preg_replace('/[^0-9]/', '', $produto['codigo'])),
                                'preco_unitario' => $produto['preco_unitario'],
                                'id_estado' => null,
                                'id_fornecedor' => $_SESSION['id_fornecedor'],
                            ];
                    } else {
                        $uf = $estadosArray[$produto['id_estado']];

                        $salvaNovoPrecos[] =
                            [
                                'codigo' => intval(preg_replace('/[^0-9]/', '', $produto['codigo'])),
                                'preco_unitario' => $produto['preco_unitario'],
                                'id_estado' => $uf['id'],
                                'id_fornecedor' => $_SESSION['id_fornecedor'],
                            ];
                    }


                }

                $this->db
                    ->where('id_fornecedor', $_SESSION['id_fornecedor'])
                    ->delete('produtos_preco');

                $this->db->insert_batch('produtos_preco', $salvaNovoPrecos);

                if ($this->db->trans_status() === FALSE) {
                    $getError = $this->db->error();

                    $this->db->trans_rollback();

                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode(
                        [
                            'status' => 'error',
                            'message' => $getError['message']
                        ]
                    ));
                } else {
                    $this->db->trans_commit();

                    $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode([
                        'status' => 'success',
                        'message' => 'Preços atualizados com sucesso'
                    ]));
                }
            }
        }
    }

}