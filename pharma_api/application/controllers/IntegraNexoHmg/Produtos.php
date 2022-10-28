<?php

class Produtos extends MY_Controller_hmg
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
                            'codigo' => $produto['codigo'],
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

            $getDados = json_decode(file_get_contents("php://input"), true);
            $salvaProduto = [];

            if($_SESSION['id_fornecedor'] == 5039){
                $f = fopen('londricir_precos.json', 'w+');
                fwrite($f, file_get_contents("php://input"));
                fclose($f);
            }



            if (isset($getDados)) {


                foreach ($getDados['produtos'] as $produto) {
                    $result = $this->db->where('codigo', $produto['codigo'])->get('produtos_catalogo');


                    if ($result->num_rows() < 1) {

                        $salvaProduto[] = [
                            'codigo' => $produto['codigo'],
                            'codigo_externo' => isset($produto['codigo_externo']) ? $produto['codigo_externo'] : '',
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
                            ->where('codigo', $produto['codigo'])
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
        $this->db->select("codigo, id_estado, preco_unitario, uf");
        $this->db->from("produtos_preco_max");
        $this->db->join('estados', 'produtos_preco_max.id_estado = estados.id', 'left');
        $this->db->where('produtos_preco_max.id_fornecedor', $_SESSION['id_fornecedor']);
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

            if($_SESSION['id_fornecedor'] == 5039){
                $f = fopen('londricir_precos.json', 'w+');
                fwrite($f, file_get_contents("php://input"));
                fclose($f);
            }


            if (isset($getDados)) {
                $this->db->trans_begin();

                $salvaNovoPrecos = [];

                foreach ($getDados['produtos'] as $produto) {


                    if ($produto['id_estado'] == 'BR') {
                        $salvaNovoPrecos[] =
                            [
                                'codigo' => $produto['codigo'],
                                'preco_unitario' => $produto['preco_unitario'],
                                'id_estado' => null,
                                'id_fornecedor' => $_SESSION['id_fornecedor'],
                            ];
                    } else {
                        $uf = $estadosArray[$produto['id_estado']];

                        $salvaNovoPrecos[] =
                            [
                                'codigo' => $produto['codigo'],
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