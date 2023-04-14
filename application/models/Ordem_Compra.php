<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ordem_Compra extends MY_Model
{
    protected $table = 'ocs_sintese';
    protected $primary_key = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_products($id, $pendente = false){
        $this->db->where('id_ordem_compra', $id);

        if ($pendente){
            $this->db->where('resgatado', 0);
        }


        return $this->db->get('ocs_sintese_produtos')->result_array();
    }

    public function get_status($codigoStatus){

        $this->db->where('codigo', $codigoStatus);
        return $this->db->get('tp_situacao_oc')->row_array();
    }

    public function get_usuarios($id_fornecedor){

        $this->db->where('id_fornecedor', $id_fornecedor);
        return $this->db->get('usuarios_resgate')->result_array();
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