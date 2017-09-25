<?php
include_once "MorganaUtils.php";
/**
 * This class manage the manage group access
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class MorganaAccess
{
    /**
     * @var string The application access groups
     */
    public $groups;
    /**
     * Initialize a new instance of the Morgana Access
     *
     * @param string[]|null $groups The application groups
     */
    public function __construct($groups = NULL)
    {
        if (is_null($groups)) {
            $this->groups == array(
                GROUP_AVALON => CAMELOT_KEY,
                GROUP_CHRONO => CHRONO_KEY,
                GROUP_NAMELESS => THE_NAMELESS_KEY
            );
        }
        else
            $this->groups = $groups;
    }
    /**
     * Sets the var on the session data var if the sessión value is equal to the condition
     *
     *  @param mixed[] $session_data The session data
     *  @param stdClass $result The query result
     *  @param string $key The session key value
     *  @param object $condition The condition to met
     *  @return void
     */
    public function SetData(&$session_data, $result, $key, $condition)
    {
        if ($session_data[$key] == $condition)
            $session_data[$key] = $result->$key;
    }
    /**
     * Sets the var on the session data var if the sessión value is equal to the condition
     *
     *  @param mixed[] $session_data The session data
     *  @param stdClass $result The query result
     *  @param string $default_key The default group key to be asigned when the group is not defined
     *  @return void
     */
    public function SetGroup(&$session_data, $result, $default_key = NULL)
    {
        if (array_key_exists($result->{KNIGHT_GRP_FIELD_NAME}, $this->groups))
            array_push($session_data[USR_ACCESS_SESSION], $this->groups[$result->{KNIGHT_GRP_FIELD_NAME}]);
        else {
            if (!is_null($default_key) && !in_array($default_key, $session_data[USR_ACCESS_SESSION]))
                array_push($session_data[USR_ACCESS_SESSION], $default_key);
            array_push($session_data[GRP_SESSION], $result->{KNIGHT_GRP_FIELD_NAME});
        }
    }
    /**
     * Checks if the user is permitted on a group
     *
     * @param string $group_name The name of the group
     * @param string $session_started If the session has not started a new one is started.
     * @return boolean True if the user is permitted on the group
     */
    public function is_permitted($group_name, $session_started = FALSE)
    {
        //There are two types of groups, level access and name access
        if (!$session_started)
            start_session();
        //Nameless always have access
        if (isset($_SESSION[USR_ACCESS_SESSION]) && in_array(THE_NAMELESS_KEY, $_SESSION[USR_ACCESS_SESSION]))
            return TRUE;
        //First check if is about a level access
        else if (array_key_exists($group_name, $this->groups) && isset($_SESSION[USR_ACCESS_SESSION])) {
            $grps = $_SESSION[USR_ACCESS_SESSION];
            return in_array($group_name, $this->groups);
        }
        //Now check group access
        else if (isset($_SESSION[GRP_SESSION])) {
            return in_array($_SESSION[GRP_SESSION], $group_name);
        }
        else
            return FALSE;
    }
}
?>