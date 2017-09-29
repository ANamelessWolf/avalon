<?php 
//Urabe 
include_once "/../../../../urabe/HasamiURLParameters.php";
include_once "/../../../../urabe/HasamiWrapper.php";
include_once "/../../../../urabe/HasamiUtils.php";
include_once "/../../../../urabe/Warai.php";
//Avalon
include_once "/../../../../avalon/services/KnightService.php";
include_once "/../../../../avalon/MorganaUtils.php";
include_once "/../../../../avalon/Chivalry.php";
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
        $this->DELETE->service_task = function ($sender) {
            return send_service_petition($this, F_DELETE);
        };
        $this->PUT->service_task = function ($sender) {
            return send_service_petition($this, F_PUT);
        };
    }
    /**
     * Define la acción de PUT del servicio
     * Se puede actualizar solo el nombre y la contraseña
     *
     * @param string $task La tarea seleccionada del servidor
     * @return string La respuesta del servidor
     */
    public function PUT_action($task)
    {
        try {
            $is_null = is_null($this->body);
            $has_name = property_exists($this->body, USER_FIELD_NAME);
            $has_password = property_exists($this->body, KNIGHT_FIELD_PASS);
            if ($is_null) {
                http_response_code(400);
                throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_UPDATE, USER_FIELD_ID));
            }
            else {
                //Se actualiza la tabla de usuarios
                if ($has_name) {
                    $this->PUT->table_update_fields(USER_FIELD_NAME);
                    $response_user = json_decode($this->PUT->update_by_field(USER_FIELD_ID));
                }
                else
                    $response_user = service_response(array(NODE_STATUS => STATUS_NOT_MODIFIED), FALSE, "", FALSE);
                //Se actualiza la tabla knights
                if ($has_password) {
                    $kId = $this->get_knight_id($this->body->{USER_FIELD_ID});
                    $response_pass = $this->k_service->update_password($kId, $this->body->{KNIGHT_FIELD_PASS}, TRUE);
                }
                else
                    $response_pass = service_response(array(NODE_STATUS => STATUS_NOT_MODIFIED), FALSE, "", FALSE);
                return service_response(array($response_user, $response_pass), TRUE);
            }
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        return $response;
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
            case TASK_CREATE :
                $response = $this->register_user();
                break;
            case CDMX_TASK_JOIN_GROUP :
                $response = $this->join_group();
                break;
            case CDMX_TASK_CREATE_ADMIN :
                $response = $this->create_admin();
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
     * Borra un usuario de la base
     *
     * @param string $task La tarea seleccionada del servidor
     * @return string La respuesta del servidor
     */
    public function DELETE_action($task)
    {
        try {
            $access = new AppAccess();
            if (!$access->is_permitted(GROUP_ADMIN_GRP)) {
                http_response_code(403);
                throw new Exception(ERR_ACCESS_DENIED);
            }
            if ($task == SERVICE_USER && $this->body_has(USER_FIELD_ID)) {
                $kId = $this->get_knight_id($this->body->{USER_FIELD_ID});

                inject_if_not_in($this->k_service->body, KNIGHT_FIELD_ID, $kId);
                $response = $this->k_service->get_response();
            }
            else if ($task == SERVICE_KNIGHT_GROUP && $this->body_has(KNIGHT_GRP_FIELD_NAME)) {
                $group_name = $this->body->{KNIGHT_GRP_FIELD_NAME};

                if (array_key_exists($group_name, $access->groups)) {
                    http_response_code(403);
                    throw new Exception(sprintf(ERR_DEL_SYSTEM_GROUP, $group_name));
                }
                else
                    $response = $this->k_service->remove_group($group_name);
            }
            else if (!in_array($task, array(SERVICE_USER, SERVICE_KNIGHT_GROUP))) {
                http_response_code(400);
                throw new Exception(ERR_TASK_UNDEFINED);
            }
            else {
                http_response_code(400);
                if ($task == SERVICE_KNIGHT_GROUP)
                    throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_DELETE, KNIGHT_GRP_FIELD_NAME));
                else
                    throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_DELETE, USER_FIELD_ID));
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
                throw new Exception(sprintf(ERR_INCOMPLETE_BODY, TASK_LOGIN, KNIGHT_FIELD_NAME . ", " . KNIGHT_FIELD_PASS));
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
        try {
            $acc = new AppAccess();
            $session_data = check_session(array(USER_FIELD_ID, USER_FIELD_NAME));
            if ($acc->is_permitted(GROUP_SUPER, TRUE)) {
            //Se ignorá solo el usuario Recycler
                if ($this->body(USER_FIELD_ID)) {
                    $query = "SELECT * FROM `%s` WHERE `%s`!= '%s' AND `%s`== %d";
                    $query = sprintf($query, USER_GROUP_TABLE, KNIGHT_GRP_FIELD_NAME, GROUP_RECYCLE_BIN, USER_FIELD_ID, $this->body->{USER_FIELD_ID});
                }
                else {
                    $query = "SELECT * FROM `%s` WHERE `%s`!= '%s'";
                    $query = sprintf($query, USER_GROUP_TABLE, KNIGHT_GRP_FIELD_NAME, GROUP_RECYCLE_BIN);
                }
            }
            else if ($acc->is_permitted(GROUP_ADMIN_GRP, TRUE)) {
                $grps = $session_data[GRP_SESSION];
                $grp_str = "";
                foreach ($grps as &$group)
                    $grp_str .= "'" . $group . "', ";
                $grp_str = substr($grp_str, 0, strlen($grp_str) - 2);
                if ($this->body(USER_FIELD_ID)) {
                    $query = "SELECT * FROM `%s` WHERE `%s`!= '%s' AND `%s`== %d";
                    $query = sprintf($query, USER_GROUP_TABLE, KNIGHT_GRP_FIELD_NAME, GROUP_RECYCLE_BIN, USER_FIELD_ID, $this->body->{USER_FIELD_ID});
                }
                else {
                    $query = "SELECT * FROM `%s` WHERE `%s` IN (%s)";
                    $query = sprintf($query, USER_GROUP_TABLE, KNIGHT_GRP_FIELD_NAME, $grp_str);
                }
            }
            else {
                http_response_code(401);
                throw new Exception(ERR_ACCESS_DENIED);
            }
            $response = $this->connector->select($query, $this->get_user_group_parser());

        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        return $response;
    }
    /**
     * Registra un nuevo usuario en la base de datos
     *
     * @return string La respuesta del servidor
     */
    public function register_user()
    {
        try {
            $access = new AppAccess();
            //Se inserta en la tabla de knights
            $k_response = run_restricted_task($this->k_service, $access, GROUP_ADMIN_GRP, "join_the_realm", TRUE);
            if (has_result($k_response)) {
            //Se inserta en la tabla de usuarios
                inject_if_not_in($this->body, KNIGHT_FIELD_ID, $k_response->{NODE_RESULT}[0]->{KNIGHT_FIELD_ID});
                $this->POST->table_insert_fields = array(USER_FIELD_NAME, KNIGHT_FIELD_ID);
                $response = $this->POST->insert();
                $response = json_decode($response);
                inject_if_not_in($response, KNIGHT_FIELD_NAME, $this->body->{KNIGHT_FIELD_NAME});
                return json_encode($response);

            }
            else
                throw new Exception(sprintf(ERR_CREATING_USER, $k_response->{NODE_ERROR}));
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        return $response;
    }
    /**
     * Crea un administrador en la aplicación
     *
     * @return La respuesta del servidor
     */
    public function create_admin()
    {
        try {
            $access = new AppAccess();
        //Se inserta en la tabla de knights
            $k_response = run_restricted_task($this->k_service, $access, GROUP_SUPER, "join_the_realm", TRUE);
            if (has_result($k_response)) {
            //Se inserta en la tabla de usuarios
                inject_if_not_in($this->body, KNIGHT_FIELD_ID, $k_response->{NODE_RESULT}[0]->{KNIGHT_FIELD_ID});
                $this->POST->table_insert_fields = array(USER_FIELD_NAME, KNIGHT_FIELD_ID);
                $response = $this->POST->insert();
                $clv_usuario = $response->{NODE_RESULT}[0]->{USER_FIELD_ID};
                inject_if_not_in($response, KNIGHT_FIELD_NAME, $this->body->{KNIGHT_FIELD_NAME});
                //Se agregá la información del nuevo usuario y se agregá al grupo
                inject_if_not_in($response, USER_FIELD_ID, $clv_usuario);
                inject_if_not_in($response, KNIGHT_GRP_FIELD_NAME, GROUP_ADMIN_GRP);
                $response = $this->join_group();
            }
            else
                throw new Exception(sprintf(ERR_CREATING_USER, $k_response->{NODE_ERROR}));
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        return $response;
    }
    /**
     * Agregá un usuario a un grupo especifico
     *
     * @return The server response
     */
    public function join_group()
    {
        try {
            $access = new AppAccess();
            if (is_null($this->body)) {
                http_response_code(400);
                throw new Exception(ERR_NULL_BODY);
            }
            else if ($this->body_has(USER_FIELD_ID, KNIGHT_GRP_FIELD_NAME)) {
                $kId = $this->get_knight_id($this->body->{USER_FIELD_ID});
                $group_name = $this->body->{KNIGHT_GRP_FIELD_NAME};
                $response = $this->k_service->join_group($kId, $group_name);
                $response = json_decode($response);
                if (has_result($response)) {
                    $knight_id = $response->{NODE_RESULT}[0]->{KNIGHT_FIELD_ID};
                    $query = query_select_by_field(USER_GROUP_TABLE, KNIGHT_FIELD_ID, $knight_id);
                    $response = $this->connector->select($query, $this->get_user_group_parser());
                    $access->AddGroup($group_name);
                }
                else {
                    http_response_code(400);
                    throw new Exception($response->{NODE_ERROR});
                }
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
        $response = $this->connector->select($query, $this->get_user_group_parser());
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
    /**
     * Obtiene el id asignado en la tabla de knights
     * @param int $clv_usuario La clave de usuario
     * @return int El id del knight asignadao a una clave de usuario
     */
    public function get_knight_id($clv_usuario)
    {
        $query = "SELECT `%s` FROM `%s` WHERE `%s` = %d";
        $query = sprintf($query, KNIGHT_FIELD_ID, USER_TABLE, USER_FIELD_ID, $clv_usuario);
        $parser = $this->get_user_group_parser();
        $result = $this->connector->select_one($query);
        if (is_null($result))
            throw new Exception(sprintf(ERR_USER_MISSING, $clv_usuario));
        else
            return intval($result);
    }
    /**
     * Obtiene el parser que utiliza la tabla de grupo de usuarios
     *
     * @return El parser de la tabla grupo de usuarios
     */
    public function get_user_group_parser()
    {
        $u_g = new HasamiWrapper(USER_GROUP_TABLE, KNIGHT_FIELD_ID, new CDMXId());
        return $u_g->parser;
    }
    /**
     * Close the connection to MySQL
     * @return void
     */
    public function close()
    {
        $this->connector->close();
        $this->k_service->close();
    }


}
?>