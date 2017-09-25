<?php
//Avalon
include_once "/../../../avalon/MorganaAccess.php";
include_once "AppConst.php";
/**
 * Esta clase se encarga de manejar el acceso en la aplicación
 * @version 1.0.0
 * @api CDMX Seguimiento de obras
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 CADLabs
 */
class AppAccess extends MorganaAccess
{
    /**
     * Inicializa una nueva instancia de la clase AppAccess
     */
    function __construct()
    {
        $groups = array(
            GROUP_VIGILANT => VIGILANT_KEY,
            GROUP_ADMIN_GRP => GROUP_ADMIN_KEY,
            GROUP_SUPER => THE_NAMELESS_KEY,
            GROUP_RECYCLE_BIN => RECYCLER_KEY,
            GROUP_CDMX_USER => CDMX_USER_KEY
        );
        parent::__construct($groups);
    }
}
?>