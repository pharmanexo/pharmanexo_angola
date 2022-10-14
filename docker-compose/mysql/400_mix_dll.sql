use mix;
SET FOREIGN_KEY_CHECKS=0;

create table if not exists chule_marca
(
	codMarca int null,
	marca varchar(50) null,
	origem varchar(50) null
);

create table if not exists chule_modelo
(
	modelo varchar(50) null,
	cor varchar(50) null,
	ano int null,
	valor int null,
	codMarca int null
);

create table if not exists cotacoes
(
	id_cotacao int null,
	cd_cotacao varchar(50) null,
	cnpj_comprador varchar(50) null,
	id_cliente int null,
	uf_comprador varchar(2) null,
	id_uf_comprador int null,
	qtd_total_itens int null,
	data_criacao datetime default CURRENT_TIMESTAMP not null,
	data_atualizacao datetime default CURRENT_TIMESTAMP null
)
charset=latin1;

create table if not exists cotacoes_produtos
(
	id_cotacao int null,
	cd_cotacao varchar(50) null,
	cd_produto_sintese int null comment 'id_produto_sintese',
	qt_produto_total_solicitado int null,
	cnpj_fornecedor_oferta varchar(50) null,
	id_fornecedor_oferta int null,
	nm_fornecedor_oferta varchar(100) null,
	uf_fornecedor_oferta varchar(2) null,
	cd_produto_marca int null comment 'id_sintese',
	ds_produto_marca varchar(50) null,
	complemento_produto_marca varchar(50) null,
	ds_marca varchar(50) null,
	qt_embalagem int null,
	qt_atribuida_comprador int null,
	data_solicitada_comprador date null,
	vl_preco_produto decimal(12,4) null,
	dt_criacao datetime default CURRENT_TIMESTAMP not null,
	dt_atualizacao datetime default CURRENT_TIMESTAMP null
)
charset=latin1;

create table if not exists fornecedores_mix_provisorio
(
	id int auto_increment
		primary key,
	id_cliente int null,
	id_estado int null,
	prioridade int null,
	desconto_mix decimal(6,2) null,
	id_fornecedor int null
)
charset=latin1;

create table if not exists log_env_sintese
(
	data_criacao datetime default CURRENT_TIMESTAMP null,
	cd_cotacao varchar(50) null,
	json json null
)
charset=latin1;

create table if not exists log_espelho
(
	data_criacao datetime default CURRENT_TIMESTAMP null,
	cd_cotacao varchar(50) null,
	json json null
)
charset=latin1;

create table if not exists logs
(
	data_criacao datetime default CURRENT_TIMESTAMP null,
	cd_cotacao varchar(50) null,
	json json null
)
charset=latin1;

create table if not exists mytable
(
	code varchar(255) null,
	active varchar(255) null,
	total varchar(255) null
);

create table if not exists produtos_preco_mix
(
	codigo int null,
	id_fornecedor int null,
	id_cliente int null,
	id_estado int null,
	preco_mix decimal(12,4) null,
	preco_base decimal(12,4) null,
	preco_fixo int default 0 null,
	margem_minima decimal(6,2) default 0.00 null,
	margem_maxima decimal(6,2) default 0.00 null,
	data_criacao datetime default CURRENT_TIMESTAMP null,
	data_atualizacao datetime default CURRENT_TIMESTAMP null on update CURRENT_TIMESTAMP
)
charset=latin1;

create index produtos_preco_mix_id_cliente_id_fornecedor_codigo_index
	on produtos_preco_mix (id_cliente, id_fornecedor, codigo);

create table if not exists user_audit_trails
(
	id int auto_increment
		primary key,
	user_id int null,
	event enum('insert', 'update', 'delete') null,
	table_name varchar(120) null,
	old_values text null,
	new_values text null,
	url varchar(255) null,
	name varchar(120) null,
	ip_address varchar(45) null,
	user_agent varchar(255) null,
	created_at timestamp null
);

