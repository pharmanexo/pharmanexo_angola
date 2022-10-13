-- MySQL dump 10.13  Distrib 8.0.31, for macos12.2 (arm64)
--
-- Host: 10.101.70.5    Database: pharmanexo
-- ------------------------------------------------------
-- Server version	8.0.21

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;

SET @@SESSION.SQL_LOG_BIN= 0;

SET FOREIGN_KEY_CHECKS=0;
SET @@global.sql_mode= 'NO_ENGINE_SUBSTITUTION';

--
-- GTID state at the beginning of the backup 
--

-- SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '0fa74343-f6ab-11ea-a927-005056aa93ba:1-775256428';

--
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
-- Table structure for table `aceites`
--

DROP TABLE IF EXISTS `aceites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aceites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cnpj` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `aceite` varchar(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Não' COMMENT 'Define se o usuário aceitou ou não os termos de uso; 0 = Não aceitou; 1 = Aceitou',
  `data_aceite` datetime DEFAULT NULL COMMENT 'Registra a data em que o usuário aceitou os termos de uso e não a data do registro em sí, pois o usuário pode selecionar "não", neste caso, este campo fica em branco (NULL)',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Aceites: Tabela de registro de todos os aceites dos termos de uso por parte dos usuários.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_log`
--

DROP TABLE IF EXISTS `api_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `origem` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `dispositivo` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `data_acesso` datetime DEFAULT NULL,
  `dados` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8141 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_token`
--

DROP TABLE IF EXISTS `api_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `api_token` (
  `usuario` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `senha` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `situacao` tinyint DEFAULT '1',
  `hash` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `apoio_produtos_match`
--

DROP TABLE IF EXISTS `apoio_produtos_match`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apoio_produtos_match` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produto` int NOT NULL,
  `descricao_sintese` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `palavra_chave` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `codigo_catalogo` varchar(20) COLLATE utf8_bin NOT NULL,
  `descricao_catalogo` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `associado` int NOT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_sintese` int DEFAULT NULL,
  `principios` text COLLATE utf8_bin,
  `visto` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_TESTE_011` (`codigo_catalogo`,`id_cliente`),
  KEY `IDX_GSYS_TESTE_022` (`codigo_catalogo`),
  KEY `IDX_GSYS_TESTE_033` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=6384472 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `carrinhos`
--

DROP TABLE IF EXISTS `carrinhos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrinhos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int unsigned NOT NULL,
  `chave` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ativo` tinyint unsigned NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `carrinhos_cliente_idx` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Carrinhos: Tabela com registro de todos os carrinhos registrados no marketplace, a validade do carrinho deverá ser setada na tela de administração.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descrição da categoria',
  `ativo` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'Define se a categoria está ativa ou não para novas inclusões aos produtos; 0 = Inativo; 1 = Ativo',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Categorias: Tabela contendo diversas categorias padronizadas e parametrizadas através do portal de administração.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ci_logs`
--

DROP TABLE IF EXISTS `ci_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ci_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `action` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `module` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `origin` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24986 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clientes_contribuintes`
--

DROP TABLE IF EXISTS `clientes_contribuintes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_contribuintes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `clientes_contribuintes_compradores_id_fk` (`id_cliente`),
  KEY `clientes_contribuintes_fornecedores_id_fk` (`id_fornecedor`),
  CONSTRAINT `clientes_contribuintes_compradores_id_fk` FOREIGN KEY (`id_cliente`) REFERENCES `compradores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `clientes_contribuintes_fornecedores_id_fk` FOREIGN KEY (`id_fornecedor`) REFERENCES `fornecedores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clientes_precos`
--

DROP TABLE IF EXISTS `clientes_precos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_precos` (
  `cod_cliente` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comissionamento_pharmanexo`
--

DROP TABLE IF EXISTS `comissionamento_pharmanexo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comissionamento_pharmanexo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int unsigned NOT NULL COMMENT 'ID do fornecedor na tabela "usuarios"',
  `comissao` decimal(3,2) NOT NULL COMMENT 'Porcentagem de comissão em cima das vendas no período determinado',
  `periodo_validade` int unsigned NOT NULL DEFAULT '12' COMMENT 'Período validade definiso em meses, por padrão 12 meses',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comissionamento_pharmanexo_fornecedor_idx` (`id_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `comissionamentos_representantes_clientes`
--

DROP TABLE IF EXISTS `comissionamentos_representantes_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comissionamentos_representantes_clientes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_representante_fornecedor` int unsigned NOT NULL,
  `id_cliente` int unsigned NOT NULL,
  `comissao` decimal(3,2) NOT NULL COMMENT 'Comissão (em porcentagem) do representante para vendas especificas para o cliente.',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `com_rep_clientes_rep_fornecedor_idx` (`id_representante_fornecedor`),
  KEY `com_rep_clientes_rep_cliente_idx` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compradores`
--

DROP TABLE IF EXISTS `compradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compradores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `razao_social` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nome_fantasia` varchar(255) DEFAULT NULL,
  `cnpj` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `responsabilidade_tecnica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `protocolo_alvara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `inscricao_estadual` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `inscricao_municipal` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `validade_alvara` datetime DEFAULT NULL,
  `documento_alvara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cartao_cnpj` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `motivo_recusa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `aprovado` tinyint DEFAULT NULL,
  `numero_afe` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `copia_afe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `integracao` tinyint DEFAULT NULL,
  `id_tipo_venda` int NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cep` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `senha` varchar(150) DEFAULT NULL,
  `spi` tinyint(1) DEFAULT NULL,
  `pharma` int DEFAULT '0',
  `login` varchar(100) DEFAULT NULL,
  `id_responsavel` int DEFAULT NULL,
  `visitado` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_COMP_01` (`id`,`cnpj`,`razao_social`),
  KEY `IDX_GYS_COMP_01` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16669 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compradores_distribuidor`
--

DROP TABLE IF EXISTS `compradores_distribuidor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compradores_distribuidor` (
  `id_comprador` int DEFAULT NULL,
  `id_comprador_distribuidor` int DEFAULT NULL,
  `id_distribuidor` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `process` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compradores_integrador`
--

DROP TABLE IF EXISTS `compradores_integrador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compradores_integrador` (
  `id_integrador` int NOT NULL,
  `id_cliente` int NOT NULL,
  `dt_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `process` int DEFAULT '0',
  KEY `idx_gsys_compradores_tst01` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compradores_pharma`
--

DROP TABLE IF EXISTS `compradores_pharma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compradores_pharma` (
  `id_fornecedor` int DEFAULT NULL,
  `id_comprador` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `compradores_regiao`
--

DROP TABLE IF EXISTS `compradores_regiao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compradores_regiao` (
  `codfilial` int DEFAULT NULL,
  `numregiao` int DEFAULT NULL,
  `codcliente` int DEFAULT NULL,
  `cnpj` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `col` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `config_analise_mercado`
--

DROP TABLE IF EXISTS `config_analise_mercado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `config_analise_mercado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `data` json DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=456 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `configs`
--

DROP TABLE IF EXISTS `configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `json` int DEFAULT '0',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `configuracao_marca_comprador`
--

DROP TABLE IF EXISTS `configuracao_marca_comprador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracao_marca_comprador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `tipo` int DEFAULT NULL COMMENT '1 - Maior Estoque,\n2 - Menor Preço,\n3 - Marca',
  `marcas` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `configuracoes_envio`
--

DROP TABLE IF EXISTS `configuracoes_envio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracoes_envio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` int DEFAULT NULL COMMENT '1 => automatica, 2 => manual, 3 => os dois',
  `id_estado` int DEFAULT NULL,
  `observacao` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `id_fornecedor` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `validade` int NOT NULL,
  `integrador` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contatos_usuarios`
--

DROP TABLE IF EXISTS `contatos_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contatos_usuarios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_contato` varchar(120) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `telefone_comercial` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone_celular` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cargo` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone_comercial_alternativo` varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `telefone_celular_alternativo` varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=188 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Contatos: Contatos cadastrados referente a todos os registros que necessitem de contato.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `controle_cotacoes`
--

DROP TABLE IF EXISTS `controle_cotacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `controle_cotacoes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int unsigned NOT NULL,
  `id_estado` int unsigned DEFAULT NULL,
  `id_cliente` int unsigned DEFAULT NULL,
  `id_tipo_venda` int unsigned DEFAULT NULL,
  `regra_venda` tinyint(1) DEFAULT '0',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `integrador` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5651 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `controle_notificacoes`
--

DROP TABLE IF EXISTS `controle_notificacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `controle_notificacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_estado` int NOT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `tipo` int DEFAULT NULL COMMENT '1 - email\n2 - sms\n',
  `destinatario` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cot_responsaveis`
--

DROP TABLE IF EXISTS `cot_responsaveis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cot_responsaveis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_gerente` int DEFAULT NULL,
  `id_assistente` int DEFAULT NULL,
  `id_comprador` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `id_consultor` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `GSYS_IDX_CR_01` (`id_comprador`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cotacoes`
--

DROP TABLE IF EXISTS `cotacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cd_cotacao` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `nivel` int DEFAULT NULL,
  `notificacao` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `xml` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `obs` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `valor_minimo` decimal(14,4) DEFAULT NULL,
  `id_forma_pagamento` int DEFAULT NULL,
  `prazo_entrega` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25678 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cotacoes_produtos`
--

DROP TABLE IF EXISTS `cotacoes_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cotacoes_produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `qtd_solicitada` int NOT NULL,
  `qtd_embalagem` int NOT NULL,
  `id_sintese` int DEFAULT NULL,
  `id_produto` int DEFAULT NULL,
  `preco_marca` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `data_cotacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_cotacao` int NOT NULL,
  `cd_cotacao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `uf_fornecedor_oferta` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'ES',
  `cnpj_comprador` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `uf_comprador` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ES',
  `controle` int DEFAULT '1',
  `submetido` int NOT NULL DEFAULT '0',
  `cd_produto_comprador` varchar(50) DEFAULT NULL,
  `id_fornecedor` int NOT NULL DEFAULT '0',
  `id_fornecedor_logado` int DEFAULT NULL,
  `id_forma_pagamento` int NOT NULL DEFAULT '0',
  `prazo_entrega` int NOT NULL DEFAULT '0',
  `valor_minimo` decimal(14,4) NOT NULL DEFAULT '0.0000',
  `nivel` int DEFAULT NULL,
  `id_pfv` int DEFAULT NULL,
  `id_marca` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `obs` text,
  `obs_produto` text,
  `ocultar` int DEFAULT '0',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `codigo_oc` varchar(20) DEFAULT NULL,
  `integrador` varchar(30) DEFAULT 'SINTESE',
  `notificado` int DEFAULT '0',
  `data_download` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_CP_01` (`id_fornecedor`),
  KEY `IDX_GSYS_CP_02` (`id_cotacao`),
  KEY `IDX_GSYS_CP_03` (`cd_cotacao`),
  KEY `IDX_GSYS_CP_04` (`id`,`data_cotacao`,`id_cotacao`,`cd_cotacao`,`cnpj_comprador`,`uf_comprador`,`submetido`,`id_fornecedor`,`nivel`),
  KEY `IDX_GSYS_CP_05` (`submetido`,`id_fornecedor`,`nivel`),
  KEY `PHNX_01` (`nivel`,`id_fornecedor`),
  KEY `IDX_GSYS_CP_06` (`id_fornecedor`,`cd_cotacao`,`submetido`) USING BTREE,
  KEY `IDX_GSYS_CP_07` (`id_fornecedor`,`cd_cotacao`,`submetido`),
  KEY `IDX_GSYS_CP_08` (`id_cliente`),
  KEY `IDX_GSYS_CP_09` (`data_cotacao`,`id_cliente`,`id_produto`),
  KEY `IDX_GSYS_CP_10` (`id_cliente`,`id_produto`,`id_pfv`),
  KEY `IDX_GSYS_CP_11` (`id_produto`,`cd_cotacao`,`id_fornecedor`,`cd_produto_comprador`),
  KEY `IDX_GSYS_CP_12` (`submetido`,`id_fornecedor`),
  KEY `IDX_GSYS_CP_13` (`id_fornecedor`,`id_produto`),
  KEY `IDX_GSYS_CP_14` (`id_fornecedor`,`id_cliente`,`data_cotacao`),
  KEY `IDX_GYS_COT_01` (`cd_cotacao`,`id_fornecedor`,`submetido`,`controle`,`nivel`)
) ENGINE=InnoDB AUTO_INCREMENT=1997257 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `dados_usuarios`
--

DROP TABLE IF EXISTS `dados_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dados_usuarios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario_aprovacao` int unsigned DEFAULT '0' COMMENT 'ID do usuário (tabela "usuários") responsável pela aprovação do cadastro do usuário atual',
  `cnpj` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Cadastro Nacional de Pessoas Juridicas',
  `razao_social` varchar(85) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Razão social da empresa',
  `nome_fantasia` varchar(85) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nome fantasia da Empresa.',
  `protocolo_alvara` varchar(85) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Protocolo do alvará será utilizado caso o alvará ainda não esteja concluído e lavrado',
  `inscricao_estadual` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Inscrição estadual da empresa',
  `inscricao_municipal` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Inscrição municipal da empresa',
  `validade_alvara` datetime DEFAULT NULL COMMENT 'Validade do alvará',
  `documento_alvara` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Nome do arquivo no fileserver/alvaras',
  `cartao_cnpj` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `motivo_recusa` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Motivo de recusa na aprovação do usuário, este motivo será obrigatório caso "aprovado" seja = 0',
  `aprovado` tinyint unsigned DEFAULT '0' COMMENT 'Define se o fornecedor/cliente está aprovado ou não para acesso ao sistema.0 = Não aprovado;1 = Aprovado;Se não for aprovado (0), "motivo" é obrigatório.',
  `numero_afe` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'O Certificado de Autorização de Funcionamento (Certificado de AFE) é um documento emitido pela Anvisa que comprova que a empresa está autorizada a exercer as atividades descritas no certificado. Nele, constam o número da autorização da empresa e seu endereço.',
  `copia_afe` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Cópia digital do arquivo AFE',
  `integracao` tinyint unsigned DEFAULT '0' COMMENT 'Define integração do usuário, caso seja fornecedo ("usuarios"."tipo" = 1):0 = Não integrado e não automatizado;1 = Integrado e não automatizado;2 = Integrado e automatizado;',
  `id_tipo_venda` int NOT NULL DEFAULT '1',
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Logo marca do usuário armazenada em fileserver /logos',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `responsabilidade_tecnica` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Responsábilidade técnica alegando capacitação para atuação',
  PRIMARY KEY (`id`),
  KEY `dados_usuarios_usuario_aprovacao_idx` (`id_usuario_aprovacao`)
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Dados usuários: Tabela responsável por armazenar todos os dados cadastrais do usuário (clientes / forncedores / administradores / representantes).\nInterligada diretamente com a tabela "usuários"';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_notificacao`
--

DROP TABLE IF EXISTS `email_notificacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_notificacao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `gerente` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `consultor` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `geral` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `grupo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `alerta_abertura` int DEFAULT '0',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_update` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5590 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `enderecos`
--

DROP TABLE IF EXISTS `enderecos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enderecos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_estado` int unsigned NOT NULL DEFAULT '0' COMMENT 'ID do estado na tabela "estados"',
  `endereco` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `numero` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'S/N',
  `bairro` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cep` varchar(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cidade` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `complemento` varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `endereco_estado_idx` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Endereços: Endereços cadastrados referente a todos os registros que necessitem de endereço.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipe_comercial`
--

DROP TABLE IF EXISTS `equipe_comercial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipe_comercial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `telefone` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `regiao` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `cargo_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `equipe_comercial_cargos`
--

DROP TABLE IF EXISTS `equipe_comercial_cargos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipe_comercial_cargos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estado_icms`
--

DROP TABLE IF EXISTS `estado_icms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_icms` (
  `id_estado` int DEFAULT NULL,
  `icms` decimal(9,2) DEFAULT NULL,
  `data_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estados` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `uf` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `descricao` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Estados: Tabela de padronização de todos os estados do território brasileiro.\n';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estoque`
--

DROP TABLE IF EXISTS `estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estoque` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_sintese` int unsigned NOT NULL,
  `id_produto` int unsigned NOT NULL COMMENT 'ID do produto na tabela "produtos"',
  `id_marca` int unsigned NOT NULL COMMENT 'ID da marca na tabela "marcas"',
  `marca` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int unsigned NOT NULL COMMENT 'ID fo fornecedor na tabela "usuarios"',
  `id_tipo_venda` int NOT NULL DEFAULT '0',
  `id_estado` int unsigned NOT NULL COMMENT 'ID do estado na tabela "estados"',
  `id_unidade` int unsigned NOT NULL COMMENT 'ID da unidade na tabelas "unidades"',
  `unidade` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `produto_descricao` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `apresentacao` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `rms` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` int unsigned NOT NULL COMMENT 'Código é a identificação do produto enviada/sincronizada com o fornecedor.\\nA triangulação do produto em relação ao fornecedor se dará pelas junções do código, id_produto e id_fornecedor',
  `contra_proposta` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 0 = Não; 1 = Sim',
  `porcentagem_campanha` decimal(3,2) DEFAULT '0.00' COMMENT 'Porcentagem adicional para campanha',
  `ativo` tinyint(1) NOT NULL DEFAULT '0' COMMENT '	Define se o produto está ativo ou nãp; 0 = Inativo; 1 = Ativo Controle através de cron ou procedure no banco de dados, onde, se o produto estiver vencido "validade < now()", o status dever´ser atualizado para 0 = Inativo',
  `preco` decimal(10,4) NOT NULL,
  `venda_parcelada` int NOT NULL DEFAULT '0',
  `preco_unidade` decimal(10,4) unsigned NOT NULL COMMENT 'Preço referente a uma unidade do produto.',
  `quantidade` int unsigned NOT NULL,
  `quantidade_unidade` int DEFAULT '1',
  `qtde_min_pedido` int NOT NULL DEFAULT '0',
  `qtde_total_venda` int NOT NULL DEFAULT '0',
  `aprovado` int NOT NULL DEFAULT '0' COMMENT '0-em aprovação 1-em venda 3- recusado',
  `valor_final_revenda` int NOT NULL DEFAULT '0' COMMENT '0 - iguais 1- diferente',
  `motivo_recusa` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `destaque` int NOT NULL DEFAULT '0' COMMENT '0-sem destaque  1-em destaque',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `estoque_produto_idx` (`id_produto`),
  KEY `estoque_fornecedor_idx` (`id_fornecedor`),
  KEY `estoque_marca_idx` (`id_marca`),
  KEY `estoque_estado_idx` (`id_estado`),
  KEY `estoque_unidade_idx` (`id_unidade`)
) ENGINE=InnoDB AUTO_INCREMENT=9524 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estudos`
--

DROP TABLE IF EXISTS `estudos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `link` text COLLATE utf8_bin,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_fornecedor` int DEFAULT NULL,
  `titulo` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `faq_questions`
--

DROP TABLE IF EXISTS `faq_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pergunta` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `resposta` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `formas`
--

DROP TABLE IF EXISTS `formas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `forma` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `formas_pagamento`
--

DROP TABLE IF EXISTS `formas_pagamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formas_pagamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(255) DEFAULT NULL,
  `qtd_dias` int DEFAULT NULL,
  `ativo` int NOT NULL DEFAULT '1',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `formas_pagamento_id_index` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=574 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `formas_pagamento_depara`
--

DROP TABLE IF EXISTS `formas_pagamento_depara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formas_pagamento_depara` (
  `cd_forma_pagamento` int NOT NULL,
  `id_forma_pagamento` int DEFAULT NULL,
  `descricao` varchar(100) COLLATE utf8_bin NOT NULL,
  `integrador` int NOT NULL,
  `ativo` int DEFAULT '1',
  `qtd_dias` int NOT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `formas_pagamento_fornecedores`
--

DROP TABLE IF EXISTS `formas_pagamento_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formas_pagamento_fornecedores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_estado` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL COMMENT 'ID do cliente na tabela "usuarios"',
  `id_fornecedor` int unsigned NOT NULL COMMENT 'ID do fornecedor na tabela "usuarios"',
  `id_forma_pagamento` int unsigned NOT NULL COMMENT 'ID da dorma de pagamento na tabela "formas_pagamento"',
  `id_tipo_venda` int unsigned NOT NULL COMMENT 'ID do tipo de venda na tabela "tipos_venda"',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `formas_pagamento_fornecedores_fornecedor_idx` (`id_fornecedor`),
  KEY `formas_pagamento_fornecedores_forma_pagamento_idx` (`id_forma_pagamento`),
  KEY `formas_pagamento_fornecedores_tipo_venda_idx` (`id_tipo_venda`)
) ENGINE=InnoDB AUTO_INCREMENT=3004 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Formas pagamentos fornecedores: Tabela com registro de todas as formas de pagamento registrada pelos fornecedores especificamente para cada cliente, caso o cliente não possua uma forma de pagamento definida pelo fornecedor, a opção "Á VISTA" deverá ser definida como default.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `formas_pagamento_integradores`
--

DROP TABLE IF EXISTS `formas_pagamento_integradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formas_pagamento_integradores` (
  `id` int NOT NULL,
  `id_integrador` int DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `qtd_dias` int DEFAULT NULL,
  `ativo` int NOT NULL DEFAULT '1',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `formas_pagamento_id_index` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fornecedores`
--

DROP TABLE IF EXISTS `fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_matriz` int DEFAULT NULL,
  `cnpj` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Cadastro Nacional de Pessoas Juridicas',
  `razao_social` varchar(85) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Razão social da empresa',
  `nome_fantasia` varchar(85) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nome fantasia da Empresa.',
  `protocolo_alvara` varchar(85) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Protocolo do alvará será utilizado caso o alvará ainda não esteja concluído e lavrado',
  `inscricao_estadual` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Inscrição estadual da empresa',
  `inscricao_municipal` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Inscrição municipal da empresa',
  `validade_alvara` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Validade do alvará',
  `documento_alvara` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nome do arquivo no fileserver/alvaras',
  `cartao_cnpj` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motivo_recusa` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Motivo de recusa na aprovação do usuário, este motivo será obrigatório caso "aprovado" seja = 0',
  `aprovado` tinyint unsigned DEFAULT '0' COMMENT 'Define se o fornecedor/cliente está aprovado ou não para acesso ao sistema.0 = Não aprovado;1 = Aprovado;Se não for aprovado (0), "motivo" é obrigatório.',
  `numero_afe` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'O Certificado de Autorização de Funcionamento (Certificado de AFE) é um documento emitido pela Anvisa que comprova que a empresa está autorizada a exercer as atividades descritas no certificado. Nele, constam o número da autorização da empresa e seu endereço.',
  `copia_afe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cópia digital do arquivo AFE',
  `integracao` tinyint unsigned DEFAULT '0' COMMENT 'Define integração do usuário, caso seja fornecedo ("usuarios"."tipo" = 1):0 = Não integrado e não automatizado;1 = Integrado e não automatizado;2 = Integrado e automatizado;',
  `id_tipo_venda` int DEFAULT '3',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Logo marca do usuário armazenada em fileserver /logos',
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `cidade` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `responsabilidade_tecnica` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Responsábilidade técnica alegando capacitação para atuação',
  `usuarios_permitidos` int DEFAULT NULL,
  `status` int DEFAULT NULL,
  `celular` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ultimo_xml_id` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inicio_atualizacao_estoque` datetime DEFAULT CURRENT_TIMESTAMP,
  `termino_atualizacao_estoque` datetime DEFAULT CURRENT_TIMESTAMP,
  `sintese` int DEFAULT NULL,
  `senha` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` int DEFAULT '0' COMMENT '0 - FORNECEDOR\n1 - DIST X DIST',
  `compra_distribuidor` tinyint(1) DEFAULT '0',
  `tipo_venda` int DEFAULT '1' COMMENT '0 = nada, 1 = manual, 2 = automatico, 3 = ambos',
  `emails_config` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permitir_cadastro_prod` int DEFAULT '0',
  `config` text COLLATE utf8mb4_unicode_ci,
  `credencial_bionexo` json DEFAULT NULL,
  `credencial_apoio` text COLLATE utf8mb4_unicode_ci,
  `chave_sintese` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_user` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_password` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` text COLLATE utf8mb4_unicode_ci,
  `margem_estoque` decimal(6,2) DEFAULT NULL,
  `identificador` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `GSYS_IDX_FRN_01` (`id`,`tipo_venda`),
  KEY `GSYS_IDX_FRN_02` (`tipo_venda`),
  KEY `GSYS_IDX_FRN_03` (`id`,`cnpj`,`razao_social`,`nome_fantasia`)
) ENGINE=InnoDB AUTO_INCREMENT=5047 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fornecedores_estados`
--

DROP TABLE IF EXISTS `fornecedores_estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedores_estados` (
  `id_estado` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fornecedores_matriz`
--

DROP TABLE IF EXISTS `fornecedores_matriz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedores_matriz` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fornecedores_prioridades`
--

DROP TABLE IF EXISTS `fornecedores_prioridades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fornecedores_prioridades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL,
  `prioridade` int NOT NULL DEFAULT '1',
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupos_usuarios`
--

DROP TABLE IF EXISTS `grupos_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos_usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `hora_fim_acesso` time NOT NULL,
  `hora_ini_acesso` time NOT NULL,
  `dias_acesso` varchar(20) NOT NULL,
  `situacao` tinyint(1) NOT NULL DEFAULT '1',
  `data_registro` datetime NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_fornecedor` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `grupos_usuarios_rotas`
--

DROP TABLE IF EXISTS `grupos_usuarios_rotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos_usuarios_rotas` (
  `id_rota` int NOT NULL,
  `tipo_usuario` int NOT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `data_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `grupos_usuarios_rotas_pk` (`id_rota`,`id_fornecedor`,`tipo_usuario`),
  KEY `fk_grupo_idx` (`tipo_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `integradores`
--

DROP TABLE IF EXISTS `integradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `integradores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `desc` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `dt_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_compradores`
--

DROP TABLE IF EXISTS `log_compradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_compradores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mensagem` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `cnpj` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_cotacoes_api`
--

DROP TABLE IF EXISTS `log_cotacoes_api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_cotacoes_api` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cotacao` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `mensagem` text COLLATE utf8_bin,
  `id_fornecedor` int DEFAULT NULL,
  `tipo` int DEFAULT '1' COMMENT '0 - erro\n1 - sucesso\n2 - warning',
  `data_criacap` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_cotacoes_sintese`
--

DROP TABLE IF EXISTS `log_cotacoes_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_cotacoes_sintese` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data` datetime DEFAULT CURRENT_TIMESTAMP,
  `mensagem` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `id_fornecedor` int DEFAULT NULL,
  `cnpj_fornecedor` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnpj_comprador` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2243695 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_de_para`
--

DROP TABLE IF EXISTS `log_de_para`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_de_para` (
  `id_usuario` int DEFAULT NULL,
  `id_produto` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `integrador` int DEFAULT '2',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `distribuidor` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_envio_automatico`
--

DROP TABLE IF EXISTS `log_envio_automatico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_envio_automatico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL,
  `integrador` varchar(50) COLLATE utf8_bin NOT NULL,
  `cd_cotacao` varchar(50) COLLATE utf8_bin NOT NULL,
  `id_cliente` int NOT NULL,
  `id_estado` int NOT NULL,
  `status` int NOT NULL COMMENT '1 = Enviado, 0 - Nao Enviado',
  `logs` json DEFAULT NULL,
  `xml` longtext COLLATE utf8_bin,
  `configs` json DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_LOG` (`cd_cotacao`,`id_fornecedor`,`id_cliente`,`id_estado`,`integrador`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=817854 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_envio_manual`
--

DROP TABLE IF EXISTS `log_envio_manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_envio_manual` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cd_cotacao` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `produtos` json DEFAULT NULL,
  `restricoes` json DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `integrador` varchar(40) COLLATE utf8_unicode_ci DEFAULT 'SINTESE',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18973 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_ordem_compra`
--

DROP TABLE IF EXISTS `log_ordem_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_ordem_compra` (
  `data` datetime DEFAULT CURRENT_TIMESTAMP,
  `cd_cotacao` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `cnpj_fornecedor` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `message` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log_ordens_compra`
--

DROP TABLE IF EXISTS `log_ordens_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_ordens_compra` (
  `data_registro` datetime DEFAULT NULL,
  `tipo` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loja_prioridade`
--

DROP TABLE IF EXISTS `loja_prioridade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loja_prioridade` (
  `codigo` int DEFAULT NULL,
  `id_loja` int DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mapa_logistico`
--

DROP TABLE IF EXISTS `mapa_logistico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mapa_logistico` (
  `id` int NOT NULL AUTO_INCREMENT,
  `icms` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `classe` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `origem` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `loja1` int DEFAULT NULL,
  `loja2` int DEFAULT NULL,
  `uf` char(5) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marcas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `marca` varchar(35) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21998 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mensagens`
--

DROP TABLE IF EXISTS `mensagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensagens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `remetente` int NOT NULL,
  `destinatario` int NOT NULL,
  `data_leitura` datetime DEFAULT NULL,
  `mensagem` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` int DEFAULT NULL,
  `data_enviado` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modulo_notificacoes`
--

DROP TABLE IF EXISTS `modulo_notificacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modulo_notificacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `mensagem` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `modulo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo` int DEFAULT NULL COMMENT '0 => admin, 1 => fornecedor',
  `ativo` int DEFAULT '0' COMMENT 'ativo = 1, inativo = 0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `motivos_recusa_cotacoes`
--

DROP TABLE IF EXISTS `motivos_recusa_cotacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motivos_recusa_cotacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descricao` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `movimentacao_estoque`
--

DROP TABLE IF EXISTS `movimentacao_estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimentacao_estoque` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL DEFAULT '0',
  `xml_id` int NOT NULL DEFAULT '0',
  `produto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nome_comercial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `codigo` int NOT NULL,
  `apresentacao` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `quantidade` int DEFAULT '0',
  `unidade` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `marca` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `rms` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `qtd_unidade` int DEFAULT '1',
  `lote` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `local` varchar(50) DEFAULT NULL,
  `validade` date DEFAULT NULL,
  `preco` decimal(14,4) DEFAULT '0.0000',
  `preco_unitario` decimal(14,4) DEFAULT '0.0000',
  `preco_unitario_outros_uf` decimal(13,4) NOT NULL DEFAULT '0.0000',
  `quantidade_outros_uf` int DEFAULT '0',
  `estado` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'ES',
  `id_estado` int DEFAULT NULL,
  `controle` int NOT NULL DEFAULT '0',
  `data_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `me_xml_registrados` (`id_fornecedor`,`xml_id`),
  KEY `me_xml_registrados_validade` (`id_fornecedor`,`validade`,`xml_id`),
  KEY `idx_gsys_movimentacao_est` (`id_fornecedor`,`data_update`)
) ENGINE=InnoDB AUTO_INCREMENT=47128124 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `niveis_acesso`
--

DROP TABLE IF EXISTS `niveis_acesso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `niveis_acesso` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nivel_acesso` int unsigned NOT NULL COMMENT 'Define o nível de acesso do usuário, caso seja administrador.\n\nUtilizar valores entre 1 e 99, onde 1 é o nível mais baixo de acesso, onde o usuário não possui acesso a determinado metodo ou view e 99 é o nível mais alto, permitindo que o usuário realize edições e exclusões.',
  `descricao` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notas_fiscais`
--

DROP TABLE IF EXISTS `notas_fiscais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas_fiscais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int DEFAULT NULL,
  `cd_ordem_compra` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero` int DEFAULT NULL,
  `modelo` int DEFAULT NULL,
  `serie` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `chave` int DEFAULT NULL,
  `valor` decimal(12,4) DEFAULT NULL,
  `valor_total_produto` decimal(12,4) DEFAULT NULL,
  `valor_frete` decimal(12,4) DEFAULT NULL,
  `cd_pedido_fornecedor` int DEFAULT NULL,
  `data_emissao` datetime DEFAULT NULL,
  `date_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notas_fiscais_produtos`
--

DROP TABLE IF EXISTS `notas_fiscais_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas_fiscais_produtos` (
  `id_nota_fiscal` int DEFAULT NULL,
  `cd_ordem_compra` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `ean` int DEFAULT NULL,
  `codigo` int DEFAULT NULL,
  `qtd_atendida` int DEFAULT NULL,
  `valor_unitario` decimal(12,4) DEFAULT NULL,
  `valor_total_produto` decimal(12,4) DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '0' COMMENT 'nao lido = 0, lido = 1',
  `token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `envia_email` int DEFAULT '0',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_leitura` datetime DEFAULT NULL,
  `data_envio` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1223003 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oc_dados`
--

DROP TABLE IF EXISTS `oc_dados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_dados` (
  `id_fornecedor` int DEFAULT NULL,
  `nome_fornecedor` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `nome_comprador` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `numero_oc` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `data` date DEFAULT NULL,
  `preco_text` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `total` decimal(12,4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `oc_oncoprod`
--

DROP TABLE IF EXISTS `oc_oncoprod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `oc_oncoprod` (
  `oc` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `loja` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ocs_sintese`
--

DROP TABLE IF EXISTS `ocs_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ocs_sintese` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Dt_Gravacao` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tp_Movimento` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cd_Fornecedor` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cd_Condicao_Pagamento` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cd_Cotacao` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cd_Ordem_Compra` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Dt_Ordem_Compra` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Hr_Ordem_Compra` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Cd_Comprador` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tp_Situacao` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Nm_Aprovador` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Dt_Previsao_Entrega` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Tp_Logradouro` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Nm_Logradouro` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ds_Complemento_Logradouro` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Nm_Bairro` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Nm_Cidade` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Id_Unidade_Federativa` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Nr_Cep` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ds_Observacao` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Telefones_Ordem_Compra` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Tp_Frete` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `pendente` tinyint(1) DEFAULT '0',
  `id_fornecedor` int DEFAULT NULL,
  `id_comprador` int DEFAULT NULL,
  `Dt_Resgate` datetime DEFAULT NULL,
  `Status_OrdemCompra` int DEFAULT NULL,
  `nota` text COLLATE utf8_unicode_ci,
  `chave_nf` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_usuario_resgate` int DEFAULT NULL,
  `data_resgate` timestamp NULL DEFAULT NULL,
  `integrador` int DEFAULT '1',
  `endereco_entrega` text COLLATE utf8_unicode_ci,
  `motivo_cancelamento` text COLLATE utf8_unicode_ci,
  `forma_pagamento` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `termos` text COLLATE utf8_unicode_ci,
  `sequencia` int DEFAULT NULL,
  `prioridade` int DEFAULT '0',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `PHNX_CD_OC_CD_FORN` (`Cd_Ordem_Compra`,`Cd_Comprador`),
  KEY `IDX_GSYS_OCSS_01` (`Cd_Cotacao`,`id_fornecedor`),
  KEY `IDX_GSYS_OCSS_02` (`id`,`Cd_Cotacao`,`id_fornecedor`),
  KEY `IDX_GSYS_OCSS_03` (`id`,`Cd_Cotacao`,`id_fornecedor`,`Cd_Ordem_Compra`),
  KEY `ocs_sintese_id_fornecedor_id_comprador_Dt_Gravacao_index` (`id_fornecedor`,`id_comprador`,`Dt_Gravacao` DESC)
) ENGINE=InnoDB AUTO_INCREMENT=134144 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ocs_sintese_produtos`
--

DROP TABLE IF EXISTS `ocs_sintese_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ocs_sintese_produtos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_ordem_compra` int DEFAULT NULL,
  `Cd_Produto_Comprador` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ds_Unidade_Compra` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Id_Marca` int DEFAULT NULL,
  `Ds_Marca` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Qt_Embalagem` int DEFAULT NULL,
  `Qt_Produto` int DEFAULT NULL,
  `Vl_Preco_Produto` decimal(14,4) DEFAULT NULL,
  `Ds_Observacao_Produto` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Cd_ProdutoERP` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `Cd_Ordem_Compra` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Id_Produto_Sintese` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Id_Sintese` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ds_Produto_Comprador` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` int DEFAULT NULL,
  `ean` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resgatado` int DEFAULT '0',
  `id_confirmacao` int DEFAULT NULL,
  `programacao` text COLLATE utf8_unicode_ci,
  `data_resgate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ocs_sintese_produtos_ocs_sintese_id_fk` (`id_ordem_compra`),
  KEY `ocs_sintese_produtos_codigo_id_index` (`codigo`,`id`),
  CONSTRAINT `ocs_sintese_produtos_ocs_sintese_id_fk` FOREIGN KEY (`id_ordem_compra`) REFERENCES `ocs_sintese` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=317302 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ocs_sintese_status`
--

DROP TABLE IF EXISTS `ocs_sintese_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ocs_sintese_status` (
  `codigo` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `descricao` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancel` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ofertas_b2b_itens`
--

DROP TABLE IF EXISTS `ofertas_b2b_itens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ofertas_b2b_itens` (
  `id_solicitacao` int NOT NULL,
  `id_forma_pagamento` int DEFAULT NULL,
  `valor_maximo` decimal(12,4) DEFAULT NULL,
  `id_prazo_entrega` int DEFAULT NULL,
  `preco_unitario` double(12,4) DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `codigo` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor_interessado` int DEFAULT NULL,
  `id_fornecedor_oferta` int DEFAULT NULL,
  `motivo` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `status` tinyint DEFAULT '0',
  `id_usuario` int DEFAULT NULL,
  `id_venda_diferenciada` int DEFAULT NULL,
  `aprovado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ordens_compra`
--

DROP TABLE IF EXISTS `ordens_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordens_compra` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int unsigned NOT NULL,
  `id_cliente` int unsigned NOT NULL,
  `id_status_ordem_compra` int unsigned NOT NULL,
  `id_pedido` int unsigned NOT NULL,
  `id_tipo_venda` int unsigned NOT NULL COMMENT 'ID do tipo de venda ma tabela "tipos_venda"',
  `tipo_telefone` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(55) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_estado` int unsigned NOT NULL,
  `chave` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ordem_compra` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Código ordem de compra do fornecedor',
  `valor_total` decimal(10,4) NOT NULL,
  `parcelas` int unsigned NOT NULL,
  `codigo_ordem_compra` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Código da ordem de compra da Sintese',
  `tipo_movimento` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_gravacao` datetime DEFAULT NULL COMMENT 'Data da ordem de compra',
  `observacao` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ramal` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `numero_telefone` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `uf` varchar(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cep` varchar(9) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cidade` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `bairro` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `complemento_logradouro` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_logradouro` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_logradouro` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_frete` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nome_aprovador` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_situacao` int DEFAULT NULL,
  `data_previsao_entrega` datetime DEFAULT NULL,
  `data_ordem_compra` date NOT NULL,
  `horario_ordem_compra` time DEFAULT NULL,
  `ordens_compracol` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_cotacao` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `codigo_fornecedor` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo_comprador` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `condicao_pagamento` int DEFAULT NULL,
  `data_emissao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `codigo_rastreio` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `transportadora` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `nota_fiscal` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_nf` date DEFAULT NULL,
  `valor_nf` decimal(14,4) DEFAULT '0.0000',
  `comprador` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'cnpj do comprador na nf',
  PRIMARY KEY (`id`),
  KEY `ordens_compra_cliente_idx` (`id_cliente`),
  KEY `ordens_compra_fornecedor_idx` (`id_fornecedor`),
  KEY `ordens_compra_pedido_idx` (`id_pedido`),
  KEY `ordens_compra_status_oc_idx` (`id_status_ordem_compra`),
  KEY `ordens_compra_tipo_venda_idx` (`id_tipo_venda`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ordens compra: Tabela contendo todas as ordens de compra registradas após a finalização dos pedidos.\n\nCada ordem de compra deverá ser gerada individualmente para cada CNPJ do pedido.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ordens_compra_sintese`
--

DROP TABLE IF EXISTS `ordens_compra_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordens_compra_sintese` (
  `id_fornecedor` int NOT NULL,
  `comprador` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cnpj_comprador` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `produto` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `unidade` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `marca` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `qtd` int DEFAULT NULL,
  `preco` decimal(12,4) DEFAULT NULL,
  `oc` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` date DEFAULT NULL,
  `status` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `total` decimal(14,2) DEFAULT NULL,
  `cd_cotacao` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_carrinho` int unsigned NOT NULL,
  `id_cliente` int unsigned NOT NULL COMMENT 'ID do cliente na tabela "usuarios"',
  `id_fornecedor` int unsigned NOT NULL,
  `id_forma_pagamento_fornecedor` int unsigned DEFAULT '0' COMMENT 'ID de forma de pagamento registro para o cliente pelo fornecedor, caso não esteja setado nenhuma forma de pagamento especifica entre o fornecedor e cliente, o valor padrão será 0, configurando assim no controller a forma de pagamento à vista',
  `id_prazo_entrega` int unsigned NOT NULL DEFAULT '0' COMMENT 'ID do prazo de entrega definida por UF pelo fornecedor na tabela "prazos_entrega"',
  `id_tipo_venda` int unsigned NOT NULL DEFAULT '0' COMMENT 'ID do tipo de venda na tabela "tipos_venda"',
  `token` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Token único gerado a partir da data de compra + id do usuário cliente + id do carrinho.',
  `valor_total` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `status` tinyint DEFAULT '0',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `pedidos_carrinho_idx` (`id_carrinho`),
  KEY `pedidos_fornecedor_idx` (`id_fornecedor`),
  KEY `pedidos_forma_pagamento_fornecedor_idx` (`id_forma_pagamento_fornecedor`),
  KEY `pedidos_prazo_entrega_idx` (`id_prazo_entrega`),
  KEY `pedidos_cliente_idx` (`id_cliente`),
  KEY `pedidos_tipo_venda_idx` (`id_tipo_venda`)
) ENGINE=InnoDB AUTO_INCREMENT=6208 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Pedidos: Tabela que registra todos os pedidos separador pelo ID do fornecedor. \n\nOs pedidos são individuais para cada fornecedor, porém, para o cliente ele será único, nesse caso, será gerado um token de identificação única, relacionando os pedidos, na visão do cliente essa divisão será transparente.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos_produtos_fornecedores`
--

DROP TABLE IF EXISTS `pedidos_produtos_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos_produtos_fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int NOT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_fornecedor` int NOT NULL,
  `id_carrinho` int unsigned NOT NULL COMMENT 'ID do carrinho na tabela "carrinhos"',
  `id_produto` int unsigned NOT NULL COMMENT 'ID do produto na tabela "produtos_fornecedores"',
  `quantidade` int NOT NULL COMMENT 'Quantidade do produto selecionado à venda,a quandtidade não pode ser maior que "produtos_fornecedores"."qtd"',
  `preco_unidade` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Em Analise',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `justificativa` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `produtos_carrinho_carrinho_idx` (`id_carrinho`),
  KEY `produtos_carrinho_produto_fornecedor_idx` (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Produtos Carrinho: É o registro de todos os produtos inseridos em seus respectivos carrinhos.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos_representantes`
--

DROP TABLE IF EXISTS `pedidos_representantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos_representantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_representante` int DEFAULT NULL,
  `id_comprador` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `id_forma_pagamento` int DEFAULT NULL,
  `id_prazo_entrega` int DEFAULT NULL,
  `condicao_pagamento` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `prazo_entrega` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `valor_minimo` decimal(9,4) DEFAULT NULL,
  `data_abertura` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_fechamento` datetime DEFAULT NULL,
  `situacao` tinyint DEFAULT '1',
  `comissao` decimal(9,2) DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uf_comprador` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `prioridade` int DEFAULT '9',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pedidos_representantes_produtos`
--

DROP TABLE IF EXISTS `pedidos_representantes_produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos_representantes_produtos` (
  `id_pedido` int DEFAULT NULL,
  `cd_produto_fornecedor` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `preco_unidade` decimal(12,4) DEFAULT NULL,
  `quantidade_solicitada` int DEFAULT NULL,
  `desconto` decimal(9,2) DEFAULT NULL,
  `preco_desconto` decimal(12,4) DEFAULT NULL,
  `total` decimal(10,4) DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `motivo` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `faturado` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfis`
--

DROP TABLE IF EXISTS `perfis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_rotas` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prazos_entrega`
--

DROP TABLE IF EXISTS `prazos_entrega`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prazos_entrega` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int unsigned NOT NULL COMMENT 'ID do fornecedor na tabela "usuarios"',
  `id_cliente` int DEFAULT NULL,
  `id_estado` int unsigned DEFAULT NULL COMMENT 'ID do estado na tabela "estados"',
  `prazo` int unsigned NOT NULL,
  `id_tipo_venda` int NOT NULL DEFAULT '0',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `prazos_entrega_fornecedor_idx` (`id_fornecedor`),
  KEY `prazos_entrega_estado_idx` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=1288 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Prazos entrega: Tabela com o registro de todos os prazos de entrega registrado pelos fornecedores, o prazo de entrega deverá ser definido por estado.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preco_medio`
--

DROP TABLE IF EXISTS `preco_medio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preco_medio` (
  `produto` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `marca` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `quantidade_embalagem` int DEFAULT NULL,
  `preco_medio` decimal(12,4) DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preco_minimo_distribuidores`
--

DROP TABLE IF EXISTS `preco_minimo_distribuidores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `preco_minimo_distribuidores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` int DEFAULT NULL,
  `produto` text COLLATE utf8_bin,
  `unidade` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `marca` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `preco` decimal(10,4) DEFAULT NULL,
  `qtd` int DEFAULT NULL,
  `cotacao` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `data_cot` date DEFAULT NULL,
  `hospital` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `fornecedor` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=291594 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `precos_especiais`
--

DROP TABLE IF EXISTS `precos_especiais`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `precos_especiais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produto` int DEFAULT NULL,
  `codigo` int DEFAULT NULL,
  `valor` decimal(14,4) DEFAULT '0.0000',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_estado` int DEFAULT NULL,
  `tipo` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `precos_fornecedores`
--

DROP TABLE IF EXISTS `precos_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `precos_fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(12) DEFAULT NULL,
  `id_produto` int NOT NULL DEFAULT '0',
  `id_marca` int NOT NULL DEFAULT '0',
  `id_fornecedor` int NOT NULL DEFAULT '0',
  `id_estado` int NOT NULL DEFAULT '0',
  `id_tipo_venda` int NOT NULL DEFAULT '0',
  `ativo` int NOT NULL DEFAULT '1',
  `quantidade` int DEFAULT NULL,
  `unidade` varchar(2) DEFAULT NULL,
  `quantidade_unidade` int DEFAULT NULL,
  `lote` varchar(12) DEFAULT NULL,
  `validade` date DEFAULT NULL,
  `preco` decimal(10,4) DEFAULT NULL,
  `preco_unidade` decimal(10,4) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `data_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `codart` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=136295 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `precos_medicamentos`
--

DROP TABLE IF EXISTS `precos_medicamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `precos_medicamentos` (
  `codigo` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `produto` text COLLATE utf8_bin,
  `unidade` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `marca` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `preco` decimal(10,4) DEFAULT NULL,
  `qtd` int DEFAULT NULL,
  `cotacao` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `hospital` text COLLATE utf8_bin,
  `fornecedor` text COLLATE utf8_bin,
  `id_sintese` varchar(20) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produto_teste`
--

DROP TABLE IF EXISTS `produto_teste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produto_teste` (
  `codigo` int DEFAULT NULL,
  `produtos` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_categoria` int unsigned NOT NULL COMMENT 'ID da categoria na tabelas "categorias_produtos"',
  `produto_descricao` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descrição completa do produto',
  `rms` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'RMS do medicamento',
  `linha_hospitalar` int NOT NULL DEFAULT '0',
  `linha_farma` int NOT NULL DEFAULT '0',
  `linha_odonto` int NOT NULL DEFAULT '0',
  `id_forma` int DEFAULT NULL,
  `imagem_produto` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Imagem do produto armazenada em fileserver /img-produtos',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de cadastro',
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data da última atualização',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34142 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Produtos: Tabela contendo diversos produtos padronizados e parametrizados através do portal de administração';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_aguardando_sintese`
--

DROP TABLE IF EXISTS `produtos_aguardando_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_aguardando_sintese` (
  `codigo` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `marca` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `data_registros` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_anvisa`
--

DROP TABLE IF EXISTS `produtos_anvisa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_anvisa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `substancia` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `laboratorio` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `codigo_ggrem` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `registro` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ean1` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ean2` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `produto` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `apresentacao` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pf_sem_impostos` decimal(10,2) NOT NULL,
  `pf0` decimal(10,2) NOT NULL,
  `pf12` decimal(10,2) NOT NULL,
  `pf17` decimal(10,2) NOT NULL,
  `pf17alc` decimal(10,2) NOT NULL,
  `pf175` decimal(10,2) NOT NULL,
  `pf175alc` decimal(10,2) NOT NULL,
  `pf18` decimal(10,2) NOT NULL,
  `pf18alc` decimal(10,2) NOT NULL,
  `pf20` decimal(10,2) NOT NULL,
  `pmc0` decimal(10,2) NOT NULL,
  `pmc12` decimal(10,2) NOT NULL,
  `pmc17` decimal(10,2) NOT NULL,
  `pmc17alc` decimal(10,2) NOT NULL,
  `pmc175` decimal(10,2) NOT NULL,
  `pmc175alc` decimal(10,2) NOT NULL,
  `pmc18` decimal(10,2) NOT NULL,
  `pmc18alc` decimal(10,2) NOT NULL,
  `pmc20` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25180 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_carrinho`
--

DROP TABLE IF EXISTS `produtos_carrinho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_carrinho` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `id_carrinho` int unsigned NOT NULL COMMENT 'ID do carrinho na tabela "carrinhos"',
  `id_produto_fornecedor` int unsigned NOT NULL COMMENT 'ID do produto na tabela "produtos_fornecedores"',
  `quantidade` int NOT NULL COMMENT 'Quantidade do produto selecionado à venda,a quandtidade não pode ser maior que "produtos_fornecedores"."qtd"',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `produtos_carrinho_carrinho_idx` (`id_carrinho`),
  KEY `produtos_carrinho_produto_fornecedor_idx` (`id_produto_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Produtos Carrinho: É o registro de todos os produtos inseridos em seus respectivos carrinhos.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_catalogo`
--

DROP TABLE IF EXISTS `produtos_catalogo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_catalogo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` int DEFAULT NULL,
  `codigo_externo` varchar(20) DEFAULT NULL,
  `apresentacao` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `marca` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `descricao` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nome_comercial` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `preco_unidade` decimal(12,4) DEFAULT '0.0000',
  `id_marca` int NOT NULL DEFAULT '0',
  `id_fornecedor` int NOT NULL DEFAULT '0',
  `ativo` int NOT NULL DEFAULT '0',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `preco` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `aprovado` int NOT NULL DEFAULT '0',
  `bloqueado` tinyint DEFAULT '0',
  `rms` varchar(50) DEFAULT NULL,
  `ean` varchar(50) DEFAULT NULL,
  `ncm` varchar(20) DEFAULT NULL,
  `quantidade_unidade` int DEFAULT NULL,
  `unidade` varchar(50) DEFAULT NULL,
  `b2b` tinyint DEFAULT '0',
  `ocultar_de_para` int DEFAULT NULL,
  `classe` varchar(20) DEFAULT NULL,
  `origem` varchar(20) DEFAULT NULL,
  `id_loja_saida` int DEFAULT NULL,
  `pharma` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_PFC_01` (`bloqueado`),
  KEY `IDX_GSYS_PFC_02` (`bloqueado`,`id_fornecedor`),
  KEY `IDX_GSYS_PFC_03` (`codigo`,`id_fornecedor`,`id_marca`),
  KEY `IDX_GSYS_PFC_04` (`id_fornecedor`),
  KEY `IDX_CD_ID_FORN` (`codigo`,`id_fornecedor`),
  KEY `IDX_GSYS_PFC_05` (`codigo`),
  KEY `IDX_GSYS_COMP_06` (`codigo`,`nome_comercial`,`descricao`,`apresentacao`),
  KEY `IDX_GYS_PC_01` (`bloqueado`,`id_fornecedor`),
  KEY `IDX_GYS_PC_02` (`bloqueado`,`id_fornecedor`,`ativo`)
) ENGINE=InnoDB AUTO_INCREMENT=176597 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_clientes_depara`
--

DROP TABLE IF EXISTS `produtos_clientes_depara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_clientes_depara` (
  `id_produto_sintese` int DEFAULT NULL,
  `cd_produto` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `id_integrador` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_cmed`
--

DROP TABLE IF EXISTS `produtos_cmed`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_cmed` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produto` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `apresentacao` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `nome` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `GSYS_CMED_TESTE01` (`nome`),
  CONSTRAINT `produtos_cmed_produtos_cmed_principios_id_produto_fk` FOREIGN KEY (`id`) REFERENCES `produtos_cmed_principios` (`id_produto`)
) ENGINE=InnoDB AUTO_INCREMENT=52620 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_cmed_principios`
--

DROP TABLE IF EXISTS `produtos_cmed_principios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_cmed_principios` (
  `id_produto` int NOT NULL,
  `descricao` varchar(255) COLLATE utf8_bin NOT NULL,
  KEY `produtos_cmed_principios_produtos_cmed_id_fk` (`id_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_cotacoes`
--

DROP TABLE IF EXISTS `produtos_cotacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_cotacoes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_produto_fornecedor` int unsigned NOT NULL COMMENT 'ID do produto na tabela estoque"',
  `id_cotacao` int unsigned NOT NULL COMMENT 'ID da cotação na tabela "cotacoes"',
  `id_uf_fornecedor_oferta` int unsigned NOT NULL,
  `id_outra_marca` int unsigned NOT NULL COMMENT 'ID da marca na tabela "marcas"',
  `id_fornecedor` int unsigned NOT NULL COMMENT 'ID do fornecedor na tabela "usuarios"',
  `id_prazo_entrega` int unsigned NOT NULL COMMENT 'Prazo de entrega relacionado com o Fronecedor do Produto solicitado',
  `id_sintese` int unsigned NOT NULL,
  `controle` tinyint unsigned NOT NULL DEFAULT '0' COMMENT 'Define se o produto foi enviado na cotação;\n0 = não foi enviado;\n1 = enviado;\n3 = não enviar (exemplo: cotação realizada pela Pharmanexo que vem na cotação);',
  `rejeitado` tinyint unsigned NOT NULL DEFAULT '0' COMMENT 'Define se o produto da cotação foi rejeitado; 0 = Não rejeitado; 1 = Rejeitado. Caso seja rejeitado, é necessário incluir motivo de rejeição na tabela "rejeicoes_cotacoes".',
  `qtd_embalagem` int unsigned NOT NULL,
  `preco_cotacao` decimal(10,4) unsigned NOT NULL,
  `preco_marca` decimal(10,4) unsigned NOT NULL,
  `preco_origem_marca` decimal(10,4) unsigned NOT NULL,
  `preco_outra_marca` decimal(10,4) unsigned NOT NULL,
  `preco_origem_outra_marca` decimal(10,4) unsigned NOT NULL,
  `codigo_produto` int unsigned NOT NULL COMMENT 'ID enviado pela integração (ID de registro no banco do parceiro)',
  `cnpj_fornecedor_oferta` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nome_fornecedor_oferta` varchar(85) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `descricao` varchar(85) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `marca_oferta` varchar(85) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `qtd_solicitada` int unsigned NOT NULL,
  `data_solicitacao_comprador_ext` datetime NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Produtos Cotação: Tabela de registro de todos os itens que foram submetidos para cotação.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_fornecedores`
--

DROP TABLE IF EXISTS `produtos_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` int DEFAULT NULL,
  `apresentacao` varchar(84) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `marca` varchar(28) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `descricao` varchar(92) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nome_comercial` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `unidade` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `quantidade_unidade` int NOT NULL DEFAULT '0',
  `rms` varchar(30) NOT NULL,
  `estoque` int NOT NULL,
  `preco_unidade` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `id_sintese` int NOT NULL,
  `id_produto` int NOT NULL,
  `id_marca` int NOT NULL DEFAULT '0',
  `id_fornecedor` int NOT NULL DEFAULT '0',
  `id_estado` int NOT NULL DEFAULT '0',
  `id_tipo_venda` int NOT NULL DEFAULT '0',
  `ativo` int NOT NULL DEFAULT '0',
  `pf0` decimal(10,4) DEFAULT NULL,
  `pf12` decimal(10,4) DEFAULT NULL,
  `pf17` decimal(10,4) DEFAULT NULL,
  `pf175` decimal(10,4) DEFAULT NULL,
  `pf18` decimal(10,4) DEFAULT NULL,
  `pf20` decimal(10,4) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `contra_proposta` tinyint NOT NULL DEFAULT '0',
  `porcentagem_campanha` decimal(5,2) NOT NULL DEFAULT '0.00',
  `preco` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `venda_parcelada` int NOT NULL DEFAULT '0',
  `qtde_min_pedido` int NOT NULL DEFAULT '0',
  `qtde_total_venda` int NOT NULL DEFAULT '0',
  `aprovado` int NOT NULL DEFAULT '0',
  `valor_final_revenda` int NOT NULL DEFAULT '0',
  `motivo_recusa` varchar(255) DEFAULT NULL,
  `destaque` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `produtos_fornecedores_descricao_apresentacao_index` (`descricao`,`apresentacao`)
) ENGINE=InnoDB AUTO_INCREMENT=8630 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_fornecedores_sintese`
--

DROP TABLE IF EXISTS `produtos_fornecedores_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_fornecedores_sintese` (
  `id_sintese` int DEFAULT NULL,
  `id_pfv` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `cd_produto` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_catalogo` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `validado` int DEFAULT NULL,
  KEY `IDX_GSYS_PFVSN_01` (`id_pfv`,`id_sintese`),
  KEY `IDX_GSYS_PFS_02` (`cd_produto`,`id_fornecedor`),
  KEY `IDX_GSYS_PFS_03` (`cd_produto`),
  KEY `IDX_GSYS_PFS_04` (`id_fornecedor`),
  KEY `IDX_PHM_01` (`id_sintese`,`id_fornecedor`),
  KEY `IDX_GYS_FST_01` (`cd_produto`,`id_fornecedor`),
  KEY `IDX_GYS_PFS_01` (`cd_produto`,`id_fornecedor`),
  KEY `IDX_GYS_PFS_02` (`id_fornecedor`,`id_sintese`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_fornecedores_validades`
--

DROP TABLE IF EXISTS `produtos_fornecedores_validades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_fornecedores_validades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` int DEFAULT NULL,
  `apresentacao` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `marca` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `descricao` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nome_comercial` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `unidade` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `quantidade_unidade` int NOT NULL DEFAULT '0',
  `rms` varchar(30) DEFAULT NULL,
  `lote` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `validade` date DEFAULT NULL,
  `estoque` int NOT NULL DEFAULT '0',
  `preco_unidade` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `id_sintese` int NOT NULL DEFAULT '0',
  `id_produto` int NOT NULL DEFAULT '0',
  `id_marca` int NOT NULL DEFAULT '0',
  `id_fornecedor` int NOT NULL DEFAULT '0',
  `id_estado` int NOT NULL DEFAULT '0',
  `id_tipo_venda` int NOT NULL DEFAULT '0',
  `ativo` int NOT NULL DEFAULT '0',
  `pf0` decimal(10,4) DEFAULT NULL,
  `pf12` decimal(10,4) DEFAULT NULL,
  `pf17` decimal(10,4) DEFAULT NULL,
  `pf175` decimal(10,4) DEFAULT NULL,
  `pf18` decimal(10,4) DEFAULT NULL,
  `pf20` decimal(10,4) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `contra_proposta` tinyint NOT NULL DEFAULT '0',
  `porcentagem_campanha` decimal(5,2) NOT NULL DEFAULT '0.00',
  `preco` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `venda_parcelada` int NOT NULL DEFAULT '0',
  `qtde_min_pedido` int NOT NULL DEFAULT '0',
  `qtde_total_venda` int NOT NULL DEFAULT '0',
  `aprovado` int NOT NULL DEFAULT '0',
  `valor_final_revenda` int NOT NULL DEFAULT '0',
  `motivo_recusa` varchar(255) DEFAULT NULL,
  `destaque` int NOT NULL DEFAULT '0',
  `aguardando_sintese` tinyint DEFAULT NULL,
  `bloqueado` tinyint DEFAULT '0',
  `sem_depara` tinyint DEFAULT NULL,
  `ean` varchar(20) DEFAULT NULL,
  `ncm` varchar(10) DEFAULT NULL,
  `id_catalogo` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_PFV_01` (`id_produto`,`id_sintese`,`bloqueado`),
  KEY `IDX_GSYS_PFV_02` (`id_produto`),
  KEY `IDX_GSYS_PFV_03` (`bloqueado`),
  KEY `IDX_GSYS_PFV_04` (`bloqueado`,`id_fornecedor`),
  KEY `IDX_GSYS_PFV_05` (`codigo`,`id_fornecedor`,`id_marca`),
  KEY `IDX_GSYS_PFV_06` (`id_fornecedor`,`id_sintese`),
  KEY `IDX_GSYS_PFV_07` (`validade`),
  KEY `IDX_GSYS_PFV_08` (`id_fornecedor`,`estoque`),
  KEY `IDX_GSYS_PFV_09` (`id_fornecedor`,`id_estado`,`validade`),
  KEY `IDX_GSYS_PFV_10` (`id_fornecedor`,`id_sintese`,`id_produto`),
  KEY `IDX_GSYS_PFV_11` (`id_fornecedor`,`id_sintese`,`id_produto`,`bloqueado`),
  KEY `IDX_GSYS_PFV_12` (`id_estado`,`validade`)
) ENGINE=InnoDB AUTO_INCREMENT=26062555 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_global`
--

DROP TABLE IF EXISTS `produtos_global`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_global` (
  `ean` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `codforn` int DEFAULT NULL,
  `estoque` int DEFAULT NULL,
  `unidade_venda` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `cod_produto` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `desc_produto` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `qtd_embalagem` int DEFAULT NULL,
  `multiplo` int DEFAULT NULL,
  `validade` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `preco_1` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `preco_2` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `preco_3` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `regiao` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `uf` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `embalagem` varchar(10) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_lote`
--

DROP TABLE IF EXISTS `produtos_lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_lote` (
  `codigo` int NOT NULL,
  `lote` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `local` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int NOT NULL,
  `estoque` int NOT NULL,
  `validade` date NOT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `fixo` int DEFAULT '0',
  KEY `lote_produtos__estoque` (`estoque`,`lote`,`validade`),
  KEY `lote_produtos_codigo` (`codigo`,`id_fornecedor`),
  KEY `GSYS_IDX_PL_01` (`id_fornecedor`,`validade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_marca_sintese`
--

DROP TABLE IF EXISTS `produtos_marca_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_marca_sintese` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produto` int NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `id_grupo` int DEFAULT NULL,
  `grupo` varchar(255) DEFAULT NULL,
  `id_sintese` int NOT NULL,
  `complemento` varchar(203) DEFAULT NULL,
  `id_marca` int DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `apresentacao` varchar(255) DEFAULT NULL,
  `rms` varchar(255) DEFAULT NULL,
  `photo_id` int DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` int DEFAULT '1',
  `id_usuario_exclusao` int DEFAULT NULL,
  `motivo_exclusao` text,
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_PMS_01` (`id_sintese`),
  KEY `produtos_marca_sintese_id_produto_index` (`id_produto`),
  KEY `produtos_marca_sintese_descricao_index` (`descricao`),
  KEY `IDX_GYS_PFS_03` (`id_produto`,`id_sintese`)
) ENGINE=InnoDB AUTO_INCREMENT=306476 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_materiais_sintese`
--

DROP TABLE IF EXISTS `produtos_materiais_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_materiais_sintese` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `cd_produto_comprador` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `ds_produto_comprador` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `id_produto` int DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98768 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_ofertados_sintese`
--

DROP TABLE IF EXISTS `produtos_ofertados_sintese`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_ofertados_sintese` (
  `codigo` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `produtos` text COLLATE utf8_bin,
  `unidade` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `marca` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `valor` decimal(12,4) DEFAULT NULL,
  `qtd_embalagem` int DEFAULT NULL,
  `qtd_solicitada` int DEFAULT NULL,
  `cotacao` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `ordem_compra` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `fornecedor` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `data_cotacao` datetime DEFAULT NULL,
  `hospital` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_pre_depara`
--

DROP TABLE IF EXISTS `produtos_pre_depara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_pre_depara` (
  `id_produto` int DEFAULT NULL,
  `id_sintese` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_pfv` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `cd_produto` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_catalogo` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `integrador` int DEFAULT NULL,
  KEY `IDX_GSYS_PFS_03` (`cd_produto`),
  KEY `IDX_GSYS_PFVSN_01` (`id_pfv`,`id_sintese`),
  KEY `IDX_GSYS_PFS_02` (`cd_produto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_pre_match`
--

DROP TABLE IF EXISTS `produtos_pre_match`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_pre_match` (
  `id_sintese` int DEFAULT NULL,
  `id_pfv` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `cd_produto` int DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_catalogo` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  KEY `IDX_GSYS_PFS_02` (`cd_produto`,`id_fornecedor`),
  KEY `IDX_GSYS_PFS_03` (`cd_produto`),
  KEY `IDX_GSYS_PFS_04` (`id_fornecedor`),
  KEY `IDX_GSYS_PFVSN_01` (`id_pfv`,`id_sintese`),
  KEY `IDX_PHM_01` (`id_sintese`,`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_preco`
--

DROP TABLE IF EXISTS `produtos_preco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_preco` (
  `codigo` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `id_estado` int DEFAULT NULL,
  `preco_unitario` decimal(12,4) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `produtos_preco` (`codigo`,`id_fornecedor`,`id_estado`),
  KEY `produtos_preco__codigo` (`codigo`,`id_fornecedor`),
  KEY `produtos_preco_id_fornecedor_index` (`id_fornecedor`),
  KEY `produtos_preco__estados` (`id_estado`),
  KEY `IDX_GSYS_PP_01` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PP_02` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PP_03` (`codigo`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PP_04` (`id_fornecedor`,`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`adm_pharmanexo`@`%`*/ /*!50003 TRIGGER `trg_produtos_preco_max` BEFORE INSERT ON `produtos_preco` FOR EACH ROW BEGIN

    DECLARE codigo integer;
    DECLARE id_fornecedor integer;
    DECLARE checkPrice float;

    SET @codigo := NEW.codigo;
    SET @id_fornecedor := NEW.id_fornecedor;

    IF (NEW.id_estado IS NOT NULL)
    THEN

        SELECT x.preco_unitario
        INTO @checkPrice
        FROM pharmanexo.produtos_preco_max x
        WHERE x.codigo = @codigo
          AND x.id_fornecedor = @id_fornecedor
          AND x.id_estado = NEW.id_estado;

    ELSE

        SELECT x.preco_unitario
        INTO @checkPrice
        FROM pharmanexo.produtos_preco_max x
        WHERE x.codigo = @codigo
          AND x.id_fornecedor = @id_fornecedor
          AND x.id_estado IS NULL;

    END IF;

    IF (@checkPrice IS NOT NULL)
    THEN

        DELETE
        FROM pharmanexo.produtos_preco_max x
        WHERE x.codigo = @codigo
          AND x.id_fornecedor = @id_fornecedor
          AND (x.id_estado = NEW.id_estado
            OR x.id_estado IS NULL);

    END IF;

    INSERT INTO pharmanexo.produtos_preco_max
        (codigo, id_fornecedor, id_estado, preco_unitario, data_criacao)
    VALUES (@codigo, @id_fornecedor, NEW.id_estado, NEW.preco_unitario, NEW.data_criacao);

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `produtos_preco_max`
--

DROP TABLE IF EXISTS `produtos_preco_max`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_preco_max` (
  `codigo` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `id_estado` int DEFAULT NULL,
  `preco_unitario` decimal(12,4) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX_GSYS_PP_01` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PP_02` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PP_03` (`codigo`,`id_estado`,`data_criacao`),
  KEY `produtos_preco_max` (`codigo`,`id_fornecedor`,`id_estado`),
  KEY `produtos_preco_max__codigo` (`codigo`,`id_fornecedor`),
  KEY `produtos_preco_max__estados` (`id_estado`),
  KEY `produtos_preco_max_id_fornecedor_index` (`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_preco_max_oncoprod`
--

DROP TABLE IF EXISTS `produtos_preco_max_oncoprod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_preco_max_oncoprod` (
  `codigo` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `id_estado` int DEFAULT NULL,
  `preco_unitario` decimal(12,4) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX_GSYS_PP_01` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PP_02` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PP_03` (`codigo`,`id_estado`,`data_criacao`),
  KEY `produtos_preco_max_oncoprod` (`codigo`,`id_fornecedor`,`id_estado`),
  KEY `produtos_preco_max_oncoprod__codigo` (`codigo`,`id_fornecedor`),
  KEY `produtos_preco_max_oncoprod__estados` (`id_estado`),
  KEY `produtos_preco_max_oncoprod_id_fornecedor_index` (`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_preco_oncoprod`
--

DROP TABLE IF EXISTS `produtos_preco_oncoprod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_preco_oncoprod` (
  `codigo` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `id_estado` int DEFAULT NULL,
  `icms` decimal(5,2) DEFAULT NULL,
  `preco_unitario` decimal(12,4) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX_GSYS_PPO_01` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PPO_02` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PPO_03` (`codigo`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PPO_04` (`id_fornecedor`,`id_estado`),
  KEY `produtos_preco_oncoprod` (`codigo`,`id_fornecedor`,`id_estado`),
  KEY `produtos_preco_oncoprod__codigo` (`codigo`,`id_fornecedor`),
  KEY `produtos_preco_oncoprod__estados` (`id_estado`),
  KEY `produtos_preco_oncoprod__icms` (`icms`),
  KEY `produtos_preco_oncoprod_id_fornecedor_index` (`id_fornecedor`),
  KEY `IDX_GYS_PP_01` (`data_criacao`,`id_estado`,`id_fornecedor`,`codigo`),
  KEY `IDX_GYS_PP_02` (`codigo`,`id_fornecedor`),
  KEY `produtos_preco_oncoprod__index_05` (`codigo`,`id_fornecedor`,`data_criacao` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`adm_pharmanexo`@`%`*/ /*!50003 TRIGGER `trg_produtos_preco_max_oncoprod` BEFORE INSERT ON `produtos_preco_oncoprod` FOR EACH ROW BEGIN

    DECLARE codigo integer;
    DECLARE id_fornecedor integer;
    DECLARE checkPrice float;

    SET @codigo := NEW.codigo;
    SET @id_fornecedor := NEW.id_fornecedor;

    IF (NEW.id_estado IS NOT NULL)
    THEN

        SELECT x.preco_unitario
        INTO @checkPrice
        FROM pharmanexo.produtos_preco_max x
        WHERE x.codigo = @codigo
          AND x.id_fornecedor = @id_fornecedor
          AND x.id_estado = NEW.id_estado;

    ELSE

        SELECT x.preco_unitario
        INTO @checkPrice
        FROM pharmanexo.produtos_preco_max x
        WHERE x.codigo = @codigo
          AND x.id_fornecedor = @id_fornecedor
          AND x.id_estado IS NULL;

    END IF;

    IF (@checkPrice IS NOT NULL)
    THEN

        DELETE
        FROM pharmanexo.produtos_preco_max x
        WHERE x.codigo = @codigo
          AND x.id_fornecedor = @id_fornecedor
          AND (x.id_estado = NEW.id_estado
            OR x.id_estado IS NULL);

    END IF;

    INSERT INTO pharmanexo.produtos_preco_max_oncoprod
    (codigo, id_fornecedor, id_estado, preco_unitario, data_criacao)
    VALUES (@codigo, @id_fornecedor, NEW.id_estado, NEW.preco_unitario, NEW.data_criacao);

END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `produtos_preco_oncoprod_historico`
--

DROP TABLE IF EXISTS `produtos_preco_oncoprod_historico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_preco_oncoprod_historico` (
  `codigo` int NOT NULL,
  `id_fornecedor` int NOT NULL,
  `id_estado` int DEFAULT NULL,
  `icms` decimal(5,2) DEFAULT NULL,
  `preco_unitario` decimal(12,4) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX_GSYS_PPO_011` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PPO_022` (`codigo`,`id_fornecedor`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PPO_033` (`codigo`,`id_estado`,`data_criacao`),
  KEY `IDX_GSYS_PPO_044` (`id_fornecedor`,`id_estado`),
  KEY `produtos_preco_oncoprod_historico` (`codigo`,`id_fornecedor`,`id_estado`),
  KEY `produtos_preco_oncoprod_historico__codigo` (`codigo`,`id_fornecedor`),
  KEY `produtos_preco_oncoprod_historico__estados` (`id_estado`),
  KEY `produtos_preco_oncoprod_historico__icms` (`icms`),
  KEY `produtos_preco_oncoprod_historico_id_fornecedor_index` (`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_precos_regiao`
--

DROP TABLE IF EXISTS `produtos_precos_regiao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_precos_regiao` (
  `codigo` int DEFAULT NULL,
  `preco_1` decimal(14,4) DEFAULT NULL,
  `preco_2` decimal(14,4) DEFAULT NULL,
  `preco_3` decimal(14,4) DEFAULT NULL,
  `uf` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `regiao` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produtos_sem_estoque`
--

DROP TABLE IF EXISTS `produtos_sem_estoque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produtos_sem_estoque` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cd_cotacao` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` int DEFAULT NULL,
  `id_produto` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=833748 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rel_prod_sem_depara`
--

DROP TABLE IF EXISTS `rel_prod_sem_depara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rel_prod_sem_depara` (
  `id` int NOT NULL,
  `id_produto_sintese` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `cd_produto_comprador` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `ds_produto_comprador` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `ds_unidade_compra` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `ds_complementar` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `qt_produto_total` int DEFAULT NULL,
  `sn_item_contrato` int DEFAULT NULL,
  `sn_permite_exibir` int DEFAULT NULL,
  `cd_cotacao` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `uf` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `codigo` varchar(20) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relatorio_produto_cotados`
--

DROP TABLE IF EXISTS `relatorio_produto_cotados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `relatorio_produto_cotados` (
  `cnpj` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `comprador` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `cotacao` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `produto` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_sintese` int DEFAULT NULL,
  `codigo` int DEFAULT NULL,
  `qtd` int DEFAULT NULL,
  `respondido` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `representantes`
--

DROP TABLE IF EXISTS `representantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(50) NOT NULL,
  `telefone_comercial` varchar(50) DEFAULT NULL,
  `telefone_celular` varchar(50) DEFAULT NULL,
  `rua` varchar(100) DEFAULT NULL,
  `numero` int DEFAULT NULL,
  `complemento` varchar(110) DEFAULT NULL,
  `bairro` varchar(70) DEFAULT NULL,
  `municipio` varchar(50) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `linha_atuacao_id` int DEFAULT NULL,
  `area_cobertura` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `comissao` int DEFAULT NULL,
  `copia_social` varchar(100) DEFAULT NULL,
  `copia_cnpj` varchar(100) DEFAULT NULL,
  `copia_id` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `senha` varchar(150) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `representantes_clientes`
--

DROP TABLE IF EXISTS `representantes_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representantes_clientes` (
  `id_cliente` int DEFAULT NULL,
  `id_representante` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `representantes_estados`
--

DROP TABLE IF EXISTS `representantes_estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representantes_estados` (
  `id_estado` int DEFAULT NULL,
  `id_representante` int DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `representantes_fornecedores`
--

DROP TABLE IF EXISTS `representantes_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representantes_fornecedores` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_representante` int unsigned NOT NULL,
  `id_fornecedor` int unsigned NOT NULL,
  `comissao` decimal(9,2) DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `meta` double(9,2) DEFAULT NULL,
  `gerente` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supervisor` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_gerente` text COLLATE utf8_unicode_ci,
  `email_supervisor` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `representantes_fornecedores_representante_idx` (`id_representante`),
  KEY `representantes_fornecedores_fornecedor_idx` (`id_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `representantes_pesquisas`
--

DROP TABLE IF EXISTS `representantes_pesquisas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representantes_pesquisas` (
  `codigo` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_representante` int DEFAULT NULL,
  `produto` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sem_estoque` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `representantes_vendas`
--

DROP TABLE IF EXISTS `representantes_vendas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representantes_vendas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_representante` int unsigned NOT NULL COMMENT 'ID do representante na tabela "usuarios"',
  `id_cliente` int unsigned NOT NULL COMMENT 'ID do cliente na tabela "usuarios"',
  `id_comissao` int unsigned NOT NULL,
  `id_ordem_compra` int unsigned NOT NULL COMMENT 'ID da ordem de compra na tabela "ordens_compra"',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `representantes_vendas_cliente_idx` (`id_cliente`),
  KEY `representantes_vendas_ordem_compra_idx` (`id_ordem_compra`),
  KEY `representantes_vendas_representante_idx` (`id_representante`),
  KEY `representantes_vendas_comissao_idx` (`id_comissao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `responsaveis_depara`
--

DROP TABLE IF EXISTS `responsaveis_depara`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `responsaveis_depara` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `inicio` datetime NOT NULL,
  `fim` datetime DEFAULT NULL,
  `integrador` int DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_cliente` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1999 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restricoes_produtos_clientes`
--

DROP TABLE IF EXISTS `restricoes_produtos_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restricoes_produtos_clientes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int unsigned DEFAULT NULL COMMENT 'ID do usuário na tabela "usuários" onde tipo 2 = Cliente;',
  `id_estado` int DEFAULT NULL,
  `id_fornecedor` int unsigned NOT NULL COMMENT 'ID do usuário na tabela "usuários" onde tipo 1 = Fornecedor;',
  `id_produto` int unsigned NOT NULL COMMENT 'ID produto na tabela "produtos"',
  `id_tipo_venda` int unsigned NOT NULL COMMENT 'ID do tipo de venda na tabela "tipos_venda"',
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `integrador` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fornecedor` (`id`,`id_fornecedor`),
  KEY `produtos_cnpj` (`id`,`id_fornecedor`,`id_cliente`,`id_produto`),
  KEY `produtos_estado` (`id`,`id_fornecedor`,`id_produto`,`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=268084 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Restricoes produtos clientes: Tabela contendo as restrições de produtos para cada clinte referente aquele fornecedor.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `restricoes_produtos_cotacoes`
--

DROP TABLE IF EXISTS `restricoes_produtos_cotacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restricoes_produtos_cotacoes` (
  `cd_cotacao` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `codigo` int DEFAULT NULL,
  `cd_produto_comprador` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_produto_sintese` int DEFAULT NULL,
  `id_marca` int DEFAULT NULL,
  `preco_marca` decimal(12,4) DEFAULT NULL,
  `estoque` int DEFAULT NULL,
  `ol` int DEFAULT '0',
  `sem_estoque` int DEFAULT '0',
  `restricao` int DEFAULT '0',
  `id_fornecedor` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  `id_fornecedor_logado` int DEFAULT NULL,
  `integrador` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'SINTESE',
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  KEY `IDX_GSYS_RPC_01` (`cd_cotacao`),
  KEY `IDX_GSYS_RPC_02` (`cd_cotacao`,`id_fornecedor`),
  KEY `IDX_GSYS_RPC_03` (`id_fornecedor`),
  KEY `IDX_GSYS_RPC_04` (`cd_produto_comprador`),
  KEY `IDX_GSYS_RPC_05` (`cd_cotacao`,`id_fornecedor`,`id_produto_sintese`,`cd_produto_comprador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rotas`
--

DROP TABLE IF EXISTS `rotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rotas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_parente` int DEFAULT NULL,
  `posicao` int NOT NULL DEFAULT '0',
  `rotulo` varchar(45) NOT NULL,
  `url` varchar(70) DEFAULT NULL,
  `icone` varchar(45) DEFAULT NULL,
  `alvo` varchar(10) NOT NULL DEFAULT '_self',
  `situacao` tinyint(1) NOT NULL DEFAULT '1',
  `grupo` tinyint DEFAULT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_registro` datetime NOT NULL,
  `modal` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=267 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `senha`
--

DROP TABLE IF EXISTS `senha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `senha` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `username` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `password` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sms_config`
--

DROP TABLE IF EXISTS `sms_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sms_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int NOT NULL,
  `id_usuario` int NOT NULL,
  `numero` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `modulos` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teste`
--

DROP TABLE IF EXISTS `teste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teste` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_produto` int NOT NULL,
  `descricao_sintese` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `palavra_chave` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `codigo_catalogo` varchar(20) COLLATE utf8_bin NOT NULL,
  `descricao_catalogo` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `associado` int NOT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_sintese` int DEFAULT NULL,
  `principios` text COLLATE utf8_bin,
  `visto` int DEFAULT '0',
  `integrador` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_TESTE_01` (`codigo_catalogo`,`id_cliente`),
  KEY `IDX_GSYS_TESTE_02` (`codigo_catalogo`),
  KEY `IDX_GSYS_TESTE_03` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=33844031 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `teste_lote`
--

DROP TABLE IF EXISTS `teste_lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teste_lote` (
  `codigo` int NOT NULL,
  `lote` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `local` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_fornecedor` int NOT NULL,
  `estoque` int NOT NULL,
  `validade` date NOT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `fixo` int DEFAULT '0',
  KEY `GSYS_IDX_PL_01` (`id_fornecedor`,`validade`),
  KEY `lote_produtos__estoque` (`estoque`,`lote`,`validade`),
  KEY `lote_produtos_codigo` (`codigo`,`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_venda`
--

DROP TABLE IF EXISTS `tipos_venda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipos_venda` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Tipos venda: Tabela contendo os tipos de venda aos quais o sistema irá trabalhar, através dos tipos de venda os fornecedores irão definir se os produtos serão vendidos/distribuídosb através do Marketplace, Integranexo ou ambas plataformas. \n\nEssa tabela pode ser expandida para outras formas de atuação e processamento.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_situacao_oc`
--

DROP TABLE IF EXISTS `tp_situacao_oc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tp_situacao_oc` (
  `codigo` int DEFAULT NULL,
  `descricao` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `unidades`
--

DROP TABLE IF EXISTS `unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidades` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ativo` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'Define se a unidade está ativa ou não; 0 = Inativo; 1 = Ativo',
  `unidade` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Identificação da unidade. ex: CX = CAIXA',
  `descricao` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Unidades:  Tabela contendo diversas unidades padronizadas e parametrizadas através do portal de administração';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_audit_trails`
--

DROP TABLE IF EXISTS `user_audit_trails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_audit_trails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `event` enum('insert','update','delete') NOT NULL,
  `table_name` varchar(128) NOT NULL,
  `old_values` text,
  `new_values` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `name` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4072260 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_old`
--

DROP TABLE IF EXISTS `users_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_old` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `integranexo` int DEFAULT '0' COMMENT '0: Fornecedor não integrado e não automático; 1: Fornecedor Integrado e não automático; 2: Fornecedor Integrado e Automático.',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cargo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razao_social` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone_comercial` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefone_celular` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco_comercial` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_estadual` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscricao_municipal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alvara_protocolo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validade` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expedido` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_alvara` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rt` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contrato` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `motivo_recusa` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `numero_afe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `copia_afe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_fantasia` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regras_de_venda` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_first_name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last_name` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_number` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_ccv` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_month` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_year` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verify_account` int NOT NULL DEFAULT '0',
  `user_token` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnpj_representante` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razao_social_representante` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `representante_id` int DEFAULT NULL,
  `tipo_admin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grupo_id` int NOT NULL DEFAULT '0',
  `quem_aprovou` int DEFAULT NULL,
  `quem_desativou` int DEFAULT NULL,
  `arquivo_enviado` varchar(110) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_vinc` int DEFAULT NULL COMMENT '29 = Hospidrogas; 132 = Biohosp',
  `tipo_fornecedor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1021 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_comprador` int unsigned DEFAULT NULL,
  `tipo_usuario` int NOT NULL DEFAULT '0' COMMENT 'Define o tipo de perfil do usuário. 0= Administrador; 1= Fornecedor; 2= Cliente; 3= Representante;',
  `nivel` int NOT NULL COMMENT 'Define o nível do usuário. 0=Adminstrador; 1=Financeiro; 2=Comercial;',
  `nome` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(150) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `administrador` tinyint DEFAULT '0',
  `token` varchar(150) DEFAULT NULL,
  `remember_token` varchar(1150) DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `situacao` int NOT NULL DEFAULT '1' COMMENT 'Informa se usuário está ativo ou não. 0= Inativo; 1= Ativo;',
  `usuario_sintese` varchar(200) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `validade_token` datetime DEFAULT NULL,
  `logado` tinyint DEFAULT '0',
  `cpf` varchar(50) DEFAULT NULL,
  `rg` varchar(40) DEFAULT NULL,
  `login_fe` int DEFAULT '0',
  `usuario_externo` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_GSYS_USR_01` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=551 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_clientes`
--

DROP TABLE IF EXISTS `usuarios_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_clientes` (
  `id_usuario` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `data_cadastro` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_fornecedores`
--

DROP TABLE IF EXISTS `usuarios_fornecedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_fornecedores` (
  `id_usuario` int unsigned NOT NULL,
  `id_fornecedor` int unsigned NOT NULL,
  `tipo` int DEFAULT NULL,
  PRIMARY KEY (`id_usuario`,`id_fornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_old`
--

DROP TABLE IF EXISTS `usuarios_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_old` (
  `tipo` int NOT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `id_aceite` int DEFAULT '0',
  `id_endereco` int NOT NULL,
  `id_contato_usuario` int DEFAULT '0',
  `id_dados_usuario` int NOT NULL,
  `id_nivel_acesso` int DEFAULT '0',
  `tipo_usuario` int NOT NULL DEFAULT '0' COMMENT 'Define o tipo de perfil do usuário: 0 = Administrador; 1 = Fornecedor; 2 = Cliente; 3 = Representante;',
  `email` varchar(1120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnpj` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Informa se usuário está ativo ou não.0 = Inativo;1 = Ativo;',
  `remember_token` varchar(1150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_criacao` timestamp NULL DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `token` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_fornecedor` int DEFAULT NULL COMMENT '''identificador da empresa que o usuario pertence''',
  `id_grupo` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_rede_atendimento`
--

DROP TABLE IF EXISTS `usuarios_rede_atendimento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_rede_atendimento` (
  `id_usuario` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_estado` int DEFAULT NULL,
  `data_cadastro` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_fornecedor` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios_resgate`
--

DROP TABLE IF EXISTS `usuarios_resgate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios_resgate` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int DEFAULT NULL,
  `nome` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `usuario` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `data_criacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=428 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `valor_minimo_cliente`
--

DROP TABLE IF EXISTS `valor_minimo_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `valor_minimo_cliente` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_fornecedor` int unsigned NOT NULL,
  `id_estado` int DEFAULT NULL,
  `id_cliente` int DEFAULT NULL,
  `id_tipo_venda` int unsigned NOT NULL,
  `valor_minimo` decimal(10,2) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `desconto_padrao` decimal(10,2) DEFAULT '0.00' COMMENT 'Desconto padrão para todos os produtos',
  PRIMARY KEY (`id`),
  KEY `valor_minimo_cliente_fornecedor_idx` (`id_fornecedor`),
  KEY `valor_minimo_cliente_cliente_idx` (`id_cliente`),
  KEY `valor_minimo_cliente_tipo_venda_idx` (`id_tipo_venda`)
) ENGINE=InnoDB AUTO_INCREMENT=1737 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vendas_diferenciadas`
--

DROP TABLE IF EXISTS `vendas_diferenciadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendas_diferenciadas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_cliente` int unsigned DEFAULT NULL COMMENT 'ID do cliente na tabela "usuarios"',
  `id_estado` int DEFAULT NULL,
  `id_fornecedor` int unsigned NOT NULL COMMENT 'ID do fornecedor  na tabela "usuarios"',
  `id_produto` int unsigned NOT NULL COMMENT 'ID do produto na tabela "produtos"',
  `id_tipo_venda` int unsigned NOT NULL COMMENT 'ID do tipo de venda na tabela "tipos_venda"',
  `desconto_percentual` decimal(6,2) NOT NULL DEFAULT '0.00',
  `comissao` decimal(9,2) DEFAULT NULL,
  `quantidade` int DEFAULT NULL,
  `tipo` int DEFAULT NULL,
  `data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lote` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `dias` int DEFAULT NULL,
  `codigo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `promocao` tinyint DEFAULT '0',
  `validade` datetime DEFAULT NULL,
  `regra_venda` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produtos_cliente` (`id`,`id_produto`,`id_cliente`,`id_fornecedor`),
  KEY `produtos_estados` (`id`,`id_fornecedor`,`id_produto`,`id_estado`),
  KEY `GSYS_IDX_VD_01` (`promocao`,`id_fornecedor`),
  KEY `GSYS_IDX_VD_02` (`codigo`,`id_fornecedor`,`regra_venda`),
  KEY `GSYS_IDX_VD_03` (`codigo`,`id_fornecedor`),
  KEY `GSYS_IDX_VD_04` (`regra_venda`),
  KEY `GSYS_IDX_VD_06` (`codigo`),
  KEY `GSYS_IDX_VD_07` (`promocao`,`id_estado`,`id_fornecedor`),
  KEY `GSYS_IDX_VD_08` (`id_estado`),
  KEY `GSYS_IDX_VD_09` (`id_fornecedor`),
  KEY `GSYS_IDX_VD_10` (`id_fornecedor`,`id_estado`,`codigo`),
  KEY `GSYS_IDX_VD_11` (`id_cliente`,`promocao`,`regra_venda`,`id_fornecedor`),
  KEY `GSYS_IDX_VD_12` (`id`,`id_cliente`,`promocao`,`regra_venda`,`id_fornecedor`),
  KEY `GSYS_IDX_VD_13` (`id`,`codigo`,`id_cliente`),
  KEY `GSYS_IDX_VD_14` (`id_cliente`,`codigo`,`id_fornecedor`),
  KEY `GSYS_IDX_VD_15` (`id`,`codigo`,`id_fornecedor`)
) ENGINE=InnoDB AUTO_INCREMENT=1055815 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Vendas diferenciada: Tabela contendo as promoções aplicadas especificamente entre fornecedor, cliente e produto.';
/*!40101 SET character_set_client = @saved_cs_client */;

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
