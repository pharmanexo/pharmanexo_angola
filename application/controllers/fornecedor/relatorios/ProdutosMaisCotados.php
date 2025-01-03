<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdutosMaisCotados extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $id_fornecedor = 112;
        $produtos = $this->getProdutosCotacoesComDepara($id_fornecedor);

        $insert = [];
        foreach ($produtos as $produto){

            $insert[] = [
                'id_sintese' => $produto['id_produto_sintese'],
                'id_fornecedor' => $id_fornecedor,
                'ds_produto' => $produto['ds_produto_comprador'],
                'total_solicitado' => $produto['total_solicitado'],
                'preco' => $produto['preco'],
                'qtd_unidade' => $produto['qtd_unidade'],
                'total' => $produto['total'],
                'codigo' => $produto['codigo']
            ];
        }

        $this->db->insert_batch('mais_cotados', $insert);

    }


    private function getProdutosCotacoesComDepara($id_fornecedor)
    {
        $dbSintese = $this->load->database('sintese', true);
        $compradores = [
            "33917568000120",
            "60975737007245",
            "02837922000183",
            "31671480000308",
            "10894988000133",
            "57571275001417",
            "57571275001840",
            "87827689002405",
            "16196263000158",
            "03284505000113",
            "16205262000122",
            "00625711000151",
            "39298922000162",
            "27569847000148",
            "13342878000238",
            "39384664000218",
            "10427478000156",
            "15153745002705",
            "78633088000176",
            "15153745000249",
            "13952064000134",
            "31684384000132",
            "32402414000133",
            "08428765000210",
            "10730521000158"];

        $produtos = $dbSintese
            ->select("*, sum(qt_produto_total) as total_solicitado")
            ->where('cotacoes_produtos.id_fornecedor', $id_fornecedor)
            ->where('cotacoes.id_fornecedor', $id_fornecedor)
            ->where('cotacoes_produtos.data_criacao > ', '2019-12-01')
            ->where_not_in('cotacoes.cd_comprador', $compradores)
            ->group_by('id_produto_sintese')
            ->join('cotacoes','cotacoes.cd_cotacao = cotacoes_produtos.cd_cotacao')
            ->get('cotacoes_produtos')
            ->result_array();
        $encontrados = [];




        foreach ($produtos as $prod) {
            $ids_sintese = $this->db->query("SELECT id_sintese FROM produtos_marca_sintese WHERE id_produto = {$prod['id_produto_sintese']} ")->result_array();
            $ids = [];

            $where = '';

            if (!empty($ids_sintese)) {

                foreach ($ids_sintese as $item) {
                    $ids[] = $item['id_sintese'];
                }
                $ids = implode(',', $ids);


                if (!empty($ids)) {

                    $where .= "id_sintese in ({$ids}) AND ";
                }

                $where .= "id_fornecedor = {$id_fornecedor} AND ";

                $where = rtrim($where, 'AND ');

                $enc = $this->db->select("*")
                    ->where($where)
                    ->group_by('cd_produto')
                    ->limit(1)
                    ->get('produtos_fornecedores_sintese')
                    ->result_array();

                foreach ($enc as $item) {


                    $prod['codigo'] = $item['cd_produto'];
                    $prod['preco'] = $this->db->query("SELECT preco_unitario FROM produtos_preco WHERE codigo = {$item['cd_produto']} and id_fornecedor = {$id_fornecedor} limit 1")->row_array()['preco_unitario'];
                    $prod['qtd_unidade'] = $this->db->query("SELECT quantidade_unidade FROM produtos_catalogo WHERE codigo = {$item['cd_produto']} and id_fornecedor = {$id_fornecedor} limit 1")->row_array()['quantidade_unidade'];
                    if (!is_null($prod['preco'])){
                        if (is_null($prod['qtd_unidade'])) $prod['qtd_unidade'] = 1;
                        $prod['total'] = $prod['total_solicitado'] * ($prod['preco'] / $prod['qtd_unidade']);
                        $encontrados[] = $prod;
                    }


                }

            }
        }

        return $encontrados;

    }
}
