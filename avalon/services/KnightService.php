<?php 
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../urabe/Warai.php";
include_once "/../../alice/Caterpillar.php";
include_once "/../Chivalry.php";
include_once "/../Excalibur.php";
include_once "/../Morgana.php";
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
            unset($result[0]->{KNIGHT_FIELD_PASS});
            return json_encode($result);
        }, $user_name, $password);
    }
}
?>