USE `cdmx_obras`;
CREATE  OR REPLACE VIEW avances_programas AS 
    (SELECT 
        A.`id_captura`, 
        A.`clv_partida`,
        P.`clv_programa`, 
        H.`fecha_captura`, 
        A.`avance_fisico`, 
        A.`avance_financiero` 
    FROM 
        `avances` AS A, 
        `partidas` AS P,
        `historicos` AS H
    WHERE 
        A.`clv_partida` = P.`clv_partida`
        AND
        H.`id_captura` = A.`id_captura`
    ORDER BY H.`fecha_captura`, A.`clv_partida`);