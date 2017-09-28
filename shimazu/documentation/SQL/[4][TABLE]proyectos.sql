CREATE TABLE IF NOT EXISTS `cdmx_obras`.`proyectos` (
  `clv_proyecto` INT NOT NULL AUTO_INCREMENT,
  `clv_usuario` INT NOT NULL,
  `num_contrato` VARCHAR(20) NOT NULL,
  `nom_proyecto` VARCHAR(300) NOT NULL,
  `monto_contrato` DOUBLE NOT NULL,
  `fecha_inicio_contrato` DATETIME NOT NULL,
  `fecha_termino_contrato` DATETIME NOT NULL,
  `estatus` VARCHAR(3) NULL,
  `tipo_proyecto` TINYINT(4) NULL,
  `empleos_generados` INT(8) NULL,
  `empleos_temporales` INT(8) NULL,
  `empleos_indirectos` INT(8) NULL,
  `observaciones` VARCHAR(600) NULL,
  PRIMARY KEY (`clv_proyecto`),
  UNIQUE INDEX `clv_proyecto_UNIQUE` (`clv_proyecto` ASC),
  INDEX `fk_proyectos_usuarios_idx` (`clv_usuario` ASC),
  CONSTRAINT `fk_proyectos_usuarios`
    FOREIGN KEY (`clv_usuario`)
    REFERENCES `cdmx_obras`.`usuarios` (`clv_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB