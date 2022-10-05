<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Cotacoes_previsao extends CI_Controller
{

    private $sint;
    private $id_distribuidor;

    public function __construct()
    {
        parent::__construct();

        $this->sint = $this->load->database('sintese', true);
        $this->id_distribuidor = 20;
    }

    public function index()
    {
        $cots = $this->sint
            ->where('id_fornecedor', 20)
            ->where("date(data_criacao) between '2022-04-01' and '2022-04-31' ")->get('cotacoes')->result_array();

        $produtos = [];

        foreach ($cots as $k => $cot) {
            $cotProdutos = $this->sint
                ->where('id_fornecedor', 20)
                ->where('cd_cotacao', $cot['cd_cotacao'])->get('cotacoes_produtos')->result_array();

            foreach ($cotProdutos as $j => $prod) {
                $prod['uf'] = $cot['uf_cotacao'];

                $ids_sintese = [];
                $id_produto = $prod['id_produto_sintese'];
                if (!empty($id_produto)) {
                    $getIdSintese = $this->db->select('id_sintese')->where('id_produto', $id_produto)->get('produtos_marca_sintese')->result_array();
                }

                foreach ($getIdSintese as $item) {
                    $ids_sintese[] = $item['id_sintese'];
                }

                if (!empty($ids_sintese)) {
                    //verifica se tem depara para o produto no distribuidor
                    $depara = $this->db
                        ->where('id_fornecedor', $this->id_distribuidor)
                        ->where_in('id_sintese', $ids_sintese)
                        ->get('produtos_fornecedores_sintese')
                        ->row_array();

                    $prod['codigo'] = $depara['cd_produto'];


                } else {
                    $depara = [];
                }

                if (!empty($depara)) {
                    $this->db->insert('rel_prod_sem_depara', $prod);
                }

            }

        }

    }


}