<?php

class ImportDeivis extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
        $this->mix = $this->load->database('mix', true);
    }

    public function deparaReverseSintese()
    {
        $query = "
        select cp.cd_produto_comprador, cp.id_produto_sintese, cp.id_fornecedor, c.id_cliente
from cotacoes_sintese.cotacoes_produtos cp
         join cotacoes_sintese.cotacoes c on c.cd_cotacao = cp.cd_cotacao and c.id_fornecedor = cp.id_fornecedor
group by cp.id_produto_sintese, cp.cd_produto_comprador";

        $data = $this->db->query($query)->result_array();

        foreach ($data as $item){

            var_dump($item);
            exit();


        }


    }

}
