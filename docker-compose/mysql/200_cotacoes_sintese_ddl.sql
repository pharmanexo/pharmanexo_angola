USE cotacoes_sintese;
SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS cotacoes
(
    id int auto_increment
    primary key,
    tp_movimento char not null,
    cd_cotacao varchar(20) null,
    cd_comprador varchar(20) null,
    id_cliente int null,
    dt_inicio_cotacao varchar(20) null,
    dt_fim_cotacao varchar(20) null,
    dt_validade_preco varchar(10) null,
    dt_cadastro varchar(20) null,
    ds_entrega varchar(50) null,
    cd_condicao_pagamento varchar(50) null,
    ds_cotacao varchar(50) null,
    nm_usuario varchar(50) null,
    ds_observacao text null,
    id_fornecedor int null,
    data_criacao datetime default CURRENT_TIMESTAMP not null,
    uf_cotacao varchar(10) null,
    ds_filiais varchar(250) null,
    oferta int default 0 not null,
    visitado int default 0 null,
    oculto int default 0 not null,
    data_atualizacao datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP,
    revisao int default 0 null,
    total_itens int default 0 null,
    motivo_recusa int null,
    usuario_recusa int null,
    data_recusa datetime null,
    obs_recusa text null,
    encerrada tinyint default 0 null
) charset=latin1;

CREATE TABLE IF NOT EXISTS cotacoes_produtos
(
    id int auto_increment
    primary key,
    id_produto_sintese int null,
    id_fornecedor int null,
    cd_produto_comprador varchar(20) null,
    ds_produto_comprador varchar(250) null,
    ds_unidade_compra varchar(50) null,
    ds_complementar text null,
    qt_produto_total varchar(10) null,
    sn_item_contrato int null,
    sn_permite_exibir int null,
    cd_cotacao varchar(20) null,
    data_criacao datetime default CURRENT_TIMESTAMP not null,
    data_atualizacao datetime default CURRENT_TIMESTAMP not null
) charset=latin1;