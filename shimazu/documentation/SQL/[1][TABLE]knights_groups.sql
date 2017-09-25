CREATE TABLE IF NOT EXISTS `cdmx_obras`.`knights` (
  `knight_id` INT NOT NULL AUTO_INCREMENT,
  `user_name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`knight_id`),
  UNIQUE INDEX `knight_id_UNIQUE` (`knight_id` ASC),
  UNIQUE INDEX `user_name_UNIQUE` (`user_name` ASC))
ENGINE = InnoDB