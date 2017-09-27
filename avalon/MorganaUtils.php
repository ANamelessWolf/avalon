<?php
include_once "/../urabe/Warai.php";
include_once "Chivalry.php";
/**
 * Morgana Session and Access Management
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
/****************************************
 ********* Application Keys *************
 ***************************************/
/**
 * @var string THE_NAMELESS_KEY
 * Gets the nameless KEY, can access any service
 */
const THE_NAMELESS_KEY = '{75EEB179-60A4-4485-9BE7-3D3E2DBCA757}';
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
 * @var string USR_ACCESS_SESSION
 * The app key field name to store the access keys for the given user
 */
const USR_ACCESS_SESSION = "user_access";
/**
 * @var string GRP_SESSION
 * The app key field name to store the name of the groups is allowed
 */
const GRP_SESSION = "groups";
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
 * Initialize the application session
 */
function start_session()
{
    if (session_id() == '')
        session_start();
}
/**
 * Close the application session
 * @param string[] The session field names
 * @return void
 */
function end_session($session_fields)
{
    start_session();
   //Always check this fields
    $special_fields = array(USR_ACCESS_SESSION, GRP_SESSION, USER_SESSION);
    foreach ($special_fields as &$field)
        if (!in_array($field, $session_fields))
        array_push($session_fields, $field);
    $count = count($session_fields);
    for ($i = 0; $i < $count; $i++)
        $_SESSION[$session_fields[$i]] = NULL;
    session_destroy();
}
/**
 * Checks the current session status
 *
 * @param string[] The session field names
 * @return mixed[] The session values
 */
function check_session($session_fields)
{
    start_session();
    $result = array();
    //Always check this fields
    $special_fields = array(USR_ACCESS_SESSION, GRP_SESSION, USER_SESSION);
    foreach ($special_fields as &$field)
        if (!in_array($field, $session_fields))
        array_push($session_fields, $field);
    //Get the session fields        
    $count = count($session_fields);
    for ($i = 0; $i < $count; $i++) {
        $key = $session_fields[$i];
        if (isset($_SESSION[$key]))
            $value = $_SESSION[$key];
        else
            $value = NULL;
        $result[$key] = $value;
    }
    return $result;
}
/**
 * Runs a task with restricted access,
 * Can recieved input parameters
 *
 * @param HasamiWrapper $sender The webservice that makes the request
 * @param MorganaAccess $access The application current access
 * @param string $group_name The group name that has permission to run the task
 * @param string $task The name of the task to run with restricted access
 * @param bool $json_decode If true the server response is returned as a PHP variable
 * @return string|stdClass The server response
 */
function run_restricted_task($sender, $access, $group_name, $task, $json_decode = FALSE)
{
    //Guardamos los parámetros de entrada en caso de existir
    $input = array();
    for ($i = 5; $i < func_num_args(); $i++)
        array_push($input, func_get_arg($i));
    //Se checan los permisos de acceso
    if ($access->is_permitted($group_name)) {
        $response = call_user_func_array(array($sender, $task), $input);
    }
    else {
        http_response_code(403);
        $response = error_response(ERR_ACCESS_DENIED);
    }
    if ($json_decode)
        $response = json_decode($response);
    return $response;
}



?>