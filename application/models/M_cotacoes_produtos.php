<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_cotacoes_produtos extends MY_Model
{
    protected $table = 'cotacoes_produtos';
    protected $primary_key = 'id';
    protected $primary_filter = 'intval';
    protected $order_field = '';
    protected $order_direction = 'ASC';

    protected $MIX;

    public function __construct()
    {
        parent::__construct();

        $this->MIX = null;
    }

    public function getTotalCotacoesPorPeriodo()
    {
        $dataInicial = date('Y-m-d', strtotime('-2 months'));
        $dataFinal = date('Y-m-d');

        $this->db->select('COUNT(id_sintese) AS total, uf_comprador, produto');
        $this->db->from($this->table);
        $this->db->where('id_fornecedor', $this->session->userdata('id_fornecedor'));
        // $this->db->where('data_cotacao >= LAST_DAY(NOW()) + INTERVAL 1 DAY - INTERVAL 3 MONTH');
        $this->db->where("data_cotacao BETWEEN '{$dataInicial}' AND '{$dataFinal}'");
        $this->db->where('rejeitado', 'N');
        $this->db->group_by('id_sintese');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function totalCotacoesPorMes($id_fornecedor, $ano)
    {
        $query = "
            SELECT 
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '1' THEN 1 END)  as 'Jan',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '2' THEN 1 END)  as 'Fev',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '3' THEN 1 END)  as 'Mar',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '4' THEN 1 END)  as 'Abr',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '5' THEN 1 END)  as 'Mai',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '6' THEN 1 END)  as 'Jun',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '7' THEN 1 END)  as 'Jul',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '8' THEN 1 END)  as 'Ago',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '9' THEN 1 END)  as 'Set',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '10' THEN 1 END) as 'Out',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '11' THEN 1 END) as 'Nov',
                COUNT(CASE WHEN MONTH(x.data_cotacao) = '12' THEN 1 END) as 'Dez'
            FROM (SELECT 
                    cot.cd_cotacao,
                    DATE_FORMAT(cot.data_criacao, '%Y-%m-%d') data_cotacao
                FROM pharmanexo.cotacoes_produtos cot
                WHERE id_fornecedor = {$id_fornecedor}
                    AND YEAR(cot.data_cotacao) = '{$ano}'
                GROUP BY cot.cd_cotacao,DATE_FORMAT(cot.data_criacao, '%Y-%m-%d')
                ORDER BY cot.cd_cotacao ASC) x
        ";

        return $this->db->query($query)->row_array();
    }

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

    public function getAmountCot($id_fornecedor, $periodo)
    {
        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = "MONTH(data_criacao) = '{$mes}' AND YEAR(data_criacao) = '{$ano}'";
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = "DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = "DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
        }

        $this->db->select("COUNT(DISTINCT cd_cotacao) qtd");
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where("{$where}");
        $this->db->where("submetido", 1);
        $cot = $this->db->get("cotacoes_produtos")->row_array();

        return intval($cot['qtd']);
    }

    public function getAcionamentosMix($periodo)
    {
        
        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = $this->MIX->where("MONTH(data_criacao) = '{$mes}' AND YEAR(data_criacao) = '{$ano}'");
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = $this->MIX->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = $this->MIX->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
        }

        $total = $this->MIX->count_all_results('cotacoes');

        return intval($total);
    }

    public function getPriceCot($id_fornecedor, $periodo)
    {
        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = "MONTH(data_criacao) = '{$mes}' AND YEAR(data_criacao) = '{$ano}'";
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = "DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = "DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'";

                break;
        }

        $this->db->select("SUM(qtd_solicitada * preco_marca) preco_total");
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where("{$where}");
        $this->db->where("submetido", 1);
        $cot = $this->db->get("cotacoes_produtos")->row_array();

        return (isset($cot['preco_total']) && !empty($cot['preco_total'])) ? $cot['preco_total'] : 0;
    }

    public function getOc($id_fornecedor, $periodo)
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

    public function getAmountManual($id_fornecedor, $periodo)
    {
        $this->db->select("COUNT(DISTINCT cd_cotacao) total");
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where("nivel", 1);
        $this->db->where("submetido", 1);

        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = $this->db->where("MONTH(data_criacao) = '{$mes}' AND YEAR(data_criacao) = '{$ano}'");
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = $this->db->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = $this->db->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
        }

        $total = $this->db->get('cotacoes_produtos')->row_array();

        return intval($total['total']);
    }

    public function getAmountAutomatica($id_fornecedor, $periodo)
    {
        $this->db->select("COUNT(DISTINCT cd_cotacao) total");
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where("nivel", 2);
        $this->db->where("submetido", 1);

        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = $this->db->where("MONTH(data_criacao) = '{$mes}' AND YEAR(data_criacao) = '{$ano}'");
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = $this->db->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = $this->db->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
        }

        $total = $this->db->get('cotacoes_produtos')->row_array();

        return intval($total['total']);
    }

    public function getAmountMix($id_fornecedor, $periodo)
    {
        $this->db->select("COUNT(DISTINCT cd_cotacao) total");
        $this->db->where("id_fornecedor", $id_fornecedor);
        $this->db->where("nivel", 3);
        $this->db->where("submetido", 1);

        switch ($periodo){
            case 'current':
                $mes = date('m', time());
                $ano = date('Y', time());

                $where = $this->db->where("MONTH(data_criacao) = '{$mes}' AND YEAR(data_criacao) = '{$ano}'");
                break;
            case '30days':
                $inicio = date('Y-m-d', strtotime('-30days'));
                $fim = date('Y-m-d', time());

                $where = $this->db->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
            case '6months':
                $inicio = date('Y-m-d', strtotime('-6months'));
                $fim = date('Y-m-d', time());

                $where = $this->db->where("DATE(data_criacao) BETWEEN '{$inicio}' AND '{$fim}'");

                break;
        }

        $total = $this->db->get('cotacoes_produtos')->row_array();

        return intval($total['total']);
    }
}

/* End of file: M_cotacoes_produtos.php */
