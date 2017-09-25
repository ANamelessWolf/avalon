USE `cdmx_obras`;
CREATE  OR REPLACE VIEW datos_avances_historicos AS 
    (SELECT 
        P.`clv_partida`, 
        A.`id_captura`, 
        P.`clv_programa`, 
        P.`nom_partida`,
        H.`fecha_captura`, 
        P.`monto_total_programado`, 
        A.`avance_programado`, 
        A.`avance_fisico`, 
        A.`avance_financiero` 
    FROM 
        `partidas`AS P, 
        `avances`AS A,
        `historicos`AS H
    WHERE 
        P.`clv_partida`= A.`clv_partida` 
        AND
        A.`id_captura`= H.`id_captura` 
    ORDER BY H.`fecha_captura`)