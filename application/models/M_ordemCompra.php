<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ordemCompra extends MY_Model
{
    protected $table = 'ocs_sintese';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtem o valor total dos produtos enviados e aprovados com ordem de compra
     *
     * @param - INT  ID fornecedor
     * @param - String tipo do periodo
     * @return  array
     */
    public function getTotalPriceOc($id_fornecedor, $periodo)
    {
        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = "MONTH(ocs.Dt_Ordem_Compra) = '{$mes}' AND YEAR(ocs.Dt_Ordem_Compra) = '{$ano}'";
                $where2 = "MONTH(cp.data_criacao) = '{$mes}' AND YEAR(cp.data_criacao) = '{$ano}'";
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = "DATE(ocs.Dt_Ordem_Compra) BETWEEN '{$inicio}' AND '{$fim}'";
                $where2 = "DATE(cp.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = "DATE(ocs.Dt_Ordem_Compra) BETWEEN '{$inicio}' AND '{$fim}'";
                $where2 = "DATE(cp.data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
        }

        # Obtem o valor total dos produtos enviados e aprovados com ordem de compra
        $ocs = $this->db->select("sum(oc_prod.Qt_Produto * oc_prod.Vl_Preco_Produto) as preco_total")
            ->from('ocs_sintese ocs')
            ->join('ocs_sintese_produtos oc_prod', 'oc_prod.id_ordem_compra = ocs.id')
            ->where('ocs.id_fornecedor', $id_fornecedor)
            ->where('ocs.pendente', 0)
            ->where($where)
            ->get()
            ->row_array();

        return (isset($ocs) && !empty($ocs['preco_total'])) ? $ocs['preco_total'] : 0;
    }

    /**
     * Obtem a quantidade de ordens de compra por periodo
     *
     * @param - int  id fornecedor
     * @param - String nome do periodo  
     * @return  int
     */
    public function getAmountOc($id_fornecedor, $periodo)
    {
        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = "MONTH(Dt_Ordem_Compra) = '{$mes}' AND YEAR(Dt_Ordem_Compra) = '{$ano}'";
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = "DATE(Dt_Ordem_Compra) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = "DATE(Dt_Ordem_Compra) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
        }

        $this->db->select("COUNT(DISTINCT Cd_Ordem_Compra) qtd");
        $this->db->from("ocs_sintese");
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where("{$where}");
        $this->db->where("Status_OrdemCompra", 2);
        $oc = $this->db->get()->row_array();

        return intval($oc['qtd']);
    }

    /**
     * Obtem os produtos de uma oc
     *
     * @param - INT Id da ordem de compra
     * @return  array
     */
    public function get_products($id, $pendente = false)
    {
        $this->db->where('id_ordem_compra', $id);

        if ($pendente){
            $this->db->where('resgatado', 0);
        }
       

        return $this->db->get('ocs_sintese_produtos')->result_array();
    }

    /**
     * Obtem o status de uma oc
     *
     * @param - INT codigo do status da OC
     * @return  array
     */
    public function get_status($codigoStatus)
    {

        $this->db->where('codigo', $codigoStatus);
        return $this->db->get('tp_situacao_oc')->row_array();
    }

    public function getCotFormaPagamento($id_fornecedor, $cot, $integrador)
    {
        $id_fp = $this->db
            ->select('id_forma_pagamento')
            ->where('id_fornecedor', $id_fornecedor)
            ->where('cd_cotacao', $cot)
            ->order_by('data_criacao DESC')
            ->limit(1)
            ->get('cotacoes_produtos')
            ->row_array();

        if (!empty($id_fp) && !is_null($id_fp)) {
            $fp = $this->db
                ->select('fp.descricao')
                ->from('formas_pagamento_depara fpd')
                ->join('formas_pagamento fp', 'fp.id = fpd.id_forma_pagamento')
                ->where('fpd.cd_forma_pagamento', $id_fp['id_forma_pagamento'])
                ->where('fpd.integrador', $integrador)
                ->get()
                ->row_array();

            return $fp['descricao'];
        }

        return false;


    }
}