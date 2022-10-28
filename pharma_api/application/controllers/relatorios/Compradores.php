<?php

class Compradores extends CI_Controller
{
    private $dbSintese;
    private $dbBionexo;
    private $dbApoio;

    public function __construct()
    {
        parent::__construct();


    }

    public function condicoesPagamento($integrador = 'sintese')
    {

        $compradores = $this->db->query("select distinct cc.id_cliente, c.cnpj, c.nome_fantasia, c.cidade, c.estado from cotacoes_sintese.cotacoes cc
                                
                                join pharmanexo.compradores c on c.id = cc.id_cliente
                                where year(cc.data_criacao) = '2021' or year(cc.data_criacao) = '2022'")->result_array();


        foreach ($compradores as $B => $comprador) {

            $sint = $this->load->database('sintese', true);


            $cots = $sint
                ->distinct()
                ->select('count(0) as total, cd_condicao_pagamento')
                ->where('id_cliente', intval($comprador['id_cliente']))
                ->where("year(data_criacao) = '2022'")
                ->group_by('cd_cotacao, cd_condicao_pagamento')
                ->get('cotacoes')->result_array();


            if (empty($cots)) {
                $compradores[$B]['condicoes'] = 'Não informado';
               //continue;
            } else {
                $c = 0;
                $cs = [];

                foreach ($cots as $cot) {
                    if ($c = 0) {
                        $c = $cot;
                    } else {
                        if ($c['total'] < $cot['total']) {
                            $c = $cot;
                        }
                    }
                }

                if (count($c) > 0) {
                    $cs[] = $c['cd_condicao_pagamento'];

                    foreach ($cots as $cot) {
                        if ($c['total'] == $cot['total'] && $c['cd_condicao_pagamento'] != $cot['cd_condicao_pagamento']) {
                            $cs[] = $cot['cd_condicao_pagamento'];
                        }
                    }
                }



                if (!empty($cs))
                {
                    foreach ($cs as $k => $f)
                    {
                        $fp = $this->getDescricaoPagamento($f);
                        $cs[$k] = ($fp == false) ? 'Não localizado' : $fp;
                    }
                }


                $compradores[$B]['condicoes'] = implode('|', $cs);
            }

        }

        $this->export($compradores);
    }

    private function export($data)
    {
        $txt = '';
        foreach ($data as $item)
        {
            $txt .= implode(',', $item) . "\n";
        }

        $f = fopen('rel.csv', 'w+');
        fwrite($f, $txt);
        fclose($f);
    }

    private function getDescricaoPagamento($id)
    {
       $fp =  $this->db->where('id', $id)->get('formas_pagamento')->row_array();

       if (!empty($fp)){
           return $fp['descricao'];
       }else{
           return false;
       }
    }
}