CREATE TABLE IF NOT EXISTS `cdmx_obras`.`usuarios` (
  `clv_usuario` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(200) NOT NULL,
  `knight_id` INT NOT NULL,
  PRIMARY KEY (`clv_usuario`),
  UNIQUE INDEX `clv_usuario_UNIQUE` (`clv_usuario` ASC),
  INDEX `fk_usuarios_knights_idx` (`knight_id` ASC),
  CONSTRAINT `fk_usuarios_knights`
    FOREIGN KEY (`knight_id`)
    REFERENCES `cdmx_obras`.`knights` (`knight_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB