CREATE TABLE IF NOT EXISTS `Avalon`.`knights_ranking` (
  `rank_id` INT NOT NULL AUTO_INCREMENT,
  `knight_id` INT NOT NULL,
  `group_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`rank_id`),
  UNIQUE INDEX `rank_id_UNIQUE` (`rank_id` ASC),
  INDEX `fk_knights_ranking_knights_idx` (`knight_id` ASC),
  INDEX `fk_knights_ranking_groups_idx` (`group_id` ASC),
  CONSTRAINT `fk_knights_ranking_knights`
    FOREIGN KEY (`knight_id`)
    REFERENCES `Avalon`.`knights` (`knight_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_knights_ranking_groups`
    FOREIGN KEY (`group_id`)
    REFERENCES `Avalon`.`knights_groups` (`group_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB