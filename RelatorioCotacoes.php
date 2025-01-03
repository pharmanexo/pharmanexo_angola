<?php

class RelatorioCotacoes extends CI_Controller
{

    private $mix;

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {

        $forns = [5042, 5043, 5044];

        $dbSint = $this->load->database('sintese', true);

        $cotacoes = $dbSint
            ->select('c.cd_cotacao, c.motivo_recusa, c.usuario_recusa, c.data_recusa, c.obs_recusa, cp.cnpj, cp.nome_fantasia, cp.razao_social, cp.estado as estado_comprador')
            ->from('cotacoes c')
            ->join('pharmanexo.compradores cp', 'cp.id = c.id_cliente')
            // ->where('c.cd_cotacao', 'COT9621-3314')
            ->where("dt_inicio_cotacao between '2022-12-01 00:00:00' and '2022-12-31 23:59:59'")
            ->where_in('c.id_fornecedor', $forns)
            ->group_by('c.cd_cotacao')
            ->get()
            ->result_array();

        foreach ($cotacoes as $cotacao) {


            $produtosCotacao = $dbSint
                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                ->where_in('id_fornecedor', $forns)
                ->group_by('id_produto_sintese, cd_produto_comprador')
                ->get('cotacoes_produtos')
                ->result_array();


            $prodsRespondidos = $this->db
                ->select('cp.*, u.nickname, u.email, cat.codigo, cat.nome_comercial')
                ->from('cotacoes_produtos cp')
                ->join('usuarios u', 'u.id = cp.id_usuario')
                ->join('produtos_catalogo cat', 'cat.codigo = cp.id_pfv and cat.id_fornecedor = cp.id_fornecedor')
                ->where('cd_cotacao', $cotacao['cd_cotacao'])
                ->where('submetido', 1)
                ->where_in('cp.id_fornecedor', $forns)
                ->get()
                ->result_array();


            $prodsGanhadores = $this->db
                ->select('osp.*, os.Cd_Ordem_Compra, os.id_fornecedor')
                ->from('ocs_sintese_produtos osp')
                ->join('ocs_sintese os', 'osp.id_ordem_compra = os.id')
                ->where('os.Cd_Cotacao', $cotacao['cd_cotacao'])
                ->where_in('os.id_fornecedor', $forns)
                ->get()
                ->result_array();


            foreach ($produtosCotacao as $k => $prodCot) {

                unset($produtosCotacao[$k]['sn_item_contrato']);
                unset($produtosCotacao[$k]['sn_permite_exibir']);

                $produtosCotacao[$k]['forn'] = '4BIO';
                $produtosCotacao[$k]['cnpj_comprador'] = $cotacao['cnpj'];
                $produtosCotacao[$k]['nome_fantasia'] = $cotacao['nome_fantasia'];
                $produtosCotacao[$k]['razao_social'] = $cotacao['razao_social'];
                $produtosCotacao[$k]['estado_comprador'] = $cotacao['estado_comprador'];
                $produtosCotacao[$k]['motivo_recusa'] = $cotacao['motivo_recusa'];
                $produtosCotacao[$k]['usuario_recusa'] = $cotacao['usuario_recusa'];
                $produtosCotacao[$k]['data_recusa'] = $cotacao['data_recusa'];
                $produtosCotacao[$k]['obs_recusa'] = $cotacao['obs_recusa'];

                if (!empty($prodsRespondidos)) {
                    foreach ($prodsRespondidos as $prodResp) {

                        if (($prodCot['cd_produto_comprador'] == $prodResp['cd_produto_comprador']) && ($prodCot['id_produto_sintese'] == $prodResp['id_produto'])) {
                            $produtosCotacao[$k]['respondido'] = 'SIM';
                            $produtosCotacao[$k]['codigo'] = $prodResp['codigo'];
                            $produtosCotacao[$k]['descricao_catalogo'] = $prodResp['nome_comercial'];
                            $produtosCotacao[$k]['respondido_por'] = $prodResp['nickname'];
                            $produtosCotacao[$k]['preco_oferta'] = $prodResp['preco_marca'];
                            $produtosCotacao[$k]['id_forn_oferta'] = $prodResp['id_fornecedor'];
                        }

                    }
                }

                if (!empty($prodsGanhadores)) {
                    foreach ($prodsGanhadores as $prodG) {
                        if (($prodCot['cd_produto_comprador'] == $prodG['Cd_Produto_Comprador']) && ($prodCot['id_produto_sintese'] == $prodG['Id_Produto_Sintese'])) {
                            $produtosCotacao[$k]['ganhou'] = 'SIM';
                            $produtosCotacao[$k]['cd_pedido'] = $prodG['Cd_Ordem_Compra'];
                            $produtosCotacao[$k]['preco_ganhador'] = $prodG['Vl_Preco_Produto'];
                            $produtosCotacao[$k]['loja'] = $prodG['id_fornecedor'];

                        }
                    }
                }

                $this->db->insert('temp_rel_oncoprod', $produtosCotacao[$k]);

            }


        }

    }
}