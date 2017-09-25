USE `cdmx_obras`;
CREATE  OR REPLACE VIEW avances_onac AS 
    (SELECT 
        P.`clv_programa`,
        P.`clv_proyecto`,
        H.`id_captura`,
		H.`fecha_captura`,
        J.`nom_proyecto`, 
        J.`num_contrato`,
        J.`monto_contrato`, 
        P.`monto_convenio`, 		
        J.`fecha_inicio_contrato`,
        J.`fecha_termino_contrato`,
        P.`fecha_convenio`,
        J.`empleos_generados`, 	
        J.`empleos_temporales`, 	
        J.`empleos_indirectos`, 	
        J.`observaciones`,
        D.`avance_programado_total`,
        D.`avance_fisico_total`,
        D.`avance_financiero_total`,
        concat(round(( D.`avance_programado_total`/J.`monto_contrato` * 100 ),2),'%') AS avance_programado_percent,
        concat(round(( D.`avance_fisico_total`/J.`monto_contrato` * 100 ),2),'%') AS avance_fisico_percent,
        concat(round(( D.`avance_financiero_total`/J.`monto_contrato` * 100 ),2),'%') AS avance_financiero_percent,
        J.`HHTSA`,
        H.`contractual`,
        H.`escalatorio`,
        H.`adicional`,
        H.`extraordinaria`,
        H.`reclamos`,
		U.`nombre`
    FROM 
    `datos_historicos_programados` AS D, 
    `historicos` AS H, 
    `programas` AS P,
    `proyectos` AS J,
	`usuarios` AS U
    WHERE 
    D.`id` = H.`id_captura`
    AND
    P.`clv_programa` = H.`clv_programa`
    AND
    J.`clv_proyecto` = P.`clv_proyecto`
	AND
	U.`clv_usuario` = J.`clv_usuario`)