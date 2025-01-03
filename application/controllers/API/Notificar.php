<?php

class Notificar extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = date("Y-m-d", time());
        $cots = [];

        $prods = $this->db
            ->select('id, produto, id_pfv, id_cliente, cd_cotacao, qtd_solicitada, preco_marca')
            ->where('id_pfv in (30001, 30002, 19001, 19002, 19003, 19004, 19005, 19006, 19007, 19008, 19009, 19010, 19011, 19012, 19013, 19014, 19015, 19016, 19017)')
            ->where('id_fornecedor', 5025)
            ->where("notificado", 0)
            ->get('cotacoes_produtos')
            ->result_array();

        foreach ($prods as $prod) {
            $cots[$prod['cd_cotacao']]['produtos'][] = $prod;
        }


        foreach ($cots as $k => $cot) {
            $cotacao = $this->db->where('cd_cotacao', $k)->where('id_fornecedor', 5025)->get('cotacoes_sintese.cotacoes')->row_array();
            $cliente = $this->db->where('id', $cotacao['id_cliente'])->get('compradores')->row_array();

            $text = '';
            $text .= "Cotação: {$k} <br> Cliente: {$cliente['nome_fantasia']} - {$cliente['estado']} <br> CNPJ: {$cliente['cnpj']} <br><br>";
            $text .= "Contato: <br> Telefone: {$cliente['telefone']} <br> Celular: {$cliente['celular']} <br> E-mail: {$cliente['email']}";

            $text .= "<br><br> <h3>Produtos</h3>";

            foreach ($cot['produtos'] as $produto) {

                $preco = number_format($produto['preco_marca'], 4, ',', '.');
                $text .= "Produto: {$produto['id_pfv']} - {$produto['produto']} <br> Qtde.: {$produto['qtd_solicitada']} | Preço: {$preco} <br> <hr>";

                $this->db->where('id', $produto['id']);
                $this->db->update('cotacoes_produtos', ['notificado' => 1]);
            }

            $notificar = [
                "to" => "administracao@pharmanexo.com.br",
                "greeting" => "",
                "subject" => "{BELCHER} - COTAÇÃO #{$k} {$cliente['nome_fantasia']} - {$cliente['estado']}",
                "message" => $text,
                "oncoprod" => 0
            ];

         //   $this->notify->send($notificar);

        }


    }

    public function statusFarma()
    {
        $data = date("Y-m-d", time());
        $cots = [];

        $prods = $this->db
            ->select('id, produto, id_pfv, id_cliente, cd_cotacao, qtd_solicitada, preco_marca')
            ->where('id_pfv in (99001,99002,99003,99004,99005,99006)')
            ->where('id_fornecedor', 5031)
            ->where("notificado", 0)
            ->get('cotacoes_produtos')
            ->result_array();

        foreach ($prods as $prod) {
            $cots[$prod['cd_cotacao']]['produtos'][] = $prod;
        }


        foreach ($cots as $k => $cot) {
            $cotacao = $this->db->where('cd_cotacao', $k)->where('id_fornecedor', 5031)->get('cotacoes_sintese.cotacoes')->row_array();
            $cliente = $this->db->where('id', $cotacao['id_cliente'])->get('compradores')->row_array();

            $text = '';
            $text .= "Cotação: {$k} <br> Cliente: {$cliente['nome_fantasia']} - {$cliente['estado']} <br> CNPJ: {$cliente['cnpj']} <br><br>";
            $text .= "Contato: <br> Telefone: {$cliente['telefone']} <br> Celular: {$cliente['celular']} <br> E-mail: {$cliente['email']}";

            $text .= "<br><br> <h3>Produtos</h3>";

            foreach ($cot['produtos'] as $produto) {

                $preco = number_format($produto['preco_marca'], 4, ',', '.');
                $text .= "Produto: {$produto['id_pfv']} - {$produto['produto']} <br> Qtde.: {$produto['qtd_solicitada']} | Preço: {$preco} <br> <hr>";

                $this->db->where('id', $produto['id']);
                $this->db->update('cotacoes_produtos', ['notificado' => 1]);
            }

            $notificar = [
                "to" => "administracao@pharmanexo.com.br, marlon.boecker@pharmanexo.com.br",
                "greeting" => "",
                "subject" => "{STATUS FARMA} - COTAÇÃO #{$k} {$cliente['nome_fantasia']} - {$cliente['estado']}",
                "message" => $text,
                "oncoprod" => 0
            ];

            $this->notify->send($notificar);

        }


    }

}
