<?php
class AtualizarPrecos extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){

        $precos_oncoexo = $this->db->select("*")->where('column_2', 'BR')->get('preco_oncoexo')->result_array();

        foreach ($precos_oncoexo as $item) {

            $data['preco_unidade'] = $item['column_3'];
            $data['quantidade_unidade'] = $item['column_5'];

            $this->db->where("id_fornecedor", '15');
            $this->db->where("id_estado <>", '15');
            $this->db->where("id_estado <>", '17');
            $this->db->where("codigo", $item['column_1']);
            $this->db->update("produtos_fornecedores_validades", $data);


        }

    }

    public function ativosExomed(){
        $ativos = $this->db->select("*")->from("exomedativo")->get()->result_array();

        foreach ($ativos as $ativo){
            $this->db->query("UPDATE produtos_fornecedores_validades set ativo = 1 where codigo = {$ativo['codigo']} and id_fornecedor = 180");
        }

    }

    public function deleteExomed(){
        $inativos = $this->db->select("*")->from("produtos_fornecedores_validades")->where("id_fornecedor = 180 and id_estado = 17 and id_sintese <> 0")->get()->result_array();

        var_dump($inativos);
        exit();

        foreach ($inativos as $inativo){

            $this->db->query("DELETE FROM produtos_fornecedores_sintese where id_pfv = {$inativo['id']}");
        }

    }
}