<?php 
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../urabe/Warai.php";
include_once "/../../alice/Caterpillar.php";
include_once "/../Chivalry.php";
include_once "/../Excalibur.php";
include_once "/../Morgana.php";
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
     * @param string $url_params The url parameters
     */
    function __construct($url_params = NULL)
    {
        parent::__construct(KNIGHT_GRP_TABLE, KNIGHT_GRP_FIELD_ID, new Excalibur());
        $this->url_parameters = $url_params;
        //Se agregá seguridad al servicio de crear
        $this->POST->service_task = function ($sender) {
            return run_restricted_task(GROUP_AVALON, function () {
                return $this->POST->default_POST_action();
            });
        };
    }
}
?>