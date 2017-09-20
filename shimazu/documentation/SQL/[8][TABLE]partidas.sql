CREATE TABLE IF NOT EXISTS `cdmx_obras`.`partidas` (
  `clv_partida` INT NOT NULL AUTO_INCREMENT,
  `clv_programa` INT NOT NULL,
  `nom_partida` VARCHAR(200) NOT NULL,
  `fecha_inicio` DATETIME NOT NULL,
  `fecha_termino` DATETIME NOT NULL,
  `monto_total_programado` DOUBLE NOT NULL,
  PRIMARY KEY (`clv_partida`),
  UNIQUE INDEX `clv_partida_UNIQUE` (`clv_partida` ASC),
  INDEX `fk_partidas_programas_idx` (`clv_programa` ASC),
  CONSTRAINT `fk_partidas_programas`
    FOREIGN KEY (`clv_programa`)
    REFERENCES `cdmx_obras`.`programas` (`clv_programa`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB