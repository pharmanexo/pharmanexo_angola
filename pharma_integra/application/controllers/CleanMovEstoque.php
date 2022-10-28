<?php

class CleanMovEstoque extends MY_Controller
{
    /**
     * @author : Chule Cabral
     * Data: 24/09/2020
     *
     * A rotina tem a função de limpar a tabela de log das integrações.
     */

    public function __construct()
    {
        parent::__construct();
    }

    protected function index_get()
    {
        /**
         * Armazena todos os fornecedores que tiveram integração no intervalo de 12 horas.
         */
        $fornecedores_rotina = $this->db->select('id, termino_atualizacao_estoque AS dt_param')
            ->where('sintese', 1)
            ->get('fornecedores')
            ->result_array();

        /**
         * Conforme o Objeto anterior, exclui a movimentação de estoque de todos os fornecedores
         * integrados no intervalo de 12 Horas.
         */
        foreach ($fornecedores_rotina as $fornecedor) {

            # Subtrai 12 dias de todas as datas de termino de estoque
            $data = date('Y-m-d H:i:s', strtotime("-12 days", strtotime($fornecedor['dt_param'])));

            $this->db->where('id_fornecedor', intval($fornecedor['id']))
                ->where("data_update < '{$data}'")
                ->delete('movimentacao_estoque');
        }
    }
}