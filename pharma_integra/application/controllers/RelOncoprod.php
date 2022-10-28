<?php

class RelOncoprod extends MY_Controller
{

    private $array = [];

    public function __construct()
    {
        parent::__construct();

    }

    public function query()
    {

        return $this->db->query("SELECT x.cnpj_comprador,
       (SELECT comp.razao_social
        FROM pharmanexo.compradores comp
        where comp.cnpj = x.cnpj_comprador LIMIT 1)                       razao_social,
       x.cd_cotacao,
       x.dt_inicio_cotacao,
       UPPER(x.descricao_produto) descricao_produto,
       x.id_sintese,
       x.codigos codigos_kraft,
       x.qtd_solicitada

FROM (SELECT cot.cd_comprador,
             INSERT(INSERT(INSERT(INSERT(cot.cd_comprador, 13, 0, '-'), 9, 0, '/'), 6, 0, '.'), 3, 0,
                    '.')                                    cnpj_comprador,
             cot.cd_cotacao,
             DATE_FORMAT(cot.dt_inicio_cotacao, '%d/%m/%Y') dt_inicio_cotacao,
             pms.id_sintese,
             pms.descricao descricao_produto,
             ccp.qt_produto_total qtd_solicitada,

             GROUP_CONCAT(DISTINCT pfs.cd_produto)          codigos


      FROM cotacoes_sintese.cotacoes_produtos ccp

               JOIN cotacoes_sintese.cotacoes cot
                    on cot.id_fornecedor = ccp.id_fornecedor
                        and cot.cd_cotacao = ccp.cd_cotacao

               JOIN pharmanexo.produtos_marca_sintese pms
                    on pms.id_produto = ccp.id_produto_sintese

               JOIN pharmanexo.produtos_fornecedores_sintese pfs
                    on pfs.id_sintese = pms.id_sintese
                        and pfs.id_fornecedor = cot.id_fornecedor


      where DATE_FORMAT(cot.dt_inicio_cotacao, '%Y-%m-%d') BETWEEN '2020-01-01' AND '2020-01-31'
        AND cot.id_fornecedor IN (12, 111, 112, 115, 120, 123)

      GROUP BY cot.cd_comprador,
               cot.cd_cotacao,
               DATE_FORMAT(cot.dt_inicio_cotacao, '%d/%m/%Y'),
               pms.id_sintese,
               pms.descricao,
               ccp.qt_produto_total

      order by DATE_FORMAT(cot.dt_inicio_cotacao, '%Y-%m-%d') ASC, cot.cd_cotacao, cot.cd_comprador) x
")->result_array();


    }

    public function query2($params)
    {

        return $this->db->where_in('id_fornecedor', [12, 111, 112, 115, 120, 123])
            ->where('cd_cotacao', $params['cd_cotacao'])
            ->where('id_pfv', $params['codigo'])
            ->get('pharmanexo.cotacoes_produtos')
            ->result_array();
    }

    protected function index_get()
    {

        $prodsCotacao = $this->query();

        foreach ($prodsCotacao as $prod) {

            unset($params);

            $codigos = explode(",", $prod['codigos_kraft']);

            foreach ($codigos as $i => $codigo) {

                $params = [
                    "codigo" => intval($codigo),
                    "cd_cotacao" => $prod['cd_cotacao']
                ];

                $verifyCot = $this->query2($params);

                if (!empty($verifyCot)) {

                    $this->array[] = [
                        "cnpj" => $prod['cnpj_comprador'],
                        "comprador" => $prod['razao_social'],
                        "cotacao" => $prod['cd_cotacao'],
                        "data" => $prod['dt_inicio_cotacao'],
                        "produto" => $prod['descricao_produto'],
                        "id_sintese" => $prod['id_sintese'],
                        "codigo" => $prod['codigos_kraft'],
                        "qtd" => $prod['qtd_solicitada'],
                        "respondido" => 'S'
                    ];

                    continue 2;

                } else if (empty($verifyCot) && isset($codigos[$i + 1])) {

                    continue;

                } else if (empty($verifyCot) && !isset($codigos[$i + 1])) {

                    $this->array[] = [
                        "cnpj" => $prod['cnpj_comprador'],
                        "comprador" => $prod['razao_social'],
                        "cotacao" => $prod['cd_cotacao'],
                        "data" => $prod['dt_inicio_cotacao'],
                        "produto" => $prod['descricao_produto'],
                        "id_sintese" => $prod['id_sintese'],
                        "codigo" => $prod['codigos_kraft'],
                        "qtd" => $prod['qtd_solicitada'],
                        "respondido" => 'N'
                    ];

                    continue 2;

                } else if (!empty($verifyCot)) {

                    $this->array[] = [
                        "cnpj" => $prod['cnpj_comprador'],
                        "comprador" => $prod['razao_social'],
                        "cotacao" => $prod['cd_cotacao'],
                        "data" => $prod['dt_inicio_cotacao'],
                        "produto" => $prod['descricao_produto'],
                        "id_sintese" => $prod['id_sintese'],
                        "codigo" => $prod['codigos_kraft'],
                        "qtd" => $prod['qtd_solicitada'],
                        "respondido" => 'S'
                    ];

                    continue 2;
                }
            }
        }

        $this->db->insert_batch('pharmanexo.relatorio_produto_cotados', $this->array);

    }
}
