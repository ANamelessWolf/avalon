CREATE TABLE IF NOT EXISTS `cdmx_obras`.`fotografias` (
  `clv_fotografia` INT NOT NULL AUTO_INCREMENT,
  `clv_proyecto` INT NOT NULL,
  `path_fotografia` VARCHAR(100) NOT NULL,
  `desc_fotografia` VARCHAR(200) NULL,
  `fecha_ingreso` DATETIME NOT NULL,
  `mapa` TINYINT(1) NOT NULL,
  PRIMARY KEY (`clv_fotografia`),
  UNIQUE INDEX `clv_fotografia_UNIQUE` (`clv_fotografia` ASC),
  INDEX `fk_fotografias_proyectos_idx` (`clv_proyecto` ASC),
  CONSTRAINT `fk_fotografias_proyectos`
    FOREIGN KEY (`clv_proyecto`)
    REFERENCES `cdmx_obras`.`proyectos` (`clv_proyecto`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB