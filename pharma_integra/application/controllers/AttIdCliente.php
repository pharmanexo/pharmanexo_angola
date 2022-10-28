<?php

class AttIdCliente extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
    }

    protected function index_get()
    {

        $compradores = $this->db->select('cnpj_comprador')
            ->group_by('cnpj_comprador')
            ->get('cotacoes_produtos')
            ->result_array();

        var_dump($compradores); exit();

        foreach ($cotacoes as $cotacao) {

            $cd_cotacao = $cotacao['cd_cotacao'];

            $dt_ini_cotacao = $this->sint->select('dt_inicio_cotacao')
                ->where('cd_cotacao', $cd_cotacao)
                ->limit(1)
                ->get('cotacoes')
                ->row_array();

            $this->db->where('cd_cotacao', $cd_cotacao);
            $this->db->set('data_cotacao', $dt_ini_cotacao['dt_inicio_cotacao']);
            $this->db->update('cotacoes_produtos');

        }

       /* $file = 'public/SUPORTE_MEDICAMENTOS-Pharmanexo.csv';

        $csv = fopen($file, 'r');

        $produtos = [];

        while (($line = fgetcsv($csv, NULL, ';')) !== false) {

            $produtos[] = [
                "codigo" => intval($line[0]),
                "rms" => NULL,
                "ean" => NULL,
                "descricao" => NULL,
                "apresentacao" => $line[2],
                "marca" => $line[3],
                "unidade" => $line[4],
                "quantidade_unidade" => (intval($line[5]) == NULL) ? 1: intval($line[5]),
                "nome_comercial" => $line[1],
                "id_fornecedor" => 5012,
                "ativo" => 1,
                "bloqueado" => 0
            ];
        }

        unset($produtos[0]);

        $this->db->insert_batch('produtos_catalogo', $produtos);*/
    }

}

