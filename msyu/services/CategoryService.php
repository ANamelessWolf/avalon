<?php
include_once "/../../urabe/HasamiWrapper.php";
include_once "/../../urabe/Warai.php";
include_once "/../../avalon/Excalibur.php";
include_once "/../Mashu.php";
/**
 * The Category Service is in charge to administrate nameless stock categories
 * A web service to administrate the Category table
 * @version 1.0.0
 * @api Msyu
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class CategoryService extends HasamiWrapper
{
    /**
     * __construct
     *
     * Initialize a new instance of the Category Service
     * @param HasamiURLParameters $url_params The url parameters
     * @param KanojoX $db_id The connection object
     */
    function __construct($url_params = NULL, $db_id = NULL)
    {
        if (is_null($db_id))
            $db_id = new Excalibur();
        parent::__construct(CATEGORY_TABLE, CATEGORY_FIELD_ID, $db_id);
        $this->url_parameters = $url_params;
        $this->POST->service_task = function ($sender) {
            return send_service_petition($this, F_POST);
        };
        $this->GET->service_task = F_GET;
    }
    /**
     * Gets the service response
     *
     * @param string $CategoryService The category service
     * @return The service response
     */
    public function GET_action($service)
    {
        $categories = array();
        if ($this->url_parameters->exists(CATEGORY_FIELD_ID)) {
            $cat = NULL;
            $cat_id = $this->url_parameters->parameters[CATEGORY_FIELD_ID];
            $parent_cat;
            do {
                $res = $this->ParseCategory($this->GET->select_by_primary_key($cat_id, TRUE));
                $cat_id = $res->{CATEGORY_FIELD_PARENT_ID};
                if (is_null($cat)) {
                    $cat = $res;
                    $parent_cat = $res;
                }
                else {
                    $parent_cat->{CATEGORY_FIELD_PARENT_ID} = $res;
                    $parent_cat = $res;
                }
            } while ($cat_id != NULL);
            array_push($categories, $cat);
        }
        else
            throw new Exception(sprintf(ERR_MISS_PARAM, CATEGORY_FIELD_ID));
        return service_response($categories, TRUE);
    }
    /**
     * Parse a category from a query result
     *
     * @param stdclass $result The server response
     * @return stdclass The selected category
     */
    public function ParseCategory($response)
    {
        if (has_result($response))
            return $response->{NODE_RESULT}[0];
        else
            throw new Exception(ERR_CAT_MISSING);
    }
    /**
     * Defines the post action service
     *
     * @param string $task The selected task
     * @return The service response
     */
    public function POST_action($task)
    {
        if ($task == TASK_CREATE) {
            return $this->POST->default_POST_action();
        }
        else
            throw new Exception(ERR_TASK_UNDEFINED);
    }
}
?>