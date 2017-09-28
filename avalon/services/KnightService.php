<?php 
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../urabe/Warai.php";
include_once "/../../alice/Caterpillar.php";
include_once "/../Chivalry.php";
include_once "/../Excalibur.php";
include_once "/../MorganaUtils.php";
include_once "/../MorganaAccess.php";
include_once "KnightGroupService.php";
include_once "KnightServiceRanking.php";
/**
 * The Knight Service is in charge to administrate the users that are used on the Avalon database
 * A web service to administrate the Knight service table
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class KnightService extends HasamiWrapper
{
    /**
     * __construct
     *
     * Initialize a new instance of the Knight Service
     * @param HasamiURLParameters $url_params The url parameters
     * @param KanojoX $db_id The connection object
     */
    function __construct($url_params = NULL, $db_id = NULL)
    {
        if (is_null($db_id))
            $db_id = new Excalibur();
        parent::__construct(KNIGHT_TABLE, KNIGHT_FIELD_ID, $db_id);
        $this->url_parameters = $url_params;
        $this->POST->service_task = function ($sender) {
            return $this->POST_action();
        };
    }
    /**
     * Defines the post action of the service
     *
     * @return string The service response
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
                $access = new MorganaAccess();
                switch ($task) {
                    case TASK_ADD :
                        $response = run_restricted_task($this, $access, GROUP_AVALON, "join_the_realm");
                        break;
                    case TASK_SELECT :
                        $response = run_restricted_task($this, $access, GROUP_AVALON, "get_user_id");
                        break;
                    case TASK_LINK :
                        $response = run_restricted_task($this, $access, GROUP_AVALON, "join_group");
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
     * This function adds a new user to the database
     * @param string $user_name The user name, if this is null the value is extracted from the body.
     * @param string $password The user password, if this is null the value is extracted from the body.
     * @return string The server response
     */
    public function join_the_realm($user_name = NULL, $password = NULL)
    {
        $this->POST->table_insert_fields = array(KNIGHT_FIELD_NAME, KNIGHT_FIELD_PASS);
        inject_if_not_in($this->body, KNIGHT_FIELD_NAME, $user_name);
        inject_if_not_in($this->body, KNIGHT_FIELD_PASS, $password);
        if (is_null($this->body)) {
            http_response_code(400);
            throw new Exception(ERR_BODY_IS_NULL);
        }
        //Password must be encrypted
        $cat = new Caterpillar();
        $this->body->{KNIGHT_FIELD_PASS} = $cat->encrypt($this->body->{KNIGHT_FIELD_PASS});
        $response = $this->POST->insert();
        $response = json_decode($response);
        //Ocultamos el password
        unset($response->{NODE_RESULT}[0]->{KNIGHT_FIELD_PASS});
        return json_encode($response);
    }
    /**
     * Gets the group id from a user name
     *
     * @param string $user_name The user name
     * @return string The server response
     */
    public function get_user_id($user_name = NULL)
    {
        inject_if_not_in($this->body, KNIGHT_FIELD_NAME, $user_name);
        $query = "SELECT %s FROM %s WHERE %s = '%s'";
        $query = sprintf(
            $query,
            KNIGHT_FIELD_ID,
            $this->table_name,
            KNIGHT_FIELD_NAME,
            $this->body->{KNIGHT_FIELD_NAME}
        );
        return $this->connector->select($query, $this->parser);
    }
    /**
     * This function adds a user to a group by its id
     *
     * @param int $user_id The user id
     * @param string $group_name The name of the group
     * @return string The server response
     */
    public function join_group($user_id = NULL, $group_name = NULL)
    {
        $g_service = new KnightGroupService($this->url_parameters, $this->connector->database_id);
        try {
            inject_if_not_in($this->body, KNIGHT_GRP_FIELD_NAME, $group_name);
            inject_if_not_in($this->body, KNIGHT_FIELD_ID, $user_id);
            //Se obtiene el id del grupo para crear el link
            $group_name = $this->body->{KNIGHT_GRP_FIELD_NAME};
            $g_id = $g_service->GetGroupId($group_name);
            $r_service = new KnightServiceRanking($this->url_parameters, $this->connector->database_id);
            $response = $r_service->create_link($this->body->{KNIGHT_FIELD_ID}, $g_id);
            $r_service->close();
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        $g_service->close();
        return $response;
    }
    /**
     * Login to the server using a user name and a password
     * @param string $user_name The user name
     * @param string $password The user password
     * @return stdClass La respuesta del servidor
     */
    public function login($user_name, $password)
    {
        $query = "SELECT `%s` FROM `%s` WHERE `%s`= '%s' AND `%s`= '%s'";
        $cat = new Caterpillar();
        $password = $cat->encrypt($password);
        $query = sprintf($query, KNIGHT_FIELD_ID, KNIGHT_TABLE, KNIGHT_FIELD_NAME, $user_name, KNIGHT_FIELD_PASS, $password);
        $response = $this->connector->select($query);
        return json_decode($response);
    }
    /**
     * Updates the password of the user
     *
     * @param int|null $k_id The knight id
     * @param string|null $new_password The new password not encrypted
     * @param bool $json_decode True if the result is decoded as a PHP variable
     * @return string|stdClass The server response
     */
    public function update_password($k_id = NULL, $new_password = NULL, $json_decode = FALSE)
    {
        try {
            inject_if_not_in($this->body, KNIGHT_FIELD_ID, $k_id);
            inject_if_not_in($this->body, KNIGHT_FIELD_PASS, $new_password);
            //Se encripta el nuevo password
            $cat = new Caterpillar();
            $this->body->{KNIGHT_FIELD_PASS} = $cat->encrypt($this->body->{KNIGHT_FIELD_PASS});
            $this->PUT->table_update_fields = array(KNIGHT_FIELD_PASS);
            $response = $this->PUT->update_by_field(KNIGHT_FIELD_ID);
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        return $json_decode ? json_decode($response) : $response;
    }
}
?>