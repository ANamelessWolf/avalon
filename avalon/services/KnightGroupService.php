<?php 
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../urabe/Warai.php";
include_once "/../../alice/Caterpillar.php";
include_once "/../Chivalry.php";
include_once "/../Excalibur.php";
include_once "/../MorganaUtils.php";
/**
 * The Knight Group Service is in charge to administrate the users groups that are used on the Avalon database
 * A web service to administrate the knights_groups table
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class KnightGroupService extends HasamiWrapper
{
    /**
     * __construct
     *
     * Initialize a new instance of the Knight Service
     * @param KanojoX $db_id The connection object
     * @param string $url_params The url parameters
     */
    function __construct($url_params = NULL, $db_id = NULL)
    {
        if (is_null($db_id))
            $db_id = new Excalibur();
        parent::__construct(KNIGHT_GRP_TABLE, KNIGHT_GRP_FIELD_ID, $db_id);
        $this->url_parameters = $url_params;
        //Se agregá seguridad al servicio de crear
        $this->POST->service_task = function ($sender) {
            return run_restricted_task(GROUP_AVALON, function () {
                return $this->POST->default_POST_action();
            });
        };
    }

    /**
     * Gets the group id from a group name
     *
     * @param string $group_name The group name
     * @throws Exception An Exception is thrown if the group name is not defined
     * @return int The group id
     */
    public function GetGroupId($group_name)
    {
        $query = sprintf(
            "SELECT %s FROM %s WHERE %s = '%s'",
            KNIGHT_GRP_FIELD_ID,
            $this->table_name,
            KNIGHT_GRP_FIELD_NAME,
            $group_name
        );
        $id = $this->connector->select_one($query);
        if (is_null($id))
            throw new Exception(ERR_UNKNOWN_GROUP);
        else
            return intval($id);
    }

    /**
     * Checks if a user belongs to a group
     *
     * @param int $user_id The user id
     * @param int $group_id The group id
     * @return bool True if the user belongs to a group
     */
    public function belongs_to_group($user_id, $group_id)
    {
        $query = "SELECT COUNT(`%s`) FROM `%s` WHERE `%s`=%d AND `%s`=%d";
        $query = sprintf($query, KNIGHT_RANK_FIELD_ID, KNIGHT_RANK_TABLE, KNIGHT_FIELD_ID, $user_id, KNIGHT_GRP_FIELD_ID, $group_id);
        $result = $this->connector->select_one($query);
        return boolval($result);
    }

}
?>