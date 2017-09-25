USE `cdmx_obras`;
CREATE  OR REPLACE VIEW fechas_avances AS 
    (SELECT 
        A.`id_captura`, 
        P.`clv_programa`, 
        H.`fecha_captura`
    FROM 
        `partidas`AS P, 
        `avances`AS A,
        `historicos`AS H
    WHERE 
        P.`clv_partida`= A.`clv_partida` 
        AND
        A.`id_captura`= H.`id_captura` 
        AND
        H.`avance_financiero_total` > 0 
        AND 	
        H.`avance_fisico_total` > 0
    GROUP BY P.`clv_programa`, A.`id_captura`
    ORDER BY H.`fecha_captura` DESC)