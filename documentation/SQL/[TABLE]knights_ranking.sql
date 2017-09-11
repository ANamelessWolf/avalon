CREATE TABLE IF NOT EXISTS `Avalon`.`knights_ranking` (
  `knight_id` INT NOT NULL,
  `group_id` INT NOT NULL,
  PRIMARY KEY (`knight_id`, `group_id`),
  INDEX `fk_knights_ranking_knights_groups1_idx` (`group_id` ASC),
  CONSTRAINT `fk_knights_ranking_knights1`
    FOREIGN KEY (`knight_id`)
    REFERENCES `Avalon`.`knights` (`knight_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_knights_ranking_knights_groups1`
    FOREIGN KEY (`group_id`)
    REFERENCES `Avalon`.`knights_groups` (`group_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB