CREATE TABLE IF NOT EXISTS `cdmx_obras`.`programas` (
  `clv_programa` INT NOT NULL AUTO_INCREMENT,
  `clv_proyecto` INT NOT NULL,
  `id_convenio` VARCHAR(45) CHARACTER SET 'big5' NULL,
  `fecha_convenio` DATETIME NOT NULL,
  `monto_convenio` DOUBLE NOT NULL,
  PRIMARY KEY (`clv_programa`),
  UNIQUE INDEX `clv_programa_UNIQUE` (`clv_programa` ASC),
  INDEX `fk_programas_proyectos_idx` (`clv_proyecto` ASC),
  CONSTRAINT `fk_programas_proyectos`
    FOREIGN KEY (`clv_proyecto`)
    REFERENCES `cdmx_obras`.`proyectos` (`clv_proyecto`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB