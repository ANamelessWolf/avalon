CREATE TABLE IF NOT EXISTS `cdmx_obras`.`avances` (
  `clv_avance` INT NOT NULL AUTO_INCREMENT,
  `id_captura` INT NOT NULL,
  `clv_partida` INT NOT NULL,
  `avance_programado` DOUBLE NOT NULL DEFAULT -1,
  `avance_fisico` DOUBLE NOT NULL DEFAULT -1,
  `avance_financiero` DOUBLE NOT NULL DEFAULT -1,
  PRIMARY KEY (`clv_avance`),
  UNIQUE INDEX `clv_avance_UNIQUE` (`clv_avance` ASC),
  INDEX `fk_avances_historicos_idx` (`id_captura` ASC),
  INDEX `fk_avances_partidas_idx` (`clv_partida` ASC),
  CONSTRAINT `fk_avances_historicos`
    FOREIGN KEY (`id_captura`)
    REFERENCES `cdmx_obras`.`historicos` (`id_captura`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_avances_partidas`
    FOREIGN KEY (`clv_partida`)
    REFERENCES `cdmx_obras`.`partidas` (`clv_partida`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB