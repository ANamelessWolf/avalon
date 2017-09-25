<?php 
//Urabe 
include_once "/../../../../urabe/HasamiURLParameters.php";
include_once "/../../../../urabe/HasamiWrapper.php";
include_once "/../../../../urabe/HasamiUtils.php";
include_once "/../../../../urabe/Warai.php";
//Avalon
include_once "/../../../../avalon/services/KnightService.php";
include_once "/../../../../avalon/MorganaUtils.php";
//CDMX
include_once "/../AppConst.php";
include_once "/../AppAccess.php";
include_once "/../AppSession.php";
include_once "/../CDMXId.php";
/**
 * User Service Class
 * Esta clase se encarga de las peticiones que manejan el acceso y administración de usuarios
 * @version 1.0.0
 * @api CDMX Seguimiento de obras
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 CADLabs
 */
class UserService extends HasamiWrapper
{
    /**
     * @var KnightService Administra la conexión de acceso de usuarios.
     */
    public $k_service;
    /**
     * __construct
     *
     * Inicializa una nueva instancia del servicio de inicio sesión y administración de usuarios
     * @param string $url_params The url parameters
     */
    function __construct($url_params = NULL)
    {
        $db_id = new CDMXId();
        parent::__construct(USER_TABLE, USER_FIELD_ID, $db_id);
        $this->enable_GET = FALSE;
        $this->url_parameters = $url_params;
        $this->k_service = new KnightService($url_params, $db_id);
        $this->POST->service_task = function ($sender) {
            return send_service_petition($this, F_POST);
        };
    }
    /**
     * Define la acción de POST del servicio
     *
     * @param string $task La tarea seleccionada del servidor
     * @return string La respuesta del servidor
     */
    public function POST_action($task)
    {
        switch ($task) {
            case TASK_LOGIN :
                $response = $this->login();
                break;
            case TASK_LOGOUT :
                $response = $this->logout();
                break;
            case TASK_GET :
                $response = $this->get_users();
                break;
            case CDMX_TASK_CHECK :
                $response = $this->check();
                break;
            default :
                throw new Exception(ERR_TASK_UNDEFINED);
        }
        return $response;
    }
    /**
     * Esta función se encarga de iniciar sesión proporcionando un nombre de usuario y un password obtenidos
     * del body
     * @return string La respuesta del servidor
     */
    public function login()
    {
        try {
            if (is_null($this->body)) {
                http_response_code(400);
                throw new Exception(ERR_BODY_IS_NULL);
            }
            else if ($this->body_has(KNIGHT_FIELD_NAME, KNIGHT_FIELD_PASS)) {
                $credentials = $this->k_service->login($this->body->{KNIGHT_FIELD_NAME}, $this->body->{KNIGHT_FIELD_PASS});
                if (has_result($credentials)) {
                    $kId = $credentials->{NODE_RESULT}[0]->{KNIGHT_FIELD_ID};
                    $u_data = $this->get_user_data($kId);
                    $u_data = json_decode($u_data);
                    if (has_result($credentials)) {
                        $session = new AppSession($u_data);
                        $session->login();
                        $response = $session->get_response();
                    }
                    else {
                        http_response_code(401);
                        throw new Exception(ERR_BAD_LOGIN);
                    }
                }
                else {
                    http_response_code(401);
                    throw new Exception(ERR_BAD_LOGIN);
                }
            }
            else {
                http_response_code(400);
                throw new Exception(sprintf(ERR_INCOMPLETE_BODY, TASK_LOGIN, KNIGHT_FIELD_NAME . ", " . KKNIGHT_FIELD_PASS));
            }
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        return $response;
    }
    /**
     * Revisa la sesión de los datos de sesión del usuario actual
     *
     * @return string La respuesta del servidor
     */
    public function check()
    {
        $session = check_session(array(USER_FIELD_ID, USER_FIELD_NAME));
        return service_response($session, TRUE);
    }
    /**
     * Cierra la sesión actual
     *
     * @return string La respuesta del servidor
     */
    public function logout()
    {
        end_session(array(USER_FIELD_ID, USER_FIELD_NAME));
        $result = array(NODE_MSG => MSG_LOGOUT);
        return service_response($result, TRUE);
    }
    /**
     *
     * Devuelve la colección de usarios guardados en el sistema si se trata de super
     * o de un administrador
     * 
     * @return string La respuesta del servidor
     */
    public function get_users()
    {
        $acc = new AppAccess();

        if ($acc->is_permitted(GROUP_SUPER)) {
           $session = 
        }
    }
    /**
     * Obtiene los datos de inicio de sesión de un usuario
     * @param int $knight_id El id del usuario
     * @return stdClass La información de inicio de sesión del usuario
     */
    public function get_user_data($knight_id)
    {
        $query = query_select_by_field(USER_GROUP_TABLE, KNIGHT_FIELD_ID, $knight_id);
        $response = $this->connector->select($query);
        $response = json_decode($response);
        if (has_result($response)) {
            $session_data = array(
                KNIGHT_FIELD_NAME => "",
                USER_FIELD_ID => -1,
                USER_FIELD_NAME => "",
                USR_ACCESS_SESSION => array(),
                GRP_SESSION => array()
            );
            $access = new AppAccess();
            foreach ($response->{NODE_RESULT} as &$result) {
                $access->SetData($session_data, $result, KNIGHT_FIELD_NAME, "");
                $access->SetData($session_data, $result, USER_FIELD_ID, -1);
                $access->SetData($session_data, $result, USER_FIELD_NAME, "");
                $access->SetGroup($session_data, $result, CDMX_USER_KEY);
            }
            return json_encode($session_data);
        }
        else
            return error_response(ERR_BAD_LOGIN);
    }

}
?>