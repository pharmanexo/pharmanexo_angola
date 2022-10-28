<?php

class LittleBallGreen extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     *
     * Rotina para setar a Bolinha Verde nas Cotaçoes que Tem Depara.
     */

    private $db_cotacao;

    public function __construct()
    {
        parent::__construct();

        $this->db_cotacao = $this->load->database('cotacoes', true);
    }

    public function index_get()
    {

        /**
         * Verifica os fornecedores que fazem parte das Integraçoes
         */
        $fornecedor = $this->db->select('id')
            ->where('sintese', 1)
            ->get('fornecedores')
            ->result_array();

        foreach ($fornecedor as $for) {

            $cotacoes = [];

            /**
             * Verifica se o fornecedores tem alguma cotaçao em aberto.
             */
            $x = $this->db_cotacao->where('id_fornecedor', intval($for['id']))
                ->where('dt_fim_cotacao > now()')
                ->get('cotacoes')
                ->row_array();

            /**
             * Verifica se o fornecedor tem algum produto em estoque.
             */
            $y = $this->db->where('id_fornecedor', intval($for['id']))
                ->get('vw_produtos_lotes')
                ->row_array();

            /**
             * Verifica se os produtos da cotaçao do fornecedor tem produtos e estoque
             */
            if (!IS_NULL($x) && !IS_NULL($y)) {

                $atende = $this->db->select('cd_cotacao')
                    ->where('id_fornecedor', intval($for['id']))
                    ->where('estoque', 1)
                    ->group_by('cd_cotacao')
                    ->get('vw_verifica_estoque_cotacao')
                    ->result_array();

            } else {
                continue;
            }

            /**
             * Armazena todas as cotaçoes passiveis de resposta
             */
            foreach ($atende as $atend) {

                $cotacoes[] = $atend['cd_cotacao'];
            }

            /**
             * Armazena todas as cotaçoes passiveis de resposta
             */
            if (!empty($cotacoes)) {

                /**
                 * Se existir cotaçoes que sao possiveis de atendimento, seta 1 em Oferta. Bolinha Verde.
                 * Continua para o proximo fornecedor.
                 */
                $this->db_cotacao->where('id_fornecedor', intval($for['id']))
                    ->where_in('cd_cotacao', $cotacoes)
                    ->where('dt_fim_cotacao > now()')
                    ->where('oferta', 0)
                    ->where('visitado', 0)
                    ->set('oferta', 1)
                    ->update('cotacoes');
            } else {
                continue;
            }
        }

        /**
         *Se Nao existir nenhuma cotaçao aberta que possa ser atendida, seta visitado como 1 e a rotina termina.
         */
        $this->db_cotacao->where('dt_fim_cotacao > now()')
            ->where('visitado', 0)
            ->set('visitado', 1)
            ->update('cotacoes');

    }
}