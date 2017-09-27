<?php 
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../urabe/Warai.php";
include_once "/../Chivalry.php";
include_once "/../Excalibur.php";
include_once "KnightGroupService.php";
/**
 * The Knight Service ranking is in charge to create and manage links between the users and the groups
 * A web service to administrate the Knight ranking table
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class KnightServiceRanking extends HasamiWrapper
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
        parent::__construct(KNIGHT_RANK_TABLE, KNIGHT_RANK_FIELD_ID, $db_id);
        $this->url_parameters = $url_params;
        $post_query = sprintf("SELECT * FROM `%s` ORDER BY %s DESC LIMIT %d", $this->table_name, $this->primary_key_name, 1);
    }
    /**
     * Adds a user to a group using its ids
     *
     * @param int $user_id The user group
     * @param int $group_id The group id
     * @return string The server response
     */
    public function create_link($user_id, $group_id)
    {
        $this->body = (object)array(KNIGHT_FIELD_ID => $user_id, KNIGHT_GRP_FIELD_ID => $group_id);
        return $this->POST->default_POST_action();
    }
}