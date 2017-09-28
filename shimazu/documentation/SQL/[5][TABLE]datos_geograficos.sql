CREATE TABLE IF NOT EXISTS `cdmx_obras`.`datos_geograficos` (
  `clv_dg` INT NOT NULL AUTO_INCREMENT,
  `clv_proyecto` INT NOT NULL,
  `tipo_geometria` TINYINT(3) NOT NULL,
  `latitud` DOUBLE NOT NULL,
  `longitud` DOUBLE NOT NULL,
  `no_figura` TINYINT UNSIGNED NULL,
  `loc_index` SMALLINT UNSIGNED NULL,
  PRIMARY KEY (`clv_dg`),
  UNIQUE INDEX `clv_dg_UNIQUE` (`clv_dg` ASC),
  INDEX `fk_datos_geograficos_proyectos_idx` (`clv_proyecto` ASC),
  CONSTRAINT `fk_datos_geograficos_proyectos`
    FOREIGN KEY (`clv_proyecto`)
    REFERENCES `cdmx_obras`.`proyectos` (`clv_proyecto`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB