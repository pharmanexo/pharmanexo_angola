<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class M_relatorios extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function getCotacoes($data, $all = false)
    {
        $this->db->start_cache();
        $this->db->select("c.cd_comprador, c.dt_inicio_cotacao, c.id_cliente, cmp.nome_fantasia, c.uf_cotacao, cp.*, r.id_assistente as assistente, r.id_consultor as consultor, r.id_gerente as gerente, pcp.id as id_oferta
        ");

        $this->db->from("cotacoes_sintese.cotacoes_produtos cp");
        $this->db->join("cotacoes_sintese.cotacoes c", "cp.cd_cotacao = c.cd_cotacao and c.id_fornecedor = cp.id_fornecedor");
        $this->db->join("pharmanexo.compradores cmp", "cmp.id = c.id_cliente");
        $this->db->join("pharmanexo.cot_responsaveis r", "r.id_comprador = c.id_cliente", 'LEFT');
        $this->db->join("pharmanexo.cotacoes_produtos pcp", "pcp.id_produto = cp.id_produto_sintese and pcp.cd_cotacao = cp.cd_cotacao and pcp.id_fornecedor = cp.id_fornecedor and pcp.cd_produto_comprador = cp.cd_produto_comprador and pcp.submetido = 1", 'LEFT');

        if (!empty($data['id_fornecedor'])) {
            $this->db->where_in('c.id_fornecedor', explode(',', $data['id_fornecedor']));
        }

        if (!empty($data['cd_cotacao'])) {
            $this->db->where('c.cd_cotacao', $data['cd_cotacao']);
        }

        if (!empty($data['dataini']) and !empty($data['datafim'])) {

            $this->db->where("c.dt_inicio_cotacao between '{$data['dataini']}' and '{$data['datafim']}'");
        }

        if (!empty($data['assistente'])) {
            $this->db->where_in('r.id_assistente', explode(',', $data['assistente']));
        }

        if (!empty($data['consultor'])) {
            $this->db->where_in('r.id_consultor', explode(',', $data['consultor']));
        }

        if (!empty($data['gerente'])) {
            $this->db->where_in('r.id_gerente', explode(',', $data['gerente']));
        }

        if (!empty($data['produto'])) {
            $this->db->where("cp.ds_produto_comprador like '%{$data['produto']}%'");
        }


        if (isset($data['status'])) {

            if ($data['status'] == 1) {
                $this->db->where("pcp.id > 0");
            }

            if ($data['status'] == 0) {
                $this->db->where("pcp.id is null");
            }

        }

        $this->db->order_by('c.dt_inicio_cotacao DESC');

        $this->db->stop_cache();
        $recordsFiltered = $this->db->count_all_results();

        if (!$all) {
            if (isset($data['start']) && $data['length'] && $data['length'] > 0) {
                $this->db->limit($data['length'], $data['start']);
            }
        }


        # query
        $query = $this->db->get();
        # var_dump($this->db->last_query());exit();
        $rows = $query->result_array();
        $recordsTotal = $this->db->count_all_results("cotacoes_sintese.cotacoes_produtos cp");


        $query->free_result();
        $this->db->flush_cache();

        return ['totalRecords' => $recordsTotal, 'totalFiltered' => $recordsFiltered, 'data' => $rows];
    }

    public function getSumarizado($data, $all = false)
    {

        $this->db->start_cache();
        $this->db->select("ct.cd_comprador,
                           ct.id_cliente,
                           c.razao_social,
                           ct.cd_cotacao,
                           ct.uf_cotacao,
                           (select count(0) from cotacoes_sintese.cotacoes_produtos cp where cp.cd_cotacao = ct.cd_cotacao and cp.id_fornecedor = ct.id_fornecedor)                  as itens,
                           (select count(0) from pharmanexo.cotacoes_produtos pcp where pcp.cd_cotacao = ct.cd_cotacao and pcp.id_fornecedor = ct.id_fornecedor and pcp.ocultar = 0) as respondidos,
                           CASE
                               when ocs.id > 0 THEN 'SIM'
                               ELSE 'NÃO'
                               END                                                                                                                                                   AS confirmado,
                           ocs.Cd_Ordem_Compra                                                                                                                                       as oc,
                           (select count(0) from pharmanexo.ocs_sintese_produtos ocp where ocp.id_ordem_compra = ocs.id)                                                             as itens_oc,
                           (select sum(ocp.Qt_Produto * ocp.Vl_Preco_Produto) from pharmanexo.ocs_sintese_produtos ocp where ocp.id_ordem_compra = ocs.id)                           as total,
                           r.id_gerente as gerente,
                           ct.dt_inicio_cotacao,
                           ct.data_criacao
       ");

        $this->db->from('cotacoes_sintese.cotacoes ct');
        $this->db->join('pharmanexo.ocs_sintese ocs', 'ocs.Cd_Cotacao = ct.cd_cotacao and ocs.id_fornecedor = ct.id_fornecedor', 'LEFT');
        $this->db->join('pharmanexo.cot_responsaveis r', 'r.id_fornecedor = ct.id_fornecedor and r.id_comprador = ct.id_cliente', 'LEFT');
        $this->db->join('pharmanexo.compradores c', 'c.id = ct.id_cliente', 'LEFT');


        if (!empty($data['id_fornecedor'])) {
            $this->db->where_in('ct.id_fornecedor', explode(',', $data['id_fornecedor']));
        }

        if (!empty($data['cd_cotacao'])) {
            $this->db->where('ct.cd_cotacao', $data['cd_cotacao']);
        }

        if (!empty($data['dataini']) and !empty($data['datafim'])) {

            $this->db->where("ct.dt_inicio_cotacao between '{$data['dataini']}' and '{$data['datafim']}'");
        }

        if (!empty($data['cliente'])) {
            $this->db->where_in('ct.id_cliente', explode(',', $data['cliente']));
        }

        if (!empty($data['gerente'])) {
            $this->db->where_in('r.id_gerente', explode(',', $data['gerente']));
        }

        if (!empty($data['uf'])) {
            $this->db->where_in('ct.uf_cotacao', explode(',', $data['uf']));
        }

        if (!empty($data['confirmada'])) {
            if ($data['confirmada'] == 0) {
                $this->db->where('ocs.id is null');
            }

            if ($data['confirmada'] == 1) {
                $this->db->where('ocs.id > 0');
            }


        }


        $this->db->order_by('ct.dt_inicio_cotacao DESC');

        $this->db->stop_cache();
        $recordsFiltered = $this->db->count_all_results();

        if (!$all) {
            if (isset($data['start']) && $data['length'] && $data['length'] > 0) {
                $this->db->limit($data['length'], $data['start']);
            }
        }

        # query
        $query = $this->db->get();
        var_dump($this->db->last_query());
        exit();
        $rows = $query->result_array();

        $recordsTotal = $this->db->count_all_results("cotacoes_sintese.cotacoes ct");

        $query->free_result();
        $this->db->flush_cache();

        return ['totalRecords' => $recordsTotal, 'totalFiltered' => $recordsFiltered, 'data' => $rows];


    }

    public function getSemVinculo($data, $all = false)
    {
        $this->db->start_cache();
        $this->db->select("comp.cnpj, comp.nome_fantasia, comp.razao_social, comp.estado, cp.ds_produto_comprador, cp.qt_produto_total, cp.ds_unidade_compra, cot.dt_inicio_cotacao");
        $this->db->from("cotacoes_sintese.cotacoes_produtos cp");

        #joins
        $this->db->join('cotacoes_sintese.cotacoes cot', 'cot.cd_cotacao = cp.cd_cotacao and cot.id_fornecedor = cp.id_fornecedor');
        $this->db->join('pharmanexo.compradores comp', 'comp.id = cot.id_cliente', 'LEFT');
        $this->db->join('produtos_marca_sintese pms', 'cp.id_produto_sintese = pms.id_produto', 'LEFT');
        $this->db->join('produtos_fornecedores_sintese pfs', 'pfs.id_sintese = pms.id_sintese and pfs.id_fornecedor = cp.id_fornecedor', 'LEFT');


        if (!empty($data['id_fornecedor'])) {
            $this->db->where_in('cot.id_fornecedor', explode(',', $data['id_fornecedor']));
        }

        if (!empty($data['dataini']) and !empty($data['datafim'])) {

            $this->db->where("cot.dt_inicio_cotacao between '{$data['dataini']}' and '{$data['datafim']}'");
        }


        $this->db->order_by('cot.dt_inicio_cotacao DESC');
        $this->db->group_by('comp.cnpj, comp.nome_fantasia, comp.estado, cp.ds_produto_comprador, cp.qt_produto_total, cp.ds_unidade_compra, cot.dt_inicio_cotacao');

        $this->db->stop_cache();
        $recordsFiltered = $this->db->count_all_results();

        if (!$all) {
            if (isset($data['start']) && $data['length'] && $data['length'] > 0) {
                $this->db->limit($data['length'], $data['start']);
            }
        }


        # query
        $query = $this->db->get();
        # var_dump($this->db->last_query());exit();
        $rows = $query->result_array();
        $recordsTotal = $this->db->count_all_results("cotacoes_sintese.cotacoes_produtos cp");


        $query->free_result();
        $this->db->flush_cache();

        return ['totalRecords' => $recordsTotal, 'totalFiltered' => $recordsFiltered, 'data' => $rows];

    }

    public function getVendasYear($data)
    {

        $this->db->select("count(0) as ocs, sum(osp.Qt_Produto * osp.Vl_Preco_Produto) as total")
            ->from("ocs_sintese_produtos osp")
            ->join("ocs_sintese os", "os.id = osp.id_ordem_compra");


        if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {
            if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {

                if (is_array($data['id_fornecedor'])) {
                    $this->db->where_in("os.id_fornecedor", $data['id_fornecedor']);
                } else {
                    $this->db->where("os.id_fornecedor", $data['id_fornecedor']);
                }

            }
        }

        $this->db->where('YEAR(os.Dt_Ordem_Compra) = YEAR(now())');
        $this->db->where("os.pendente", 0);

        $c = $this->db->get();

        return $c->row_array();


    }

    public function getVendasDay($data)
    {

        $this->db->select("count(0) as ocs, sum(osp.Qt_Produto * osp.Vl_Preco_Produto) as total")
            ->from("ocs_sintese_produtos osp")
            ->join("ocs_sintese os", "os.id = osp.id_ordem_compra");


        if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {

            if (is_array($data['id_fornecedor'])) {
                $this->db->where_in("os.id_fornecedor", $data['id_fornecedor']);
            } else {
                $this->db->where("os.id_fornecedor", $data['id_fornecedor']);
            }

        }


        $this->db->where('date(os.Dt_Ordem_Compra) = date(now())');
        $this->db->where("os.pendente", 0);
        $c = $this->db->get();


        return $c->row_array();


    }

    public function getVendasPeriodo($data)
    {

        $this->db->select("count(0) as ocs, sum(osp.Qt_Produto * osp.Vl_Preco_Produto) as total")
            ->from("ocs_sintese_produtos osp")
            ->join("ocs_sintese os", "os.id = osp.id_ordem_compra");


        if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {

            if (is_array($data['id_fornecedor'])) {
                $this->db->where_in("os.id_fornecedor", $data['id_fornecedor']);
            } else {
                $this->db->where("os.id_fornecedor", $data['id_fornecedor']);
            }

        }


        if (!empty($data['dataini']) and !empty($data['datafim'])) {

            $this->db->where("os.Dt_Ordem_Compra between '{$data['dataini']}' and '{$data['datafim']}'");
        }

        $this->db->where("os.pendente", 0);
        // $this->db->where("os.transaction_id > 0");
        $result = $this->db->get()->row_array();


        return $result;
    }

    public function getRankingVendas($data)
    {

        $this->db->select("os.id, os.Cd_Ordem_Compra as oc, count(0) as itens, sum(osp.Qt_Produto * osp.Vl_Preco_Produto) as total")
            ->from("ocs_sintese_produtos osp")
            ->join("ocs_sintese os", "os.id = osp.id_ordem_compra");

        if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {


            if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {

                if (is_array($data['id_fornecedor'])) {
                    $this->db->where_in("os.id_fornecedor", $data['id_fornecedor']);
                } else {
                    $this->db->where("os.id_fornecedor", $data['id_fornecedor']);
                }

            }
        }

        if (!empty($data['dataini']) and !empty($data['datafim'])) {
            $this->db->where("os.Dt_Ordem_Compra between '{$data['dataini']}' and '{$data['datafim']}'");
        }

        $this->db->where("os.pendente", 0);
        $this->db->where("os.transaction_id > 0");

        $this->db->group_by('os.Cd_Ordem_Compra');
        $this->db->order_by('total desc');
        $this->db->limit(10);

        $a = $this->db->get();


        $this->db->select("os.id, os.Cd_Ordem_Compra as oc, count(0) as itens, osp.codigo, sum(osp.Qt_Produto * osp.Vl_Preco_Produto) as total")
            ->from("ocs_sintese_produtos osp")
            ->join("ocs_sintese os", "os.id = osp.id_ordem_compra");

        if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {
            if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {

                if (is_array($data['id_fornecedor'])) {
                    $this->db->where_in("os.id_fornecedor", $data['id_fornecedor']);
                } else {
                    $this->db->where("os.id_fornecedor", $data['id_fornecedor']);
                }

            }
        }

        if (!empty($data['dataini']) and !empty($data['datafim'])) {
            $this->db->where("os.Dt_Ordem_Compra between '{$data['dataini']}' and '{$data['datafim']}'");
        }
        $this->db->where("os.pendente", 0);
        // $this->db->where("os.transaction_id > 0");

        $this->db->group_by('osp.codigo');
        $this->db->order_by('itens DESC');
        $this->db->limit(10);

        $b = $this->db->get();

        $data = [
            'ocs' => $a->result_array(),
            'produtos' => $b->result_array()
        ];


        return $data;

    }

    public function getRelGeral($filtros = null)
    {
        $page = (isset($filtros['page'])) ? intval($filtros['page']) : 1;
        $limit = 100;
        $offset = $limit * ($page - 1);


        $where = '';

        $oncoprod = explode(',', ONCOPROD);
        if (in_array($this->session->id_fornecedor, $oncoprod)) {
            $where .= "ct.id_fornecedor in (" . ONCOPROD . ") AND ";
        } else {
            $where .= "ct.id_fornecedor = {$this->session->id_fornecedor} AND ";
        }

        if (isset($filtros['id_clientes'])) {
            $where .= "ct.id_cliente in ({$filtros['id_clientes']}) AND ";
        }

        if (!empty($filtros['dataini']) and !empty($filtros['datafim'])) {
            $where .= "ct.dt_inicio_cotacao between '{$filtros['dataini']}}' and '{$filtros['datafim']}' AND ";
        }

        $where = rtrim($where, "AND ");

        $query = "
                        select ct.data_criacao                                                           as data_cotacao,
                       ct.cd_cotacao,
                       c.cnpj,
                       c.nome_fantasia,
                       cp.ds_produto_comprador,
                       cp.cd_produto_comprador,
                       ppc.codigo                                                                as codigo_kraft,
                       ppc.nome_comercial                                                        as produto_kraft,
                       ppc.marca                                                                 as fabricante,
                       cp.qt_produto_total,
                       if(ct.motivo_recusa > 0, 'SIM', 'NÃO')                                    AS descartado,
                       if(ct.motivo_recusa > 0, ct.data_recusa, 'NÃO')                                    AS data_descarte,
                       if(ct.motivo_recusa > 0, mt.descricao, '-')                               AS motivo_descarte,
                       if(ct.motivo_recusa > 0, ct.obs_recusa, '-')                              AS obs_descarte,
                       if(ct.motivo_recusa > 0, (select nome from usuarios us where us.id = ct.usuario_recusa),
                          '-')                                                                   AS usuario_descarte,
                       pcp.data_criacao                                                           as data_resposta,
                       if(pcp.id > 0, 'SIM', 'NÃO')                                              as Respondido,
                       u.nome                                                                    as usuario_resposta,
                       if(pcp.preco_marca > 0, pcp.preco_marca, '0.0000')                        as preco_ofertado,
                       if(pcp.preco_marca > 0, (pcp.qtd_solicitada * pcp.preco_marca), '0.0000') as total_oferta,
                       pcp.id_pfv                                                                as codigo_ofertado,
                       (osp.Qt_Produto * osp.Vl_Preco_Produto)                                   as total_oc,
                       if(osp.Vl_Preco_Produto > 0, oc.Dt_Gravacao, '')                          as data_oc,
                       f.cnpj as cnpj_loja,
                       f.nome_fantasia as loja,
                       ct.uf_cotacao as uf_cotacao
                from cotacoes_sintese.cotacoes ct
                         join cotacoes_sintese.cotacoes_produtos cp
                              on cp.cd_cotacao = ct.cd_cotacao and cp.id_fornecedor = ct.id_fornecedor
                         join compradores c on c.id = ct.id_cliente
                         left join pharmanexo.cotacoes_produtos pcp on pcp.cd_produto_comprador = cp.cd_produto_comprador
                    and pcp.cd_cotacao = ct.cd_cotacao and pcp.id_fornecedor = ct.id_fornecedor
                         left join pharmanexo.ocs_sintese oc on oc.Cd_Cotacao = pcp.cd_cotacao and oc.id_fornecedor = ct.id_fornecedor
                         left join pharmanexo.ocs_sintese_produtos osp on osp.codigo = pcp.id_pfv and osp.id_ordem_compra = oc.id and
                                                                          osp.Cd_Produto_Comprador = cp.cd_produto_comprador
                         left join usuarios u on u.id = pcp.id_usuario
                         left join motivos_recusa_cotacoes mt on mt.id = ct.motivo_recusa
                         left join pharmanexo.produtos_catalogo ppc on ppc.codigo = pcp.id_pfv and ppc.id_fornecedor = pcp.id_fornecedor
                         join pharmanexo.fornecedores f on f.id = ct.id_fornecedor
                where {$where}
                group by ct.id_cliente, cp.id_produto_sintese, cp.cd_produto_comprador, f.id
                order by ct.data_criacao ASC ";


      /*  $rows = $this->db->query($query)->num_rows();*/

      //  $query = $query . " limit {$limit} offset $offset";

        $data = $this->db->query($query)->result_array();

        var_dump($data);
        exit();

       /* $output = [
            "rows" => $rows,
            "pages" => ceil(($rows / $limit)),
            "data" => $data,
        ];*/

        return $data;


    }

    public function getRankingVendasCompradores($data)
    {

        $this->db->select("c.cnpj,c.nome_fantasia, sum(osp.Qt_Produto * osp.Vl_Preco_Produto) as total, c.estado")
            ->from("ocs_sintese_produtos osp")
            ->join("ocs_sintese os", "os.id = osp.id_ordem_compra")
            ->join("compradores c", "c.id = os.id_comprador");

        if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {


            if (isset($data['id_fornecedor']) && !empty($data['id_fornecedor'])) {

                if (is_array($data['id_fornecedor'])) {
                    $this->db->where_in("os.id_fornecedor", $data['id_fornecedor']);
                } else {
                    $this->db->where("os.id_fornecedor", $data['id_fornecedor']);
                }

            }
        }

        if (!empty($data['dataini']) and !empty($data['datafim'])) {
            $this->db->where("os.Dt_Ordem_Compra between '{$data['dataini']}' and '{$data['datafim']}'");
        }


        if (!empty($data['estados']) and !empty($data['estados'])) {
            $this->db->where_in("c.estado", $data['estados']);
        }

        $this->db->where("os.pendente", 0);
        $this->db->where("os.transaction_id > 0");

        $this->db->group_by('os.id_comprador');
        $this->db->order_by('total desc');


        $a = $this->db->get();


        $data = $a->result_array();


        return $data;

    }

    public function getResponsaveis($id_cliente)
    {
        return $this->db->where('id_comprador', $id_cliente)->get('cot_responsaveis')->row_array();

    }

    public function getPessoaEquipe($id_pessoa)
    {
        return $this->db->where('id', $id_pessoa)->get('equipe_comercial')->row_array();

    }

    public function getOfertas($data)
    {
        $this->db->select('qtd_solicitada, preco_marca, data_criacao');

        if (isset($data['id_produto_sintese'])) {
            $this->db->where('id_produto', $data['id_produto_sintese']);
        }

        if (isset($data['cd_produto_comprador'])) {
            $this->db->where("(cd_produto_comprador = '{$data['cd_produto_comprador']}' OR cd_produto_comprador is null)");
        }

        if (isset($data['cd_cotacao'])) {
            $this->db->where('cd_cotacao', $data['cd_cotacao']);
        }

        if (isset($data['id_fornecedor'])) {
            $this->db->where('id_fornecedor', $data['id_fornecedor']);
        }

        $this->db->where('submetido', 1);
        $this->db->where('preco_marca >', 0);

        $q = $this->db->get('cotacoes_produtos');


        return $q->result_array();

    }

    public function getEquipeAll($tipo)
    {
        return $this->db->where('cargo_id', $tipo)->get('equipe_comercial')->result_array();
    }


    //RELATORIO ANALITO USUARIOS
    public function getAnaliticoUsuarios($data)
    {
        $idForn = $this->session->id_fornecedor;
        $forns = explode(',', ONCOPROD);

        if (in_array($idForn, $forns)) {
            $forns = ONCOPROD;
            $where = "co.id_fornecedor in ({$forns}) AND ";
        } else {
            $where = "co.id_fornecedor = {$idForn} AND ";
        }


        if (isset($data['dataini']) && isset($data['datafim'])) {
            $where .= "cast(co.data_criacao as date) >= '{$data['dataini']}' AND ";
            $where .= "cast(co.data_criacao as date) <= '{$data['datafim']}' AND ";
        } else {
            $where .= "year(co.data_criacao) = year(now()) AND MONTH(co.data_criacao) =  MONTH(now()) AND ";
        }

        if (isset($data['usuario'])) {
            $where .= "u.id = {$data['usuario']} AND ";
        }

        $where = rtrim($where, 'AND ');


        $query = "
                    SELECT grupo.id,
                   grupo.usuario,
                   grupo.estado,
                   grupo.qtd_cotacoes,
                   grupo.qtd_itens_ofertados,
                   grupo.qtd_pedidos_convertidos,
                   qtd_itens_convertidos,
                   IFNULL(soma.total_vendido, 0) as total_vendido
            from
                (
                    SELECT
                        q.id,
                        q.usuario,
                        q.estado,
                        max(q.qtd_cotacoes) qtd_cotacoes,
                        max(q.qtd_itens_ofertados) qtd_itens_ofertados,
                        max(q.qtd_pedidos_convertidos) qtd_pedidos_convertidos,
                        max(q.qtd_itens_convertidos) qtd_itens_convertidos
                    from
                        (
                            select
                                u.id,
                                u.nome usuario,
                                c.estado,
                                count(distinct co.cd_cotacao) qtd_cotacoes,
                                count(co.cd_cotacao) qtd_itens_ofertados,
                                count(distinct os.Cd_Ordem_Compra) qtd_pedidos_convertidos,
                                count(distinct osp.id) qtd_itens_convertidos
                            from
                                pharmanexo.usuarios u
                                    left join pharmanexo.cotacoes_produtos co on
                                        u.id = co.id_usuario
                                    left join compradores c on
                                        c.id = co.id_cliente
                                    left join ocs_sintese os on
                                            co.cd_cotacao = os.cd_cotacao
                                        and os.id_fornecedor = co.id_fornecedor
                                    left join ocs_sintese_produtos osp on
                                        osp.id_ordem_compra = os.id
                            where
                             {$where}
                            group by
                                u.id,
                                u.nome,
                                c.estado
                        ) q
                    group by
                        q.id,
                        q.usuario,
                        q.estado
                ) grupo
                    INNER JOIN
                (
                    SELECT
                        q.id,
                        q.usuario,
                        q.estado,
                        sum(q.total_vendido) as total_vendido
                    from
                        (
                            select
                                u.id,
                                u.nome usuario,
                                c.estado,
                                ROUND((osp.Vl_Preco_Produto * osp.Qt_Produto), 1) as total_vendido
                            from
                                pharmanexo.usuarios u
                                    left join pharmanexo.cotacoes_produtos co on
                                        u.id = co.id_usuario
                                    left join compradores c on
                                        c.id = co.id_cliente
                                    left join ocs_sintese os on
                                            co.cd_cotacao = os.cd_cotacao
                                        and os.id_fornecedor = co.id_fornecedor
                                    left join ocs_sintese_produtos osp on
                                        osp.id_ordem_compra = os.id
                            where
                                  {$where}
                            group by
                                u.id,
                                u.nome,
                                c.estado,
                                total_vendido
                        ) q
                    group by
                        q.id,
                        q.usuario,
                        q.estado
                ) soma
                on grupo.id = soma.id and grupo.usuario = soma.usuario and grupo.estado = soma.estado

        ";

        $consulta = $this->db->query($query)->result_array();


        $usuarios = [];

        if (isset($data['usuario'])) {
            return $consulta;
        }

        foreach ($consulta as $consulta) {

            $qtd = (isset($usuarios[$consulta['id']]['qtd_cotacoes'])) ? $usuarios[$consulta['id']]['qtd_cotacoes'] : 0;
            $qtdOfertado = (isset($usuarios[$consulta['id']]['qtd_itens_ofertados'])) ? $usuarios[$consulta['id']]['qtd_itens_ofertados'] : 0;
            $qtdConv = (isset($usuarios[$consulta['id']]['qtd_pedidos_convertidos'])) ? $usuarios[$consulta['id']]['qtd_pedidos_convertidos'] : 0;
            $qtdItensConv = (isset($usuarios[$consulta['id']]['qtd_itens_convertidos'])) ? $usuarios[$consulta['id']]['qtd_itens_convertidos'] : 0;
            $total = (isset($usuarios[$consulta['id']]['total_vendido'])) ? $usuarios[$consulta['id']]['total_vendido'] : 0;

            $usuarios[$consulta['id']]['nome'] = $consulta['usuario'];
            $usuarios[$consulta['id']]['qtd_cotacoes'] = $consulta['qtd_cotacoes'] + $qtd;
            $usuarios[$consulta['id']]['qtd_itens_ofertados'] = $consulta['qtd_itens_ofertados'] + $qtdOfertado;
            $usuarios[$consulta['id']]['qtd_pedidos_convertidos'] = $consulta['qtd_pedidos_convertidos'] + $qtdConv;
            $usuarios[$consulta['id']]['qtd_itens_convertidos'] = $consulta['qtd_itens_convertidos'] + $qtdItensConv;
            $usuarios[$consulta['id']]['total_vendido'] = $consulta['total_vendido'] + $total;
            $usuarios[$consulta['id']]['estados'][] = $consulta['estado'];
        }

        return $usuarios;
    }

    public function getAnaliticoUsuariosCotacoes($data)
    {
        $idForn = $this->session->id_fornecedor;
        $forns = explode(',', ONCOPROD);

        if (in_array($idForn, $forns)) {
            $forns = ONCOPROD;
            $where = "co.id_fornecedor in ({$forns}) AND ";
        } else {
            $where = "co.id_fornecedor = {$idForn} AND ";
        }


        if (isset($data['dataini']) && isset($data['datafim'])) {
            $where .= "cast(co.data_criacao as date) >= '{$data['dataini']}' AND ";
            $where .= "cast(co.data_criacao as date) <= '{$data['datafim']}' AND ";
        } else {
            $where .= "year(co.data_criacao) = year(now()) AND MONTH(co.data_criacao) =  MONTH(now()) AND ";
        }

        if (isset($data['usuario'])) {
            $where .= "u.id = {$data['usuario']} AND ";
        }

        if (isset($data['uf'])) {
            $where .= "c.estado = '{$data['uf']}' AND ";
        }

        $where = rtrim($where, 'AND ');


        $query = "
                 select co.cd_cotacao,
                        u.id,
                        u.nome                                                            usuario,
                        c.razao_social,
                        c.estado                                                          estado_comprador,
                        co.id_fornecedor,
                        co.data_criacao,
                        co.integrador,
                        CASE
                            WHEN os.Cd_Ordem_Compra IS NOT NULL
                                THEN 'SIM'
                            ELSE 'NÃO'
                            END                                                        AS CONVERTIDO,
                        format(osp.Vl_Preco_Produto * osp.Qt_Produto, 4, 'de_DE') as total_vendido
                 from pharmanexo.usuarios u
                          join pharmanexo.cotacoes_produtos co
                               on u.id = co.id_usuario
                          left join ocs_sintese os
                                    on co.cd_cotacao = os.cd_cotacao and os.id_fornecedor = co.id_fornecedor
                          left join ocs_sintese_produtos osp
                                    on osp.id_ordem_compra = os.id
                          left join compradores c on c.id = co.id_cliente

                 where {$where}
                 group by co.cd_cotacao, os.Cd_Ordem_Compra, osp.id
                 ";


        $consulta = $this->db->query($query)->result_array();
        $usuarios = [];

        if (isset($data['usuario'])) {
            return $consulta;
        }

        /*foreach ($consulta as $consulta) {

            $qtd = (isset($usuarios[$consulta['id']]['qtd_cotacoes'])) ? $usuarios[$consulta['id']]['qtd_cotacoes'] : 0;
            $qtdOfertado = (isset($usuarios[$consulta['id']]['qtd_itens_ofertados'])) ? $usuarios[$consulta['id']]['qtd_itens_ofertados'] : 0;
            $qtdConv = (isset($usuarios[$consulta['id']]['qtd_pedidos_convertidos'])) ? $usuarios[$consulta['id']]['qtd_pedidos_convertidos'] : 0;
            $qtdItensConv = (isset($usuarios[$consulta['id']]['qts_itens_covertidos'])) ? $usuarios[$consulta['id']]['qts_itens_covertidos'] : 0;
            $total = (isset($usuarios[$consulta['id']]['total_vendido'])) ? $usuarios[$consulta['id']]['total_vendido'] : 0;

            $usuarios[$consulta['id']]['nome'] = $consulta['usuario'];
            $usuarios[$consulta['id']]['qtd_cotacoes'] = $consulta['qtd_cotacoes'] + $qtd;
            $usuarios[$consulta['id']]['qtd_itens_ofertados'] = $consulta['qtd_itens_ofertados'] + $qtdOfertado;
            $usuarios[$consulta['id']]['qtd_pedidos_convertidos'] = $consulta['qtd_pedidos_convertidos'] + $qtdConv;
            $usuarios[$consulta['id']]['qts_itens_covertidos'] = $consulta['qts_itens_covertidos'] + $qtdItensConv;
            $usuarios[$consulta['id']]['total_vendido'] = dbNumberFormat($consulta['total_vendido']) + $total;
            $usuarios[$consulta['id']]['estados'][] = $consulta['estado_comprador'];
        }*/

        return $usuarios;
    }

    public function getAnalUserCots()
    {

        $query = "
                    SELECT grupo.id,
                   grupo.usuario,
                   grupo.estado,
                   grupo.qtd_cotacoes,
                   grupo.qtd_itens_ofertados,
                   grupo.qtd_pedidos_convertidos,
                   qtd_itens_convertidos,
                   IFNULL(soma.total_vendido, 0) as total_vendido
            from
                (
                    SELECT
                        q.id,
                        q.usuario,
                        q.estado,
                        max(q.qtd_cotacoes) qtd_cotacoes,
                        max(q.qtd_itens_ofertados) qtd_itens_ofertados,
                        max(q.qtd_pedidos_convertidos) qtd_pedidos_convertidos,
                        max(q.qtd_itens_convertidos) qtd_itens_convertidos
                        -- sum(q.total_vendido)
                    from
                        (
                            select
                                u.id,
                                u.nome usuario,
                                c.estado,
                                count(distinct co.cd_cotacao) qtd_cotacoes,
                                count(co.cd_cotacao) qtd_itens_ofertados,
                                count(distinct os.Cd_Ordem_Compra) qtd_pedidos_convertidos,
                                count(distinct osp.id) qtd_itens_convertidos
                                -- ROUND((osp.Vl_Preco_Produto * osp.Qt_Produto), 1) as total_vendido
                            from
                                pharmanexo.usuarios u
                                    left join pharmanexo.cotacoes_produtos co on
                                        u.id = co.id_usuario
                                    left join compradores c on
                                        c.id = co.id_cliente
                                    left join ocs_sintese os on
                                            co.cd_cotacao = os.cd_cotacao
                                        and os.id_fornecedor = co.id_fornecedor
                                    left join ocs_sintese_produtos osp on
                                        osp.id_ordem_compra = os.id
                            where
                                    co.id_fornecedor = 20
                              -- and u.id = 420
                              AND cast(co.data_criacao as date) >= '2022-07-01'
                              AND cast(co.data_criacao as date) <= '2022-07-31'
                            group by
                                u.id,
                                u.nome,
                                c.estado
                        ) q
                    group by
                        q.id,
                        q.usuario
                ) grupo
                    INNER JOIN
                (
                    SELECT
                        q.id,
                        q.usuario,
                        q.estado,
                        sum(q.total_vendido) as total_vendido
                    from
                        (
                            select
                                u.id,
                                u.nome usuario,
                                c.estado,
                                -- count(distinct co.cd_cotacao) qtd_cotacoes,
                                --  count(co.cd_cotacao) qtd_itens_ofertados,
                                --  count(distinct os.Cd_Ordem_Compra) qtd_pedidos_convertidos,
                                --   count(distinct osp.id) qtd_itens_convertidos,
                                ROUND((osp.Vl_Preco_Produto * osp.Qt_Produto), 1) as total_vendido
                            from
                                pharmanexo.usuarios u
                                    left join pharmanexo.cotacoes_produtos co on
                                        u.id = co.id_usuario
                                    left join compradores c on
                                        c.id = co.id_cliente
                                    left join ocs_sintese os on
                                            co.cd_cotacao = os.cd_cotacao
                                        and os.id_fornecedor = co.id_fornecedor
                                    left join ocs_sintese_produtos osp on
                                        osp.id_ordem_compra = os.id
                            where
                                    co.id_fornecedor = 20
                              -- and u.id = 420
                              AND cast(co.data_criacao as date) >= '2022-07-01'
                              AND cast(co.data_criacao as date) <= '2022-07-31'
                            group by
                                u.id,
                                u.nome,
                                c.estado,
                                total_vendido
                        ) q
                    group by
                        q.id,
                        q.usuario
                ) soma
                on grupo.id = soma.id and grupo.usuario = soma.usuario

        ";


        /* $cotacoesUsuarios = [];
         foreach ($cotacoes as $cotacao) {
             $cotacoesUsuarios[$cotacao['id_usuario']][] = $cotacao;
         }

         foreach ($cotacoesUsuarios as $k => $cots) {
            foreach ($cots as $j => $cot){
                $oc = $this->db
                    ->select('id')
                    ->where('id_fornecedor', $this->session->id_fornecedor)
                    ->where('cd_cotacao', $cot['cd_cotacao'])
                    ->get('ocs_sintese')
                    ->result_array();
                if (!empty($oc)){
                    $cotacoesUsuarios[$k][$j]['oc'] = true;
                }

                var_dump($cotacoesUsuarios);
                exit();

            }*/

    }

}
