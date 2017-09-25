<?php
//URABE
include_once "/../../../urabe/HasamiUtils.php";
//Avalon
include_once "/../../../avalon/MorganaSession.php";
//CDMX
include_once "AppConst.php";
/**
 * Esta clase se encarga de manejar la sesión de la aplicaión
 * @version 1.0.0
 * @api CDMX Seguimiento de obras
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 CADLabs
 */
class AppSession extends MorganaSession
{
    /**
     * @var string The user key
     */
    public $clv_usuario;
    /**
     * @var string The name of the user
     */
    public $nombre;
    /**
     * Inicializa una nueva sesión de la aplicación
     *
     * @param string $stdClass La información de inicio de sesión
     */
    public function __construct($session_data)
    {
        parent::__construct($session_data->{USER_SESSION}, $session_data->{USR_ACCESS_SESSION}, $session_data->{GRP_SESSION});
        $this->session_fields = array_keys((array)$session_data);
        $this->clv_usuario = $session_data->{USER_FIELD_ID};
        $this->nombre = $session_data->{USER_FIELD_NAME};
        $this->login_task = function ($input) {
        //Registramos al usuario
            $_SESSION[USER_FIELD_ID] = $this->clv_usuario;
            $_SESSION[USER_FIELD_NAME] = $this->nombre;
        };
    }
    /**
     * Devuelve la respuesta de los datos de sesión actual
     *
     * @return string The server response
     */
    public function get_response()
    {
        $result = array(
            USER_SESSION => $this->user_name,
            USER_FIELD_ID => $this->clv_usuario,
            USER_FIELD_NAME => $this->nombre,
            USR_ACCESS_SESSION => $this->user_access,
            GRP_SESSION => $this->groups,
        );
        return service_response($result, TRUE);
    }
}
?>