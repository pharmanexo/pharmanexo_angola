DROP TABLE IF EXISTS `report_factory`;

CREATE TABLE `report_factory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `usuario_solicitacao_id` int unsigned DEFAULT NULL,
  `hashcod` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_extension` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_solicitacao` datetime NOT NULL,
  `status` smallint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_9BAF63F94456E3A0` (`hashcod`),
  KEY `IDX_9BAF63F916D6AD02` (`usuario_solicitacao_id`),
  CONSTRAINT `FK_9BAF63F916D6AD02` FOREIGN KEY (`usuario_solicitacao_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;