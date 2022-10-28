<?php

class RemoveCotacoesDuplicadas extends CI_Controller
{
    private $DB1, $DB2;

    public function __construct()
    {
        parent::__construct();
        $this->DB1 = $this->load->database('default', true);
        $this->DB2 = $this->load->database('sintese', true);
    }

    public function index()
    {
        $arrCot = $this->DB2->query("SELECT cd_cotacao, COUNT(cd_cotacao) as total
                                        FROM cotacoes
                                        WHERE id_fornecedor = 20
                                        GROUP BY cd_cotacao
                                        HAVING total > 1")->result_array();

        foreach ($arrCot as $cot) {

            $cotacoes = $this->DB2->where("cd_cotacao = '{$cot['cd_cotacao']}' and id_fornecedor = 20")->get('cotacoes')->row_array();

            $t = $this->DB2->where('id_fornecedor', 20)->where('cd_cotacao', $cot['cd_cotacao'])->where("id <> {$cotacoes['id']}")->delete('cotacoes');

        }


    }

}
