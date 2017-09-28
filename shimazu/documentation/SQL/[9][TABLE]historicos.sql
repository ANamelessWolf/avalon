CREATE TABLE IF NOT EXISTS `cdmx_obras`.`historicos` (
  `id_captura` INT NOT NULL AUTO_INCREMENT,
  `clv_programa` INT NOT NULL,
  `fecha_captura` DATETIME NOT NULL,
  `avance_programado_total` DOUBLE NOT NULL,
  `avance_fisico_total` DOUBLE NOT NULL,
  `avance_financiero_total` DOUBLE NOT NULL,
  `contractual` DOUBLE NULL DEFAULT 0,
  `escalatorio` DOUBLE NULL DEFAULT 0,
  `adicional` DOUBLE NULL DEFAULT 0,
  `extraordinaria` DOUBLE NULL DEFAULT 0,
  `reclamos` DOUBLE NULL DEFAULT 0,
  `HHTSA` DOUBLE NULL DEFAULT 0,
  PRIMARY KEY (`id_captura`),
  UNIQUE INDEX `id_captura_UNIQUE` (`id_captura` ASC),
  INDEX `fk_partidas_programas_idx` (`clv_programa` ASC),
  CONSTRAINT `fk_historicos_programas`
    FOREIGN KEY (`clv_programa`)
    REFERENCES `cdmx_obras`.`programas` (`clv_programa`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB