-- Temporary view structure for view `VW_REL_VL_TOTAL_COTADO`
--

DROP TABLE IF EXISTS `VW_REL_VL_TOTAL_COTADO`;
/*!50001 DROP VIEW IF EXISTS `VW_REL_VL_TOTAL_COTADO`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `VW_REL_VL_TOTAL_COTADO` AS SELECT 
 1 AS `vl_total`,
 1 AS `qt_total`,
 1 AS `codigo`,
 1 AS `id_fornecedor`,
 1 AS `competencia`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_ocs_sintese`
--

DROP TABLE IF EXISTS `view_ocs_sintese`;
/*!50001 DROP VIEW IF EXISTS `view_ocs_sintese`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_ocs_sintese` AS SELECT 
 1 AS `id`,
 1 AS `Dt_Gravacao`,
 1 AS `Tp_Movimento`,
 1 AS `Cd_Fornecedor`,
 1 AS `Cd_Condicao_Pagamento`,
 1 AS `Cd_Cotacao`,
 1 AS `Cd_Ordem_Compra`,
 1 AS `Dt_Ordem_Compra`,
 1 AS `Hr_Ordem_Compra`,
 1 AS `id_comprador`,
 1 AS `Tp_Situacao`,
 1 AS `Nm_Aprovador`,
 1 AS `Dt_Previsao_Entrega`,
 1 AS `Cd_Comprador`,
 1 AS `Nm_Logradouro`,
 1 AS `Ds_Complemento_Logradouro`,
 1 AS `Nm_Bairro`,
 1 AS `Nm_Cidade`,
 1 AS `Id_Unidade_Federativa`,
 1 AS `Nr_Cep`,
 1 AS `Ds_Observacao`,
 1 AS `Telefones_Ordem_Compra`,
 1 AS `Tp_Frete`,
 1 AS `pendente`,
 1 AS `id_fornecedor`,
 1 AS `Dt_Resgate`,
 1 AS `Status_OrdemCompra`,
 1 AS `nota`,
 1 AS `chave_nf`,
 1 AS `transaction_id`,
 1 AS `id_usuario_resgate`,
 1 AS `data_resgate`,
 1 AS `integrador`,
 1 AS `endereco_entrega`,
 1 AS `motivo_cancelamento`,
 1 AS `forma_pagamento`,
 1 AS `termos`,
 1 AS `sequencia`,
 1 AS `prioridade`,
 1 AS `Tp_Logradouro`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_catalogo_sem_sintese`
--

DROP TABLE IF EXISTS `vw_catalogo_sem_sintese`;
/*!50001 DROP VIEW IF EXISTS `vw_catalogo_sem_sintese`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_catalogo_sem_sintese` AS SELECT 
 1 AS `id`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `preco_unidade`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `ativo`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`,
 1 AS `preco`,
 1 AS `aprovado`,
 1 AS `bloqueado`,
 1 AS `rms`,
 1 AS `ean`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_clientes_fornecedores`
--

DROP TABLE IF EXISTS `vw_clientes_fornecedores`;
/*!50001 DROP VIEW IF EXISTS `vw_clientes_fornecedores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_clientes_fornecedores` AS SELECT 
 1 AS `id`,
 1 AS `cnpj`,
 1 AS `nome`,
 1 AS `razao_social`,
 1 AS `alvara`,
 1 AS `responsabilidade_tecnica`,
 1 AS `validade_alvara`,
 1 AS `cartao_cnpj`,
 1 AS `id_fornecedor`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_compradores_bionexo`
--

DROP TABLE IF EXISTS `vw_compradores_bionexo`;
/*!50001 DROP VIEW IF EXISTS `vw_compradores_bionexo`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_compradores_bionexo` AS SELECT 
 1 AS `id_cliente`,
 1 AS `cnpj`,
 1 AS `razao_social`,
 1 AS `id_fornecedor`,
 1 AS `fornecedor`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_cotacoes`
--

DROP TABLE IF EXISTS `vw_cotacoes`;
/*!50001 DROP VIEW IF EXISTS `vw_cotacoes`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_cotacoes` AS SELECT 
 1 AS `id`,
 1 AS `id_cotacao`,
 1 AS `uf_comprador`,
 1 AS `cnpj_comprador`,
 1 AS `total_itens`,
 1 AS `valor_total`,
 1 AS `data_cotacao`,
 1 AS `id_fornecedor`,
 1 AS `codigo_oc`,
 1 AS `cnpj`,
 1 AS `cd_cotacao`,
 1 AS `submetido`,
 1 AS `nivel`,
 1 AS `razao_social`,
 1 AS `nome_fantasia`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_cotacoes_agrupadas`
--

DROP TABLE IF EXISTS `vw_cotacoes_agrupadas`;
/*!50001 DROP VIEW IF EXISTS `vw_cotacoes_agrupadas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_cotacoes_agrupadas` AS SELECT 
 1 AS `integrador`,
 1 AS `id`,
 1 AS `id_fornecedor`,
 1 AS `cd_cotacao`,
 1 AS `cd_comprador`,
 1 AS `id_cliente`,
 1 AS `dt_inicio_cotacao`,
 1 AS `dt_fim_cotacao`,
 1 AS `ds_cotacao`,
 1 AS `uf_cotacao`,
 1 AS `oferta`,
 1 AS `oculto`,
 1 AS `total_itens`,
 1 AS `revisada`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_cotacoes_bkp`
--

DROP TABLE IF EXISTS `vw_cotacoes_bkp`;
/*!50001 DROP VIEW IF EXISTS `vw_cotacoes_bkp`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_cotacoes_bkp` AS SELECT 
 1 AS `id`,
 1 AS `id_cotacao`,
 1 AS `uf_comprador`,
 1 AS `cnpj_comprador`,
 1 AS `total_itens`,
 1 AS `valor_total`,
 1 AS `data_cotacao`,
 1 AS `id_fornecedor`,
 1 AS `cd_cotacao`,
 1 AS `submetido`,
 1 AS `nivel`,
 1 AS `cnpj`,
 1 AS `razao_social`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_cotacoes_integrador`
--

DROP TABLE IF EXISTS `vw_cotacoes_integrador`;
/*!50001 DROP VIEW IF EXISTS `vw_cotacoes_integrador`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_cotacoes_integrador` AS SELECT 
 1 AS `integrador`,
 1 AS `id`,
 1 AS `id_fornecedor`,
 1 AS `cd_cotacao`,
 1 AS `cd_comprador`,
 1 AS `id_cliente`,
 1 AS `dt_inicio_cotacao`,
 1 AS `dt_fim_cotacao`,
 1 AS `ds_cotacao`,
 1 AS `uf_cotacao`,
 1 AS `oferta`,
 1 AS `oculto`,
 1 AS `total_itens`,
 1 AS `revisada`,
 1 AS `motivo_recusa`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_cotacoes_recusas`
--

DROP TABLE IF EXISTS `vw_cotacoes_recusas`;
/*!50001 DROP VIEW IF EXISTS `vw_cotacoes_recusas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_cotacoes_recusas` AS SELECT 
 1 AS `cd_cotacao`,
 1 AS `id_fornecedor`,
 1 AS `motivo_recusa`,
 1 AS `usuario_recusa`,
 1 AS `data_recusa`,
 1 AS `obs_recusa`,
 1 AS `integrador`,
 1 AS `usuario`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_encontrados_sintese`
--

DROP TABLE IF EXISTS `vw_encontrados_sintese`;
/*!50001 DROP VIEW IF EXISTS `vw_encontrados_sintese`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_encontrados_sintese` AS SELECT 
 1 AS `cd_cotacao`,
 1 AS `ds_produto_comprador`,
 1 AS `cd_produto`,
 1 AS `id_produto_sintese`,
 1 AS `id`,
 1 AS `produto_descricao`,
 1 AS `marca`,
 1 AS `quantidade_unidade`,
 1 AS `id_marca`,
 1 AS `id_produto`,
 1 AS `id_fornecedor`,
 1 AS `estoque`,
 1 AS `preco`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_estoque_produtos_fornecedores`
--

DROP TABLE IF EXISTS `vw_estoque_produtos_fornecedores`;
/*!50001 DROP VIEW IF EXISTS `vw_estoque_produtos_fornecedores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_estoque_produtos_fornecedores` AS SELECT 
 1 AS `codigo`,
 1 AS `id_fornecedor`,
 1 AS `nome_comercial`,
 1 AS `descricao`,
 1 AS `lote`,
 1 AS `validade`,
 1 AS `marca`,
 1 AS `preco_unidade`,
 1 AS `estoque_unitario`,
 1 AS `id_estado`,
 1 AS `estado`,
 1 AS `estoque`,
 1 AS `quantidade_unidade`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_formas_pagamento_fornecedores`
--

DROP TABLE IF EXISTS `vw_formas_pagamento_fornecedores`;
/*!50001 DROP VIEW IF EXISTS `vw_formas_pagamento_fornecedores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_formas_pagamento_fornecedores` AS SELECT 
 1 AS `descricao`,
 1 AS `id`,
 1 AS `id_estado`,
 1 AS `id_cliente`,
 1 AS `id_fornecedor`,
 1 AS `id_forma_pagamento`,
 1 AS `id_tipo_venda`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_fornecedores_estoque`
--

DROP TABLE IF EXISTS `vw_fornecedores_estoque`;
/*!50001 DROP VIEW IF EXISTS `vw_fornecedores_estoque`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_fornecedores_estoque` AS SELECT 
 1 AS `id`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `unidade`,
 1 AS `quantidade_unidade`,
 1 AS `rms`,
 1 AS `lote`,
 1 AS `validade`,
 1 AS `estoque`,
 1 AS `preco_unidade`,
 1 AS `id_sintese`,
 1 AS `id_produto`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `id_tipo_venda`,
 1 AS `ativo`,
 1 AS `pf0`,
 1 AS `pf12`,
 1 AS `pf17`,
 1 AS `pf175`,
 1 AS `pf18`,
 1 AS `pf20`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`,
 1 AS `contra_proposta`,
 1 AS `porcentagem_campanha`,
 1 AS `preco`,
 1 AS `venda_parcelada`,
 1 AS `qtde_min_pedido`,
 1 AS `qtde_total_venda`,
 1 AS `aprovado`,
 1 AS `valor_final_revenda`,
 1 AS `motivo_recusa`,
 1 AS `destaque`,
 1 AS `aguardando_sintese`,
 1 AS `bloqueado`,
 1 AS `sem_depara`,
 1 AS `ean`,
 1 AS `ncm`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_fornecedores_usuarios`
--

DROP TABLE IF EXISTS `vw_fornecedores_usuarios`;
/*!50001 DROP VIEW IF EXISTS `vw_fornecedores_usuarios`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_fornecedores_usuarios` AS SELECT 
 1 AS `tipo`,
 1 AS `id`,
 1 AS `id_comprador`,
 1 AS `tipo_usuario`,
 1 AS `nivel`,
 1 AS `nome`,
 1 AS `email`,
 1 AS `senha`,
 1 AS `telefone`,
 1 AS `celular`,
 1 AS `rg`,
 1 AS `cpf`,
 1 AS `foto`,
 1 AS `administrador`,
 1 AS `situacao`,
 1 AS `token`,
 1 AS `id_fornecedor`,
 1 AS `remember_token`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_fornecedores_usuarios_rotas`
--

DROP TABLE IF EXISTS `vw_fornecedores_usuarios_rotas`;
/*!50001 DROP VIEW IF EXISTS `vw_fornecedores_usuarios_rotas`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_fornecedores_usuarios_rotas` AS SELECT 
 1 AS `id`,
 1 AS `id_parente`,
 1 AS `posicao`,
 1 AS `rotulo`,
 1 AS `url`,
 1 AS `icone`,
 1 AS `alvo`,
 1 AS `situacao`,
 1 AS `data_registro`,
 1 AS `data_atualizacao`,
 1 AS `grupo`,
 1 AS `modal`,
 1 AS `id_fornecedor`,
 1 AS `tipo_usuario`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_not_found_sintese`
--

DROP TABLE IF EXISTS `vw_not_found_sintese`;
/*!50001 DROP VIEW IF EXISTS `vw_not_found_sintese`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_not_found_sintese` AS SELECT 
 1 AS `codigo`,
 1 AS `marca`,
 1 AS `id_fornecedor`,
 1 AS `id_usuario`,
 1 AS `nome_comercial`,
 1 AS `descricao`,
 1 AS `apresentacao`,
 1 AS `nome`,
 1 AS `razao_social`,
 1 AS `cnpj`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_ocs_produtos`
--

DROP TABLE IF EXISTS `vw_ocs_produtos`;
/*!50001 DROP VIEW IF EXISTS `vw_ocs_produtos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_ocs_produtos` AS SELECT 
 1 AS `id`,
 1 AS `id_ordem_compra`,
 1 AS `Cd_Produto_Comprador`,
 1 AS `Ds_Unidade_Compra`,
 1 AS `Id_Marca`,
 1 AS `Ds_Marca`,
 1 AS `Qt_Embalagem`,
 1 AS `Qt_Produto`,
 1 AS `Vl_Preco_Produto`,
 1 AS `Ds_Observacao_Produto`,
 1 AS `Cd_ProdutoERP`,
 1 AS `Cd_Ordem_Compra`,
 1 AS `Id_Produto_Sintese`,
 1 AS `Id_Sintese`,
 1 AS `Ds_Produto_Comprador`,
 1 AS `codigo`,
 1 AS `ean`,
 1 AS `resgatado`,
 1 AS `id_confirmacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_ofertas_b2b`
--

DROP TABLE IF EXISTS `vw_ofertas_b2b`;
/*!50001 DROP VIEW IF EXISTS `vw_ofertas_b2b`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_ofertas_b2b` AS SELECT 
 1 AS `id_solicitacao`,
 1 AS `id_forma_pagamento`,
 1 AS `valor_maximo`,
 1 AS `id_prazo_entrega`,
 1 AS `quantidade`,
 1 AS `codigo`,
 1 AS `id_fornecedor_interessado`,
 1 AS `id_fornecedor_oferta`,
 1 AS `id_usuario`,
 1 AS `cnpj`,
 1 AS `razao_social`,
 1 AS `estado`,
 1 AS `telefone`,
 1 AS `celular`,
 1 AS `email`,
 1 AS `itens`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_ordens_compra`
--

DROP TABLE IF EXISTS `vw_ordens_compra`;
/*!50001 DROP VIEW IF EXISTS `vw_ordens_compra`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_ordens_compra` AS SELECT 
 1 AS `id`,
 1 AS `Dt_Ordem_Compra`,
 1 AS `Cd_Ordem_Compra`,
 1 AS `id_fornecedor`,
 1 AS `id_cliente`,
 1 AS `cnpj`,
 1 AS `razao_social`,
 1 AS `cidade`,
 1 AS `estado`,
 1 AS `total_itens`,
 1 AS `total`,
 1 AS `total_formatado`,
 1 AS `integrador`,
 1 AS `id_integrador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_pedidos`
--

DROP TABLE IF EXISTS `vw_pedidos`;
/*!50001 DROP VIEW IF EXISTS `vw_pedidos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_pedidos` AS SELECT 
 1 AS `id`,
 1 AS `id_carrinho`,
 1 AS `id_cliente`,
 1 AS `id_fornecedor`,
 1 AS `id_forma_pagamento_fornecedor`,
 1 AS `id_prazo_entrega`,
 1 AS `id_tipo_venda`,
 1 AS `token`,
 1 AS `status`,
 1 AS `cnpj`,
 1 AS `razao_social`,
 1 AS `cidade`,
 1 AS `uf`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`,
 1 AS `total_itens`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_pedidos_produtos`
--

DROP TABLE IF EXISTS `vw_pedidos_produtos`;
/*!50001 DROP VIEW IF EXISTS `vw_pedidos_produtos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_pedidos_produtos` AS SELECT 
 1 AS `id`,
 1 AS `id_pedido`,
 1 AS `id_cliente`,
 1 AS `id_fornecedor`,
 1 AS `id_carrinho`,
 1 AS `id_produto`,
 1 AS `quantidade`,
 1 AS `preco_unidade`,
 1 AS `status`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`,
 1 AS `produto_descricao`,
 1 AS `razao_social`,
 1 AS `marca`,
 1 AS `id_sintese`,
 1 AS `id_estado`,
 1 AS `codigo`,
 1 AS `porcentagem_campanha`,
 1 AS `ativo`,
 1 AS `valor`,
 1 AS `total`,
 1 AS `preco_unitario`,
 1 AS `quantidade_unidade`,
 1 AS `justificativa`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_pedidos_rep_prods`
--

DROP TABLE IF EXISTS `vw_pedidos_rep_prods`;
/*!50001 DROP VIEW IF EXISTS `vw_pedidos_rep_prods`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_pedidos_rep_prods` AS SELECT 
 1 AS `id_pedido`,
 1 AS `cd_produto_fornecedor`,
 1 AS `preco_unidade`,
 1 AS `quantidade_solicitada`,
 1 AS `desconto`,
 1 AS `preco_desconto`,
 1 AS `total`,
 1 AS `data_criacao`,
 1 AS `nome_comercial`,
 1 AS `descricao`,
 1 AS `apresentacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_pfs_agrupados`
--

DROP TABLE IF EXISTS `vw_pfs_agrupados`;
/*!50001 DROP VIEW IF EXISTS `vw_pfs_agrupados`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_pfs_agrupados` AS SELECT 
 1 AS `id`,
 1 AS `produto_descricao`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `unidade`,
 1 AS `quantidade_unidade`,
 1 AS `rms`,
 1 AS `lote`,
 1 AS `validade`,
 1 AS `estoque`,
 1 AS `preco_unidade`,
 1 AS `id_produto`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `id_tipo_venda`,
 1 AS `ativo`,
 1 AS `pf0`,
 1 AS `pf12`,
 1 AS `pf17`,
 1 AS `pf175`,
 1 AS `pf18`,
 1 AS `pf20`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`,
 1 AS `contra_proposta`,
 1 AS `porcentagem_campanha`,
 1 AS `preco`,
 1 AS `venda_parcelada`,
 1 AS `qtde_min_pedido`,
 1 AS `qtde_total_venda`,
 1 AS `aprovado`,
 1 AS `valor_final_revenda`,
 1 AS `motivo_recusa`,
 1 AS `destaque`,
 1 AS `id_sintese`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_preco_medio`
--

DROP TABLE IF EXISTS `vw_preco_medio`;
/*!50001 DROP VIEW IF EXISTS `vw_preco_medio`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_preco_medio` AS SELECT 
 1 AS `produto`,
 1 AS `marca`,
 1 AS `quantidade_embalagem`,
 1 AS `preco_medio`,
 1 AS `data_criacao`,
 1 AS `id_produto`,
 1 AS `id`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_preco_medio_panpharma`
--

DROP TABLE IF EXISTS `vw_preco_medio_panpharma`;
/*!50001 DROP VIEW IF EXISTS `vw_preco_medio_panpharma`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_preco_medio_panpharma` AS SELECT 
 1 AS `cd_produto`,
 1 AS `id_produto`,
 1 AS `produto_catalogo`,
 1 AS `descricao`,
 1 AS `preco_medio_marca`,
 1 AS `marca`,
 1 AS `preco_outras`,
 1 AS `outra_marca`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_precos_fonecedores`
--

DROP TABLE IF EXISTS `vw_precos_fonecedores`;
/*!50001 DROP VIEW IF EXISTS `vw_precos_fonecedores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_precos_fonecedores` AS SELECT 
 1 AS `id`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `unidade`,
 1 AS `quantidade_unidade`,
 1 AS `rms`,
 1 AS `lote`,
 1 AS `validade`,
 1 AS `estoque`,
 1 AS `preco_unidade`,
 1 AS `id_sintese`,
 1 AS `id_produto`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `id_tipo_venda`,
 1 AS `ativo`,
 1 AS `pf0`,
 1 AS `pf12`,
 1 AS `pf17`,
 1 AS `pf175`,
 1 AS `pf18`,
 1 AS `pf20`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`,
 1 AS `contra_proposta`,
 1 AS `porcentagem_campanha`,
 1 AS `preco`,
 1 AS `venda_parcelada`,
 1 AS `qtde_min_pedido`,
 1 AS `qtde_total_venda`,
 1 AS `aprovado`,
 1 AS `valor_final_revenda`,
 1 AS `motivo_recusa`,
 1 AS `destaque`,
 1 AS `aguardando_sintese`,
 1 AS `bloqueado`,
 1 AS `sem_depara`,
 1 AS `ean`,
 1 AS `ncm`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_precos_grafico`
--

DROP TABLE IF EXISTS `vw_precos_grafico`;
/*!50001 DROP VIEW IF EXISTS `vw_precos_grafico`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_precos_grafico` AS SELECT 
 1 AS `id_fornecedor`,
 1 AS `quantidade_unidade`,
 1 AS `preco`,
 1 AS `validade`,
 1 AS `id_estado`,
 1 AS `total`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_prods_automatic_offer`
--

DROP TABLE IF EXISTS `vw_prods_automatic_offer`;
/*!50001 DROP VIEW IF EXISTS `vw_prods_automatic_offer`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_prods_automatic_offer` AS SELECT 
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `id_cliente`,
 1 AS `codigo`,
 1 AS `id_marca`,
 1 AS `marca`,
 1 AS `desconto_percentual`,
 1 AS `promocao`,
 1 AS `qtd_unidade`,
 1 AS `estoque`,
 1 AS `validade`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos`
--

DROP TABLE IF EXISTS `vw_produtos`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos` AS SELECT 
 1 AS `produto_descricao`,
 1 AS `id`,
 1 AS `nome_comercial`,
 1 AS `apresentacao`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `marca`,
 1 AS `id_sintese`,
 1 AS `id_produto`,
 1 AS `id_estado`,
 1 AS `codigo`,
 1 AS `porcentagem_campanha`,
 1 AS `ativo`,
 1 AS `aprovado`,
 1 AS `preco`,
 1 AS `preco_unidade`,
 1 AS `quantidade`,
 1 AS `validade`,
 1 AS `lote`,
 1 AS `quantidade_unidade`,
 1 AS `destaque`,
 1 AS `estado`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_cliente_depara`
--

DROP TABLE IF EXISTS `vw_produtos_cliente_depara`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_cliente_depara`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_cliente_depara` AS SELECT 
 1 AS `id_cliente`,
 1 AS `codigo_hospital`,
 1 AS `id_produto`,
 1 AS `id_sintese`,
 1 AS `codigo_fornecedor`,
 1 AS `id_fornecedor`,
 1 AS `produto_comprador`,
 1 AS `produto_bionexo`,
 1 AS `integrador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_cliente_depara_integrador`
--

DROP TABLE IF EXISTS `vw_produtos_cliente_depara_integrador`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_cliente_depara_integrador`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_cliente_depara_integrador` AS SELECT 
 1 AS `id_cliente`,
 1 AS `codigo_hospital`,
 1 AS `id_produto`,
 1 AS `id_sintese`,
 1 AS `codigo_fornecedor`,
 1 AS `id_fornecedor`,
 1 AS `produto_comprador`,
 1 AS `produto_bionexo`,
 1 AS `integrador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_clientes_sem_depara`
--

DROP TABLE IF EXISTS `vw_produtos_clientes_sem_depara`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_clientes_sem_depara`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_clientes_sem_depara` AS SELECT 
 1 AS `id_cliente`,
 1 AS `codigo`,
 1 AS `descricao`,
 1 AS `quantidade_unidade`,
 1 AS `ativo`,
 1 AS `ocultar`,
 1 AS `process`,
 1 AS `id_categoria`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_clientes_sem_depara_apoio`
--

DROP TABLE IF EXISTS `vw_produtos_clientes_sem_depara_apoio`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_clientes_sem_depara_apoio`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_clientes_sem_depara_apoio` AS SELECT 
 1 AS `id_cliente`,
 1 AS `codigo`,
 1 AS `unidade`,
 1 AS `descricao`,
 1 AS `quantidade_unidade`,
 1 AS `ativo`,
 1 AS `ocultar`,
 1 AS `process`,
 1 AS `not_found`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_cotados`
--

DROP TABLE IF EXISTS `vw_produtos_cotados`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_cotados`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_cotados` AS SELECT 
 1 AS `id_pfv`,
 1 AS `produto`,
 1 AS `preco_unit`,
 1 AS `total`,
 1 AS `preco_total`,
 1 AS `qtd_total`,
 1 AS `id_fornecedor`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_fornecedores`
--

DROP TABLE IF EXISTS `vw_produtos_fornecedores`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_fornecedores`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_fornecedores` AS SELECT 
 1 AS `produto_descricao`,
 1 AS `id`,
 1 AS `id_fornecedor`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `quantidade_unidade`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `unidade`,
 1 AS `rms`,
 1 AS `id_sintese`,
 1 AS `id_produto`,
 1 AS `id_marca`,
 1 AS `ativo`,
 1 AS `aprovado`,
 1 AS `preco`,
 1 AS `bloqueado`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_fornecedores_sintese`
--

DROP TABLE IF EXISTS `vw_produtos_fornecedores_sintese`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_fornecedores_sintese`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_fornecedores_sintese` AS SELECT 
 1 AS `id`,
 1 AS `id_sintese`,
 1 AS `produto_descricao`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `rms`,
 1 AS `preco_unidade`,
 1 AS `quantidade_unidade`,
 1 AS `id_produto`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `ativo`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_fornecedores_validades`
--

DROP TABLE IF EXISTS `vw_produtos_fornecedores_validades`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_fornecedores_validades`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_fornecedores_validades` AS SELECT 
 1 AS `produto_descricao`,
 1 AS `id`,
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `unidade`,
 1 AS `rms`,
 1 AS `id_sintese`,
 1 AS `id_produto`,
 1 AS `id_marca`,
 1 AS `ativo`,
 1 AS `aprovado`,
 1 AS `preco`,
 1 AS `validade`,
 1 AS `lote`,
 1 AS `estoque`,
 1 AS `quantidade_unidade`,
 1 AS `bloqueado`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_lotes`
--

DROP TABLE IF EXISTS `vw_produtos_lotes`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_lotes`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_lotes` AS SELECT 
 1 AS `codigo`,
 1 AS `id_fornecedor`,
 1 AS `ean`,
 1 AS `nome_comercial`,
 1 AS `descricao`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `quantidade_unidade`,
 1 AS `lote`,
 1 AS `local`,
 1 AS `validade`,
 1 AS `estoque`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_pre_depara`
--

DROP TABLE IF EXISTS `vw_produtos_pre_depara`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_pre_depara`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_pre_depara` AS SELECT 
 1 AS `id`,
 1 AS `descricao_sintese`,
 1 AS `descricao_catalogo`,
 1 AS `id_sintese`,
 1 AS `id_produto`,
 1 AS `cd_produto`,
 1 AS `codigo_catalogo`,
 1 AS `id_cliente`,
 1 AS `principios`,
 1 AS `nome_fantasia`,
 1 AS `estado`,
 1 AS `integrador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_pre_depara_apoio`
--

DROP TABLE IF EXISTS `vw_produtos_pre_depara_apoio`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_pre_depara_apoio`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_pre_depara_apoio` AS SELECT 
 1 AS `id`,
 1 AS `descricao_sintese`,
 1 AS `descricao_catalogo`,
 1 AS `id_sintese`,
 1 AS `id_produto`,
 1 AS `cd_produto`,
 1 AS `codigo_catalogo`,
 1 AS `id_cliente`,
 1 AS `principios`,
 1 AS `nome_fantasia`,
 1 AS `estado`,
 1 AS `integrador`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_pre_match`
--

DROP TABLE IF EXISTS `vw_produtos_pre_match`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_pre_match`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_pre_match` AS SELECT 
 1 AS `id`,
 1 AS `id_sintese`,
 1 AS `produto_descricao`,
 1 AS `codigo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `rms`,
 1 AS `preco_unidade`,
 1 AS `quantidade_unidade`,
 1 AS `id_produto`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `ativo`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_preco_fixo`
--

DROP TABLE IF EXISTS `vw_produtos_preco_fixo`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_preco_fixo`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_preco_fixo` AS SELECT 
 1 AS `cnpj`,
 1 AS `id_cliente`,
 1 AS `nome_fantasia`,
 1 AS `id_estado`,
 1 AS `estado`,
 1 AS `codigo`,
 1 AS `nome_comercial`,
 1 AS `preco_base`,
 1 AS `id_fornecedor`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_precos`
--

DROP TABLE IF EXISTS `vw_produtos_precos`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_precos`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_precos` AS SELECT 
 1 AS `codigo`,
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `preco_unitario`,
 1 AS `data_criacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_precos_max`
--

DROP TABLE IF EXISTS `vw_produtos_precos_max`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_precos_max`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_precos_max` AS SELECT 
 1 AS `codigo`,
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `preco_unitario`,
 1 AS `data_criacao`,
 1 AS `uf`,
 1 AS `descricao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_precos_old`
--

DROP TABLE IF EXISTS `vw_produtos_precos_old`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_precos_old`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_precos_old` AS SELECT 
 1 AS `codigo`,
 1 AS `id_fornecedor`,
 1 AS `id_estado`,
 1 AS `preco_unitario`,
 1 AS `data_criacao`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_sem_depara`
--

DROP TABLE IF EXISTS `vw_produtos_sem_depara`;
/*!50001 DROP VIEW IF EXISTS `vw_produtos_sem_depara`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_produtos_sem_depara` AS SELECT 
 1 AS `id`,
 1 AS `codigo`,
 1 AS `codigo_externo`,
 1 AS `apresentacao`,
 1 AS `marca`,
 1 AS `descricao`,
 1 AS `nome_comercial`,
 1 AS `preco_unidade`,
 1 AS `id_marca`,
 1 AS `id_fornecedor`,
 1 AS `ativo`,
 1 AS `data_criacao`,
 1 AS `data_atualizacao`,
 1 AS `preco`,
 1 AS `aprovado`,
 1 AS `bloqueado`,
 1 AS `rms`,
 1 AS `ean`,
 1 AS `ncm`,
 1 AS `quantidade_unidade`,
 1 AS `unidade`,
 1 AS `b2b`,
 1 AS `ocultar_de_para`,
 1 AS `classe`,
 1 AS `origem`,
 1 AS `id_loja_saida`,
 1 AS `pharma`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `vw_produtos_sem_estoque`
--

DROP TABLE IF EXISTS `vw_produtos_sem_estoque`;