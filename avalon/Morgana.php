<?php
include_once "/../urabe/Warai.php";
/**
 * Morgana Session and Access Management
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
/**
 * @var string ADMIN_KEY
 * Gets the administrator KEY, can access any service
 */
const ADMIN_KEY = '{75EEB179-60A4-4485-9BE7-3D3E2DBCA757}';
/**
 * @var string CAMELOT_KEY
 * Gets the camelot user key, can access avalon services
 */
const CAMELOT_KEY = '{D1810785-749D-4345-BD70-465759BB67B4}';
/**
 * @var string CHRONOT_KEY
 * Gets the chrono user key, can access chrono services
 */
const CHRONO_KEY = '{C27F00C6-F8F9-47cc-A1BF-1D0178275B75}';
/****************************************
 ********* Session field names **********
 ***************************************/
/**
 * @var string USER_SESSION
 * The app key field name to store a user name
 */
const USER_SESSION = 'user_name';
/**
 * @var string GRP_SESSION
 * The app key field name to store a list of groups
 */
const GRP_SESSION = "GROUP";
/****************************************
 ************** Group names *************
 ***************************************/
/**
 * @var string GROUP_AVALON
 * The name of the group who has access to avalon services
 */
const GROUP_AVALON = "Avalon";
/**
 * @var string GROUP_AVALON
 * The name of the group who has access to chrono services
 */
const GROUP_CHRONO = "Chrono";
/**
 * @var string GROUP_NAMELESS
 * The name of the group who has access to everything
 */
const GROUP_NAMELESS = "Nameless";
/**
 * Check if a user is permitted on a group
 * @param string $group_name The name of the group
 * @return boolean True if the logged user is permitted on the group
 */
function is_permitted($group_name)
{
    start_session();
    if (isset($_SESSION[GRP_SESSION])) {
        $grps = $_SESSION[GRP_SESSION];
        return in_array(GROUP_NAMELESS, $grps) || is_in_group($group_name, $grps);
    }
    else
        return FALSE;
}
/**
 * Gets the application group names
 *
 * @return The application group names associated to a group key
 */
function get_group_names()
{
    $group_by_names = array(
        GROUP_AVALON => CAMELOT_KEY,
        GROUP_CHRONO => CHRONO_KEY,
        GROUP_NAMELESS => ADMIN_KEY
    );
    return $group_by_names;
}
/**
 * Check if an user has access to a group by name
 *
 * @param string $group_name The name of the group
 * @param string[] $grps The list of groups where the user is allowed
 * @return boolean True if the user is on the group
 */
function is_in_group($group_name, $grps)
{
    return array_key_exists($group_name, get_group_names()) && in_array($group_by_names[$group_name], $grps);
}
/**
 * Initialize the application session
 */
function start_session()
{
    if (session_id() == '')
        session_start();
}
/**
 * Runs a task with restricted access
 *
 * @param string $group_name allowed group
 * @param callback $task The task to call
 * @param mixed $params The parameters that receive the task
 * @return string The server response
 */
function run_restricted_task($group_name, $task, $params = NULL)
{
    if (is_permitted($group_name)) {
        //Se agregan los parámetros dinámicos que recibe la función
        $input = array();
        $num_args = func_num_args();
        for ($i = 2; $i < $num_args; $i++) {
            $input_param = func_get_arg($i);
            array_push($input, $input_param);
        }
        return  call_user_func_array($task, $input);
    }
    else {
        http_response_code(403);
        return error_response(ERR_ACCESS_DENIED);
    }
}
/**
 * This class manage the authentication and method access while running scripts
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class MorganaSession
{
    /**
     * @var string[] The session field names
     */
    public $session_fields;
    /**
     * @var string The user name
     */
    public $user_name;
    /**
     * @var string[] The name of the groups the user is allowed
     */
    public $groups;
    /**
     * @var callback Defines the login task
     * (HasamiRESTfulService $service): string
     */
    public $login_task;
    /**
     * Initialize a new instance of the Morgana Session
     *
     * @param string $user_name The user name
     * @param string[] $groups The name of the groups in which the user is allowed
     */
    public function __construct($user_name, $groups)
    {
        $this->user_name = $user_name;
        $this->groups = $groups;
        $this->session_fields = array(GRP_SESSION, USER_SESSION);
    }
    /**
     * Login to the current server
     * @param mixed[]|null $input The login input parameters
     * @return void
     */
    public function login($input = NULL)
    {
        start_session();
        if (!is_null($this->login_task))
            call_user_func_array($this->login_task, array($input));
        //Registramos al usuario
        $_SESSION[USER_SESSION] = $this->user_name;
        $_SESSION[GRP_SESSION] = $this->groups;
    }
    /**
     * Logout from the server
     * @return void
     */
    public function logout()
    {
        start_session();
        $count = count($this->session_fields);
        for ($i = 0; $i < $count; $i++)
            $_SESSION[$this->session_fields[$i]] = NULL;
        session_destroy();
    }
    /**
     * Check the current session status
     * 
     * @return mixed[] The session status
     */
    public function check_status()
    {
        start_session();
        $result = array();
        $count = count($this->session_fields);
        for ($i = 0; $i < $count; $i++) {
            $key = $this->session_fields[i];
            $value = $_SESSION[$key];
            $result[$key] = $value;
        }
        return $result;
    }
}
?>