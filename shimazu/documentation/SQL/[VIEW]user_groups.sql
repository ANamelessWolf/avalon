USE `cdmx_obras`;
CREATE  OR REPLACE VIEW `user_groups` AS
(SELECT 
    `knights`.`knight_id`,
    `knights`.`user_name`, 
    `usuarios`.`clv_usuario`, 
    `usuarios`.`nombre`, 
    `knights_groups`.`group_name` 
FROM 
    `usuarios`,
    `knights`,
    `knights_groups`,
    `knights_ranking` 
WHERE 
    `usuarios`.`knight_id` = `knights`.`knight_id` 
    AND 
    `knights_groups`.`group_id` = `knights_ranking`.`group_id` 
    AND 
    `knights`.`knight_id`=`knights_ranking`.`knight_id`)