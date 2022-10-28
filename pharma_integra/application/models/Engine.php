<?php

class Engine extends MY_Model
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     */

    public function __construct()
    {
        parent::__construct();

        switch (MY_ENV) {

            case 'development':
                $this->db = $this->load->database('pharmahmg', true);
                break;

            case 'production':
                $this->db = $this->load->database('default', true);
                break;

            default:
                $this->db = $this->load->database('pharmahmg', true);
                break;
        }
    }

    public function start($string, $fornecedor, $time, $xmlId = NULL): bool
    {

        /**
         * Função que verifica se o fornecedor faz parte da Integração.
         * Marca o tempo de inicio e fim da Integração.
         */

        $integracao = $this->db->where('id', $fornecedor)
            ->get('fornecedores')->row_array()['integracao'];

        (intval($integracao) == 1) ? $bool = TRUE : $bool = FALSE;

        if ($string == 'BEGIN' && ($bool)) {

            $this->db->where('id', $fornecedor)
                ->set('inicio_atualizacao_estoque', date('Y-m-d H:i:s', $time))
                ->update('fornecedores');

        } else if ($string == 'END' && ($bool)) {

            $this->db->where('id', $fornecedor)
                ->set('termino_atualizacao_estoque', date('Y-m-d H:i:s', $time))
                ->set('ultimo_xml_id', $xmlId)
                ->update('fornecedores');
        }
        return $bool;
    }

    public function startIn($string, $fornecedor, $time): bool
    {
        /**
         * Função que verifica se o fornecedor faz parte da Integração.
         * Marca o tempo de inicio e fim da Integração.
         */

        $integracao = $this->db->where_in('id', $fornecedor)
            ->get('fornecedores')->row_array()['integracao'];

        (intval($integracao) == 1) ? $bool = TRUE : $bool = FALSE;

        if ($string == 'BEGIN' && ($bool)) {

            $this->db->where_in('id', $fornecedor)
                ->set('inicio_atualizacao_estoque', date('Y-m-d H:i:s', $time))
                ->update('fornecedores');

        } else if ($string == 'END' && ($bool)) {

            $this->db->where_in('id', $fornecedor)
                ->set('termino_atualizacao_estoque', date('Y-m-d H:i:s', $time))
                ->update('fornecedores');

        }
        return $bool;
    }

    public function cleanStock($fornecedor)
    {
        /**
         * Limpa o estoque do fornecedor.
         */

        $this->db->where('id_fornecedor', $fornecedor)
            ->where('fixo', 0)
            ->delete('produtos_lote');
    }

    public function cleanStockIn($fornecedor)
    {
        /**
         * Limpa o estoque do fornecedor.
         */
        $this->db->where_in('id_fornecedor', $fornecedor)
            ->where('fixo', 0)
            ->delete('produtos_lote');
    }

    public function checkXmlId($xmlId, $fornecedor)
    {
        /**
         * Verifica se o XML ID já foi inserido no Banco de Dados.
         * Apenas o Fornecedor Hospidrogas, utiliza esse método.
         */

        $itsXmlId = $this->db->where('ultimo_xml_id', $xmlId)->where('id', $fornecedor)
            ->get('fornecedores')->row_array();

        return IS_NULL($itsXmlId);
    }

    public function checkLot($array): bool
    {
        /**
         * Verifica se o lote já foi inserido no Banco de Dados.
         */
        $lote = $this->db->where('codigo', $array['codigo'])
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->where('lote', $array['lote'])
            ->where('local', $array['local'])
            ->get('produtos_lote')
            ->row_array();

        return IS_NULL($lote);
    }

    public function checkPrice($array): bool
    {
        /**
         * Verifica se o preço já foi inserido no Banco de Dados.
         */

        $vigencia = $this->db->select_max('data_criacao')
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->where('id_estado', $array['id_estado'])
            ->get('produtos_preco')
            ->row_array()['data_criacao'];

        $preco = $this->db->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->where('preco_unitario', $array['preco_unitario'])
            ->where('id_estado', $array['id_estado'])
            ->where('data_criacao', $vigencia)
            ->get('produtos_preco')
            ->row_array();

        return IS_NULL($preco);
    }

    public function checkPriceOncoprod($array): bool
    {
        /**
         * Verifica se o preço já foi inserido no Banco de Dados.
         */

        $vigencia = $this->db->select_max('data_criacao')
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->group_start()
            ->group_start()
            ->where('id_estado', $array['id_estado'])
            ->where('icms is NULL')
            ->group_end()
            ->or_group_start()
            ->where('id_estado IS NULL')
            ->where('icms', $array['icms'])
            ->group_end()
            ->group_end()
            ->get('produtos_preco_oncoprod')
            ->row_array()['data_criacao'];

        $preco = $this->db->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->where('preco_unitario', $array['preco_unitario'])
            ->where('data_criacao', $vigencia)
            ->group_start()
            ->group_start()
            ->where('id_estado', $array['id_estado'])
            ->where('icms is NULL')
            ->group_end()
            ->or_group_start()
            ->where('id_estado IS NULL')
            ->where('icms', $array['icms'])
            ->group_end()
            ->group_end()
            ->get('produtos_preco_oncoprod')
            ->row_array();

        return IS_NULL($preco);
    }

    public function checkPriceTime($array)
    {
        /**
         * Pega a data do preço
         */
        $vigencia = $this->db->select_max('data_criacao')
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->where('id_estado', $array['id_estado'])
            ->get('produtos_preco')
            ->row_array()['data_criacao'];

        $dt_preco = $this->db->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->where('id_estado', $array['id_estado'])
            ->where('data_criacao', $vigencia)
            ->get('produtos_preco')
            ->row_array()['data_criacao'];

        return $dt_preco;
    }

    public function checkCatalog($array): bool
    {
        /**
         * Verifica se o produto já foi inserido no Banco de Dados.
         */
        $catalogo = $this->db->where('id_fornecedor', $array['id_fornecedor'])
            ->where('codigo', $array['codigo'])
            ->get('produtos_catalogo')
            ->row_array();

        return IS_NULL($catalogo);
    }

    public function activeCatalog($array)
    {
        /**
         * Faz update nos produtos do catalogo.
         */
        $oncoProd = [12, 111, 112, 115, 120, 126, 123];

        if (in_array($array['id_fornecedor'], $oncoProd)) {

            foreach ($oncoProd as $for) {

                $this->db->where('id_fornecedor', $for);
                $this->db->where('codigo', $array['codigo']);
                $this->db->set('apresentacao', $array['apresentacao']);
                $this->db->set('rms', $array['rms']);
                //  $this->db->set('unidade', $array['unidade']);
                $this->db->set('marca', $array['marca']);

                $this->db->update('produtos_catalogo');

            }

        } else if ($array['id_fornecedor'] == 20) {

            $this->db->where('id_fornecedor', $array['id_fornecedor']);
            $this->db->where('codigo', $array['codigo']);
            $this->db->set('apresentacao', $array['apresentacao']);
            $this->db->set('quantidade_unidade', $array['quantidade_unidade']);
            $this->db->set('rms', $array['rms']);
            $this->db->set('marca', $array['marca']);

            $this->db->update('produtos_catalogo');

        } else {

            $this->db->where('id_fornecedor', $array['id_fornecedor']);
            $this->db->where('codigo', $array['codigo']);

            if (isset($array['codigo_externo'])){
                $this->db->set('codigo_externo', $array['codigo_externo']);
            }

            $this->db->set('nome_comercial', $array['nome_comercial']);
            $this->db->set('apresentacao', $array['apresentacao']);
            $this->db->set('quantidade_unidade', $array['quantidade_unidade']);
            $this->db->set('rms', $array['rms']);
            $this->db->set('marca', $array['marca']);


            $this->db->update('produtos_catalogo');
        }
    }

    public function updateIdMarca($array)
    {
        /**
         * Faz update nas marcas dos produtos no catalogo
         */
        $id_marca = $this->db->where('marca', $array['marca'])
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->limit(1)
            ->get('produtos_catalogo')
            ->row_array()['id_marca'];

        $id_marca = intval($id_marca);

        if (!IS_NULL($id_marca) && ($id_marca) != 0)
            $this->db->where('id_fornecedor', $array['id_fornecedor'])
                ->where('codigo', $array['codigo'])
                ->set('id_marca', intval($id_marca))
                ->update('produtos_catalogo');

    }

    public function checkIdMarca($array)
    {
        /**
         * Verifica o ID marca do produto.
         */
        $id_marca = $this->db->where('marca', $array['marca'])
            ->where('id_fornecedor', $array['id_fornecedor'])
            ->limit(1)
            ->get('produtos_catalogo')
            ->row_array()['id_marca'];

        return intval($id_marca);
    }
}


