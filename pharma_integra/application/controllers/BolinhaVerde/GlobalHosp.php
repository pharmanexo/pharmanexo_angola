<?php

class GlobalHosp extends MY_Controller
{

    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     *
     * Rotina para setar a Bolinha Verde nas Cotaçoes da Sintese que Tem Depara.
     *
     * Crontab => 45 7-23 * * * curl --request GET https://pharmanexo.com.br/pharma_integra/BolinhaVerde/Sintese
     */

    private $sint;

    public function __construct()
    {
        parent::__construct();

        $this->sint = $this->load->database('cotacoes', true);

    }

    public function index_get()
    {

        /**
         * Verifica os fornecedores que fazem parte das Integraçoes
         */
        $fornecedor = $this->db->select('id')
            ->where('sintese', 1)
             ->where('id', 5038)
            ->get('fornecedores')
            ->result_array();

        foreach ($fornecedor as $for) {

            $cotacoes = [];

            /**
             * Verifica se o fornecedores tem alguma cotaçao em aberto.
             */
            $x = $this->sint->where('id_fornecedor', intval($for['id']))
                ->where('dt_fim_cotacao > now()')
                ->where('(oferta = 0 OR oferta is null)')
                ->get('cotacoes')
                ->row_array();


            /**
             * Verifica se o fornecedor tem algum produto em estoque.
             */
            $y = $this->db->where('id_fornecedor', intval($for['id']))
                ->get('vw_produtos_lotes')
                ->row_array();
            if (empty($y)){
                continue;
            }

            /**
             * Verifica se os produtos da cotaçao do fornecedor tem produtos e estoque
             */
            if (!IS_NULL($x) && !IS_NULL($y)) {

                $atende = $this->db->select('cd_cotacao')
                    ->where('id_fornecedor', intval($for['id']))
                   # ->where('estoque', 1)
                    ->group_by('cd_cotacao')
                    ->get('vw_verifica_estoque_cotacao_cte')
                    ->result_array();

                if (empty($atende))
                    continue;

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
                $this->sint->where('id_fornecedor', intval($for['id']))
                    ->where_in('cd_cotacao', $cotacoes)
                    ->where('dt_fim_cotacao > now()')
                    ->where('oferta', 0)
                    ->where('visitado', 0)
                    ->set('oferta', 1)
                    ->update('cotacoes');
            } else {
                continue;
            }


            /**
             *Se Nao existir nenhuma cotaçao aberta que possa ser atendida, seta visitado como 1 e a rotina termina.
             */
            $this->sint->where('dt_fim_cotacao > now()')
                ->where('visitado', 0)
                ->where('id_fornecedor', intval($for['id']))
                ->set('visitado', 1)
                ->update('cotacoes');
        }



    }
}
