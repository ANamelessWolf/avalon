<?php 
//Urabe 
include_once "/../../../../urabe/HasamiURLParameters.php";
include_once "/../../../../urabe/HasamiWrapper.php";
include_once "/../../../../urabe/HasamiUtils.php";
include_once "/../../../../urabe/Warai.php";
//Avalon
include_once "/../../../../avalon/services/KnightService.php";
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
            return $this->POST_action();
        };
    }
    /**
     * Define la acción de POST del servicio
     *
     * @return string La respuesta del servidor
     */
    public function POST_action()
    {
        try {
            if (is_null($this->url_parameters) || !$this->url_parameters->exists(KEY_TASK)) {
                http_response_code(400);
                throw new Exception(ERR_TASK_UNDEFINED);
            }
            else {
                $task = $this->url_parameters->parameters[KEY_TASK];
                switch ($task) {
                    case TASK_LOGIN :
                        $response = $this->login();
                        break;
                    default :
                        throw new Exception(ERR_TASK_UNDEFINED);
                }
            }
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
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
                    else
                        throw new Exception(ERR_BAD_LOGIN);
                }
                else
                    throw new Exception(ERR_BAD_LOGIN);
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