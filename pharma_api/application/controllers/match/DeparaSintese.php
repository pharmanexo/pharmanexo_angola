<?php

class DeparaSintese extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function deparaReverseSintese()
    {
        $query = "
                        select cp.cd_produto_comprador, cp.id_produto_sintese, cp.id_fornecedor, c.id_cliente
                from cotacoes_sintese.cotacoes_produtos cp
                         join cotacoes_sintese.cotacoes c on c.cd_cotacao = cp.cd_cotacao and c.id_fornecedor = cp.id_fornecedor
                group by cp.id_produto_sintese, cp.cd_produto_comprador, c.id_cliente";

        $data = $this->db->query($query)->result_array();

        $db = $this->load->database('sintese', true);

        $db->insert_batch("auxiliar_depara", $data);

    }


    public function match()
    {
        $db = $this->load->database('sintese', true);
        $insert = [];
        $produtos = $db->get('auxiliar_depara')->result_array();

        foreach ($produtos as $produto) {
            $insert[] = [
                'id_produto_sintese' => $produto['id_produto_sintese'],
                'cd_produto' => $produto['cd_produto_comprador'],
                'id_integrador' => 1,
                'id_cliente' => $produto['id_cliente']
            ];
        }

        $this->db->insert_batch('produtos_clientes_depara', $insert);


    }
}
