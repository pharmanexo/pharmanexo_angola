<?php

class GetTotalEstoque extends CI_Controller
{

    private $oncoprod = [12, 111, 112, 115, 120, 123];

    public function __construct()
    {
        parent::__construct();

        #$this->load->model('Engine');
    }

    private function myQuery($fornecedor, $where)
    {

        return $this->db->query("SELECT CODIGO,
       ID_FORNECEDOR,
       ID_ESTADO,
       QUANTIDADE_UNIDADE,
       ESTOQUE,
       ESTOQUE_TOTAL,
       PRECO_UNITARIO,
       PRECO_TOTAL_GERAL,
       PRECO_TOTAL_ONCOPROD,
       LOTE,
       VALIDADE

        FROM (SELECT pl.codigo,
                     pl.id_fornecedor,
                     pp.id_estado,
                     pc.quantidade_unidade,
                     pl.estoque,
                     (pl.estoque * pc.quantidade_unidade) estoque_total,
                     pp.preco_unitario,
                     (CASE
                          WHEN pl.id_fornecedor NOT IN (12, 111, 112, 115, 120, 123) then (pl.estoque * pp.preco_unitario)
                          else NULL END)                  preco_total_geral,
                     (CASE
                          WHEN pl.id_fornecedor IN (12, 111, 112, 115, 120, 123) then (pl.estoque * pp.preco_unitario)
                          else NULL END)                  preco_total_oncoprod,
                     pl.lote,
                     pl.validade
        
              FROM pharmanexo.produtos_lote pl
        
                       JOIN pharmanexo.produtos_catalogo pc
                            on pc.codigo = pl.codigo
                                and pc.id_fornecedor = pl.id_fornecedor
                                and pc.ativo = 1
                                and pc.bloqueado = 0
        
                       JOIN pharmanexo.produtos_preco pp
                            on pp.codigo = pl.codigo
                                and pp.id_fornecedor = pl.id_fornecedor
        
        
              where pl.id_fornecedor = {$fornecedor}
                and pp.id_estado {$where}
        
                AND pp.data_criacao = (CASE
                                           WHEN ISNULL(pp.id_estado) then
                                               (select max(pp2.data_criacao)
                                                from pharmanexo.produtos_preco pp2
                                                where pp2.id_fornecedor = pl.id_fornecedor
                                                  and pp2.codigo = pl.codigo
                                                  and pp2.id_estado is null)
        
                                           ELSE
                                               (select max(pp2.data_criacao)
                                                from pharmanexo.produtos_preco pp2
                                                where pp2.id_fornecedor = pl.id_fornecedor
                                                  and pp2.codigo = pl.codigo
                                                  and pp2.id_estado = pp.id_estado)
                  END)
        
              GROUP BY pl.codigo,
                       pl.id_fornecedor,
                       pp.id_estado,
                       pc.quantidade_unidade,
                       pl.estoque,
                       pp.preco_unitario,
                       pl.lote,
                       pl.validade
                       
                       HAVING estoque > 0) x
                ")->result_array();

    }

    public function precoTotal()
    {

        $precoTotal = [];
        $result = [];
        $preco_total = 0;

        $fornecedores = $this->db->select('id')->get('fornecedores')->result_array();

        foreach ($fornecedores as $fornecedor) {

            $preco_geral = 0;
            $preco_oncoprod = 0;

            $where = '';

            $id_fornecedor = intval($fornecedor['id']);

            $uf_fornecedor = $this->db->where('id', $id_fornecedor)->get('fornecedores')->row_array()['estado'];

            $estado = $this->db->where('uf', $uf_fornecedor)->get('estados')->row_array()['id'];

            if (IS_NULL($estado))
                continue;

            if ($id_fornecedor == 20) {

                $where = 'is null';

            }
            if ($id_fornecedor == 104) {

                $where = "= {$estado}";

            } else if ($id_fornecedor == 15 || $id_fornecedor == 25 || $id_fornecedor == 180) {

                $where = "= {$estado}";

            } else if (in_array($id_fornecedor, $this->oncoprod)) {

                if ($id_fornecedor == 112) {
                    $where = '= 9';

                } else {
                    $where = "= {$estado}";
                }

            } else {

                $where = 'is null';
            }

            $arrayPrecos = $this->myQuery($id_fornecedor, $where);

            foreach ($arrayPrecos as $preço) {

                $preco_geral += $preço['PRECO_TOTAL_GERAL'];
                $preco_oncoprod += $preço['PRECO_TOTAL_ONCOPROD'];
            }

            if (in_array($id_fornecedor, $this->oncoprod)) {

                $precoTotal[] = [
                    'fornecedor' => $id_fornecedor,
                    'preco_total' => floatval($preco_oncoprod)
                ];

            } else {

                $precoTotal[] = [
                    'fornecedor' => $id_fornecedor,
                    'preco_total' => floatval($preco_geral)
                ];
            }
        }

        foreach ($precoTotal as $preco) {

            $preco_total += $preco['preco_total'];
        }


        if ($preco_total != 0) {

            $result = [
                'response' => true,
                'preco_total' => number_format($preco_total, 2, ',', '.')
            ];
        } else {
            $result = [
                'response' => true,
                'preco_total' => '0,00'
            ];
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
}