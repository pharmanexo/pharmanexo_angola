<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//header('Content-Type: application/json;charset=ISO-8859-1');

class RequestBionexo extends CI_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 25/09/2020
     */


    public function __construct()
    {
        parent::__construct();

        $this->bio = $this->load->database('bionexo', true);

    }

    public function index()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        #$cnpjs = [];

        $getCotacao = $this->bio
            ->where('catalogo', 0)
           // ->where('id_cliente', 14050)
            ->get('cotacoes')
            ->result_array();


        if (count($getCotacao) > 0) {
            foreach ($getCotacao as $keyCot => $item) {


                $id_cotacao = intval($item['id']);

                $getProdsCotacao = $this->bio
                    ->where('id_cotacao', $id_cotacao)
                    // ->where('cd_produto_comprador', 'F097801')
                    ->get('cotacoes_produtos')
                    ->result_array();


                if (empty($getProdsCotacao))
                    continue;

                $getProdsCotacao = arrayFormat($getProdsCotacao);

                foreach ($getProdsCotacao as $prods) {

                    $codigo = $prods['cd_produto_comprador'];


                    $arrProds =
                        [
                            'id_cliente' => $item['id_cliente'],
                            'codigo' => $codigo,
                            'descricao' => $prods['ds_produto_comprador'],
                            'id_unidade' => $prods['id_unidade'],
                            'unidade' => $prods['ds_unidade_compra'],
                            'id_categoria' => $prods['id_categoria'],
                        ];


                    $checkProds = $this->bio
                        ->where('id_cliente', $arrProds['id_cliente'])
                        ->where('codigo', $arrProds['codigo'])
                        ->get('catalogo')
                        ->row_array();


                    if (IS_NULL($checkProds)) {
                        $this->bio->insert('catalogo', $arrProds);
                    }

                    $updateCot = $this->bio->where('id', $item['id'])
                        ->set('catalogo', 1)
                        ->update('cotacoes');

                }
            }
        }
    }

} // class

