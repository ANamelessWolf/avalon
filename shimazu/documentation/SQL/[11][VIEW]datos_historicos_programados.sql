USE `cdmx_obras`;
CREATE  OR REPLACE VIEW datos_historicos_programados AS 
    (SELECT 
        P.`clv_programa`, 
        IF(H.`fecha_captura`>=P.`fecha_inicio`, H.`id_captura`, H.id_captura*-1) AS `id`, 
        H.`fecha_captura`, H.`avance_programado_total`, 
        H.`avance_fisico_total`, 
        H.`avance_financiero_total` 
    FROM 
        `partidas` AS P, 
        `historicos` AS H 
    WHERE 
        P.`clv_programa` = H.`clv_programa` 
    GROUP BY `id` HAVING `id`>0 )