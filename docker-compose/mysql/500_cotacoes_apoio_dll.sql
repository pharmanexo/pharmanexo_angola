use cotacoes_apoio;
SET FOREIGN_KEY_CHECKS=0;

create table if not exists catalogo
(
	id int auto_increment
		primary key,
	id_cliente int not null,
	codigo varchar(50) not null,
	descricao varchar(200) null,
	quantidade_unidade int default 1 null,
	ativo int default 1 null,
	bloqueado int default 0 null,
	id_unidade int null,
	unidade varchar(50) null,
	id_categoria int null,
	dt_criacao datetime default CURRENT_TIMESTAMP null,
	ocultar int default 0 null comment 'produto que nao possui item correspondente da sintese',
	process int default 0 null,
	not_found int default 0 null
);

create table if not exists cotacoes
(
	id int auto_increment
		primary key,
	ds_cotacao varchar(50) null,
	dt_inicio_cotacao varchar(50) null,
	dt_fim_cotacao varchar(50) null,
	nome_hospital varchar(100) null,
	id_cliente int null,
	cd_comprador varchar(50) null,
	uf_cotacao varchar(5) null,
	cidade varchar(50) null,
	endereco varchar(100) null,
	contato varchar(50) null,
	id_forma_pagamento varchar(10) null,
	observacao text null,
	cd_cotacao int null,
	forma_pagamento varchar(50) null,
	dt_criacao datetime default CURRENT_TIMESTAMP null,
	visitado int default 0 not null,
	id_fornecedor int not null,
	oferta int default 0 null,
	oculto int default 0 null,
	revisao int default 0 null,
	total_itens int default 0 not null,
	catalogo int default 0 null,
	motivo_recusa int null,
	usuario_recusa int null,
	data_recusa datetime null,
	obs_recusa text null,
	encerrada tinyint default 0 null
);


create table if not exists cotacoes_produtos
(
	id int auto_increment
		primary key,
	id_cotacao int null,
	sequencia varchar(5) null,
	id_artigo varchar(50) null,
	cd_produto_comprador varchar(50) null,
	ds_produto_comprador varchar(200) null,
	qt_produto_total varchar(50) null,
	ds_unidade_compra varchar(50) null,
	id_unidade varchar(10) null,
	marca_favorita varchar(100) null,
	id_categoria varchar(100) null,
	dt_criacao datetime default CURRENT_TIMESTAMP null
);

create table if not exists logs_cotacao
(
	data_criacao datetime default CURRENT_TIMESTAMP null,
	cd_cotacao varchar(50) null,
	json json null
)
charset=latin1;

create table if not exists produtos_marcas
(
	id int auto_increment
		primary key,
	codigo_produto int null,
	codigo_marca int null,
	marca varchar(100) null,
	dt_criacao datetime default CURRENT_TIMESTAMP null,
	id_cotacao int default 0 null,
	id_cliente int default 0 null
);


