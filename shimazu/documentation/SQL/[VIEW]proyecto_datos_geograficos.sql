USE `cdmx_obras`;
CREATE  OR REPLACE VIEW proyecto_datos_geograficos AS 
    (SELECT
        `p`.*, `l`.`clv_dg`, `l`.`latitud`, `l`.`longitud`, `l`.`tipo_geometria`
    FROM
        `cdmx_obras`.`proyectos` AS `p`, 
        `cdmx_obras`.`datos_geograficos` AS `l`
    WHERE
        `p`.`clv_proyecto` = `l`.`clv_proyecto`);