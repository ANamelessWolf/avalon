<?php
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../avalon/Excalibur.php";
include_once "/../Mashu.php";
/**
 * The Unit Service is in charge to administrate nameless service category
 * A web service to administrate the Unit table
 * @version 1.0.0
 * @api Msyu
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class UnitService extends HasamiWrapper
{
    /**
     * __construct
     *
     * Initialize a new instance of the Unit Service
     * @param HasamiURLParameters $url_params The url parameters
     * @param KanojoX $db_id The connection object
     */
    function __construct($url_params = NULL, $db_id = NULL)
    {
        if (is_null($db_id))
            $db_id = new Excalibur();
        parent::__construct(UNIT_TABLE, UNIT_FIELD_ID, $db_id);
        $this->url_parameters = $url_params;
    }
}
?>