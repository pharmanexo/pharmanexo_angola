<?php

class consetarClientes extends CI_Controller
{
    private $dbSintese;
    private $dbMix;

    public function __construct()
    {
        parent::__construct();

        $this->dbSintese = $this->load->database('sintese', true);
        $this->dbMix = $this->load->database('mix', true);
    }

    public function index()
    {
        set_time_limit(0);
        ini_set('memory_limit', '40000M');

        $cotacoes_comprador = $this->dbSintese->query("
            SELECT cd_comprador, GROUP_CONCAT(id order by id ASC) AS ids 
            FROM cotacoes 
            GROUP BY cd_comprador
        ")->result_array();

        $naoencontrado = [];

        foreach ($cotacoes_comprador as $kk => $row) {
            
            $cnpj = mask($row['cd_comprador'], '##.###.###/####-##');

            $ids = rtrim($row['ids'], ',');

            $cliente = $this->db->where("cnpj = '{$cnpj}'")->get('compradores')->row_array();

            if ( isset($cliente) && !empty($cliente) ) {

                $this->dbSintese->where("cd_comprador", $row['cd_comprador'])->update('cotacoes', ['id_cliente' => $cliente['id'], 'data_atualizacao' => '2020-08-11 11:33:00']);
            } else {

                $naoencontrado[] = $row;
            }
        }

        var_dump($naoencontrado); exit();
    }

    public function corrigirTabelas()
    {
        set_time_limit(0);
        ini_set('memory_limit', '40000M');


        $clientes = $this->db->query("SELECT cp.cnpj, GROUP_CONCAT(cp.id order by id ASC) as ids FROM pharmanexo.compradores cp group by cp.cnpj")->result_array();
        $excluir = [];

        foreach ($clientes as $cliente) {

            $ids = explode(',', $cliente['ids']);

            $idPrimary = $ids[0];
            unset($ids[0]);

            $values = [
                "id_cliente" => $idPrimary
            ];

            if (!empty($ids) || !isset($ids)) {

               $this->db->where_in('id_cliente', $ids)
                   ->update('vendas_diferenciadas', $values);

               $this->db->where_in('id_cliente', $ids)
                    ->update('restricoes_produtos_clientes', ["id_cliente" => $idPrimary, 'data_atualizacao' => date("Y-m-d H:i:s")]);

                $this->db->where_in('id_cliente', $ids)
                    ->update('controle_cotacoes', $values);

                $this->db->where_in('id_cliente', $ids)
                    ->update('prazos_entrega', $values);

                $this->db->where_in('id_cliente', $ids)
                    ->update('valor_minimo_cliente', $values);

                $this->db->where_in('id_cliente', $ids)
                    ->update('formas_pagamento_fornecedores', $values);

                $this->db->where_in('id_cliente', $ids)
                    ->update('cotacoes_produtos', $values);

                $this->db->where_in('id_cliente', $ids)
                    ->update('cotacoes', $values);

                $this->db->where_in('id_cliente', $ids)
                    ->update('email_notificacao', $values);

                $this->db->where_in('id_comprador', $ids)
                    ->update('ocs_sintese', $values);

                $this->db->where_in('id_cliente', $ids)
                    ->update('pedidos_produtos_fornecedores', $values);

                $this->dbSintese->where_in('id_cliente', $ids)
                    ->update('cotacoes', $values);

                $this->dbMix->where_in('id_cliente', $ids)
                    ->update('cotacoes', $values);

                $this->dbMix->where_in('id_cliente', $ids)
                    ->update('cotacoes', $values);

                $this->dbMix->where_in('id_cliente', $ids)
                    ->update('fornecedores_mix_provisorio', $values);

                $this->dbMix->where_in('id_cliente', $ids)
                    ->update('produtos_preco_mix', $values);


                foreach ($ids as $id) {

                    $this->db->where('id', $id)->delete('compradores');
                }
            }
        }

        var_dump('fim');
        exit();
    }
}