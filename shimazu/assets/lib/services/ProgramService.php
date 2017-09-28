<?php 
//Urabe 
include_once "/../../../../urabe/HasamiURLParameters.php";
include_once "/../../../../urabe/HasamiWrapper.php";
include_once "/../../../../urabe/HasamiUtils.php";
include_once "/../../../../urabe/Warai.php";
//Avalon
include_once "/../../../../avalon/MorganaUtils.php";
include_once "/../../../../avalon/Chivalry.php";
//CDMX
include_once "/../AppConst.php";
include_once "/../AppAccess.php";
include_once "/../AppSession.php";
include_once "/../CDMXId.php";
/**
 * Program Service Class
 * Esta clase se encarga de las peticiones que manejan la información de programas
 * @version 1.0.0
 * @api CDMX Seguimiento de obras
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 CADLabs
 */
class ProgramService extends HasamiWrapper
{
    /**
     * __construct
     *
     * Inicializa una nueva instancia del servicio administración de programas
     * @param string $url_params Los párametros leidos de las URLS
     */
    function __construct($url_params = NULL)
    {
        $db_id = new CDMXId();
        parent::__construct(PROGRAM_TABLE, PROGRAM_FIELD_ID, $db_id);
    }
}
?>