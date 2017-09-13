<?php 
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../urabe/Warai.php";
include_once "/../../alice/Caterpillar.php";
include_once "/../Chivalry.php";
include_once "/../Excalibur.php";
include_once "/../Morgana.php";
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
     * @param string $url_params The url parameters
     */
    function __construct($url_params = NULL)
    {
        parent::__construct(KNIGHT_TABLE, KNIGHT_FIELD_ID, new Excalibur());
        $this->url_parameters = $url_params;
        $this->POST->service_task = function ($sender) {
            return $this->POST_action();
        };
    }
    /**
     * Define la acción de POST del servicio Knight
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
                    case TASK_ADD :
                        $response = $this->join_the_realm();
                        break;
                    case TASK_SELECT :
                        $response = $this->get_user_id();
                        break;
                    case TASK_LINK :
                        $response = $this->join_group();
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
        return run_restricted_task(GROUP_AVALON, function ($u, $p) {
            $this->POST->table_insert_fields = array(KNIGHT_FIELD_NAME, KNIGHT_FIELD_PASS);
            inject_if_not_in($this->body, KNIGHT_FIELD_NAME, $u);
            inject_if_not_in($this->body, KNIGHT_FIELD_PASS, $p);
            //Password must be encrypted
            $cat = new Caterpillar();
            $this->body->{KNIGHT_FIELD_PASS} = $cat->encrypt($this->body->{KNIGHT_FIELD_PASS});
            $result = $this->POST->insert();
            $result = json_decode($result);
            //Ocultamos el password
            unset($result->{NODE_RESULT}[0]->{KNIGHT_FIELD_PASS});
            return json_encode($result);
        }, $user_name, $password);
    }
    /**
     * Gets the group id from a user name
     *
     * @param string $user_name The user name
     * @return string The server response
     */
    public function get_user_id($user_name = NULL)
    {
        return run_restricted_task(GROUP_AVALON, function ($u) {
            inject_if_not_in($this->body, KNIGHT_FIELD_NAME, $u);
            $query = "SELECT %s FROM %s WHERE %s = '%s'";
            $query = sprintf(
                $query,
                KNIGHT_FIELD_ID,
                $this->table_name,
                KNIGHT_FIELD_NAME,
                $this->body->{KNIGHT_FIELD_NAME}
            );
            return $this->connector->select($query, $this->parser);
        }, $user_name);
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
        return run_restricted_task(GROUP_AVALON, function ($u_id, $g_name) {
            $g_service = new KnightGroupService(NULL);
            try {
                inject_if_not_in($this->body, KNIGHT_GRP_FIELD_NAME, $group_name);
                inject_if_not_in($this->body, KNIGHT_FIELD_ID, $u_id);
                //Se obtiene el id del grupo para crear el link
                $g_name = $this->body->{KNIGHT_GRP_FIELD_NAME};
                $g_id = $g_service->GetGroupId($g_name);
                $r_service = new KnightServiceRanking();
                $response = $r_service->create_link($this->body->{KNIGHT_FIELD_ID}, $g_id);
                $r_service->close();
            } catch (Exception $e) {
                $response = error_response($e->getMessage());
            }
            $g_service->close();
        }, $user_id, $group_name);
    }
}
?>