<?php
include_once "/../../../urabe/KanojoX.php";
/**
 * La información de conexión a la base de CDMX
 * 
 * @version 1.0.0
 * @api CDMX Seguimiento de obras
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 CADLabs
 */
class CDMXId extends KanojoX
{
    /**
     * __construct
     *
     * Inicializa una nueva instancia del identificador de conexión
     */
    function __construct()
    {
        $this->db_name = "cdmx_obras";
        $this->host = "127.0.0.1";
        $this->user_name = "root";
        $this->password = "";
    }
}
?>