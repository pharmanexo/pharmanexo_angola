<?php
class M_encontrados_sintese extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProdutosByFornecedor($cd_cotacao, $fornecedor, $estado)
    {

        $query = "SELECT 
            cot.cd_cotacao,
            cot.dt_inicio_cotacao,
            cot_prods.ds_produto_comprador,
            forn_sint.cd_produto codigo,
            pc.id,
            CONCAT(pc.nome_comercial, ' - ', (case when pc.apresentacao is null then pc.descricao else pc.apresentacao end)) produto_descricao,
            pc.marca,
            IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
            pc.id_marca,
            marc_sint.id_produto,
            cot_prods.id_fornecedor,
            (SELECT (SUM(pl.estoque) * IF(pc2.quantidade_unidade is null, 1, pc2.quantidade_unidade))
                FROM pharmanexo.produtos_lote pl
                JOIN pharmanexo.produtos_catalogo pc2
                    on pc2.codigo = pl.codigo
                    and pc2.id_fornecedor = pl.id_fornecedor
                    and pc2.ativo = 1
                    and pc2.bloqueado = 0
                where pl.codigo = forn_sint.cd_produto
                    and pl.id_fornecedor = forn_sint.id_fornecedor) estoque,
            (SELECT pp.preco_unitario FROM pharmanexo.produtos_preco pp
                where pp.id_estado {$estado}
                and pp.id_fornecedor = forn_sint.id_fornecedor
                and pp.codigo = forn_sint.cd_produto
                and pp.data_criacao = (CASE
                    WHEN ISNULL(pp.id_estado) then
                        (select max(pp2.data_criacao)
                            from pharmanexo.produtos_preco pp2
                            where pp2.id_fornecedor = pp.id_fornecedor
                                and pp2.codigo = pp.codigo
                                and pp2.id_estado is null)
                    ELSE
                        (select max(pp2.data_criacao)
                            from pharmanexo.produtos_preco pp2
                              where pp2.id_fornecedor = pp.id_fornecedor
                                and pp2.codigo = pp.codigo
                                and pp2.id_estado = pp.id_estado) END) LIMIT 1) preco_unidade

            FROM cotacoes_sintese.cotacoes cot
            JOIN cotacoes_sintese.cotacoes_produtos cot_prods
                on cot_prods.id_fornecedor = cot.id_fornecedor
                and cot_prods.cd_cotacao = cot.cd_cotacao
            LEFT join pharmanexo.produtos_marca_sintese marc_sint
                on marc_sint.id_produto = cot_prods.id_produto_sintese
            LEFT JOIN pharmanexo.produtos_fornecedores_sintese forn_sint
                on forn_sint.id_fornecedor = cot.id_fornecedor
                and forn_sint.id_sintese = marc_sint.id_sintese
            JOIN pharmanexo.produtos_catalogo pc
                on pc.codigo = forn_sint.cd_produto
                and pc.id_fornecedor = forn_sint.id_fornecedor
                and pc.ativo = 1
                and pc.bloqueado = 0

            WHERE cot.cd_cotacao = '{$cd_cotacao}'
                and cot_prods.id_fornecedor {$fornecedor}  
            GROUP BY cot.cd_cotacao,
                cot_prods.id_produto_sintese,
                cot_prods.id_fornecedor,
                forn_sint.cd_produto,
                marc_sint.id_produto,
                CONCAT(pc.nome_comercial, ' - ', (case when pc.apresentacao is null then pc.descricao else pc.apresentacao end)),
                pc.marca,
                pc.id_marca,
                pc.quantidade_unidade

            having forn_sint.cd_produto is not null
            order by cot_prods.ds_produto_comprador ASC, cot_prods.id_fornecedor
        ";

        return $this->db->query($query)->result_array();
    }


    public function getProdutos_depara($cd_cotacao, $fornecedor)
    {

        $query = "SELECT 
            cot.cd_cotacao,
            cot.dt_inicio_cotacao,
            cot_prods.ds_produto_comprador,
            forn_sint.cd_produto codigo,
            pc.id,
            CONCAT(pc.nome_comercial, ' - ', (case when pc.apresentacao is null then pc.descricao else pc.apresentacao end)) produto_descricao,
            pc.marca,
            IF(pc.quantidade_unidade is null, 1, pc.quantidade_unidade) quantidade_unidade,
            pc.id_marca,
            marc_sint.id_produto,
            cot_prods.id_fornecedor
            FROM cotacoes_sintese.cotacoes cot
            JOIN cotacoes_sintese.cotacoes_produtos cot_prods
                on cot_prods.id_fornecedor = cot.id_fornecedor
                and cot_prods.cd_cotacao = cot.cd_cotacao
            LEFT join pharmanexo.produtos_marca_sintese marc_sint
                on marc_sint.id_produto = cot_prods.id_produto_sintese
            LEFT JOIN pharmanexo.produtos_fornecedores_sintese forn_sint
                on forn_sint.id_fornecedor = cot.id_fornecedor
                and forn_sint.id_sintese = marc_sint.id_sintese
            JOIN pharmanexo.produtos_catalogo pc
                on pc.codigo = forn_sint.cd_produto
                and pc.id_fornecedor = forn_sint.id_fornecedor
                and pc.ativo = 1
                and pc.bloqueado = 0

            WHERE cot.cd_cotacao = '{$cd_cotacao}'
                and cot_prods.id_fornecedor {$fornecedor}  
            GROUP BY cot.cd_cotacao,
                cot_prods.id_produto_sintese,
                forn_sint.cd_produto,
                marc_sint.id_produto,
                CONCAT(pc.nome_comercial, ' - ', (case when pc.apresentacao is null then pc.descricao else pc.apresentacao end)),
                pc.marca,
                pc.id_marca,
                pc.quantidade_unidade

            having forn_sint.cd_produto is not null
            order by cot_prods.ds_produto_comprador ASC, cot_prods.id_fornecedor
        ";

        return $this->db->query($query)->result_array();
    }
}
