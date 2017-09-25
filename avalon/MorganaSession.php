<?php

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
     * @var string[] The user access keys
     */
    public $user_access;
    /**
     * @var string[] The groups that the user has access
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
     * @param string[] $user_access The user access keys
     * @param string[] $groups The name of the groups that the user belongs
     */
    public function __construct($user_name, $user_access, $groups)
    {
        $this->user_name = $user_name;
        $this->groups = $groups;
        $this->user_access = $user_access;
        $this->session_fields = array(USR_ACCESS_SESSION, GRP_SESSION, USER_SESSION);
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
        $_SESSION[USR_ACCESS_SESSION] = $this->user_access;
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
            $key = $this->session_fields[$i];
            if (isset($_SESSION[$key]))
            $value = $_SESSION[$key];
        else
            $value = NULL;
            $result[$key] = $value;
        }
        return $result;
    }
}
?>