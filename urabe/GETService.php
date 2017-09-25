<?php
include_once "Warai.php";
include_once "Selection.php";
include_once "HasamiRESTfulService.php";
/**
 * GET Service Class
 * Defines a RESTful service with the GET verb that is often used to **select** data from the database. 
 * This service contains a collection of methods to do a selection to a MySQL table.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class GETService extends HasamiRESTfulService
{
    /**
     * @var Selection[] gets or sets the selections that depends on the use of
     * GET variables.
     */
    public $selections_by_get_vars;
    /**
     * __construct
     *
     * Initialize a new instance of the GET Service class.
     * 
     * @param HasamiWrapper $web_service The web service wrapprer
     */
    public function __construct($web_service)
    {
        parent::__construct($web_service);
        $this->selections_by_get_vars = array();
        $this->service_task = function ($sender) {
            return $this->default_GET_action();
        };
    }
    /**
     * Defines the default GET action. The action selects all fields from the table without using url parameter options or GET
     * variables.
     *
     * @return The server response
     */
    public function default_GET_action()
    {
        try {
            $service = $this->web_service;
            $url_params = $service->url_parameters;
            if (!is_null($url_params) && $url_params->exists($service->primary_key_name)) {
                $id = $url_params->parameters[$service->primary_key_name];
                return $this->select_by_primary_key($id);
            }
            else {
                $selection = selection_all($this);
                return $selection->get_response();
            }
        } catch (Exception $e) {
            return error_response($e->getMessage());
        }
    }
    /**
     * Gets the first selection that matches the selection condition. 
     * The selection is managed in the order that was added.
     * @return string The server response
     */
    public function select_with_GET_vars()
    {
        $selection_count = count($this->selections_by_get_vars);
        $condition_met = FALSE;
        $selection = NULL;
        for ($i = 0; is_null($selection) && $i < $selection_count; $i++) {
            $condition_met = $this->selections_by_get_vars[$i]->condition();
            if ($condition_met)
                $selection = $this->selections_by_get_vars[$i];
        }
        if (is_null($selection))
            return $selection;
        else
            return empty_response();
    }
    /**
     * Select all fields from the table that matches primary key value. 
     *
     * @param int $key_value The primary key value
     * @param bool $decode_json Enables to get the response decoded as a PHP variable
     * @return string|stdClass The server response
     */
    public function select_by_primary_key($key_value, $decode_json = FALSE)
    {
        try {
            $service = $this->web_service;
            $query = "SELECT * FROM `%s` WHERE `%s` = $key_value";
            $query = sprintf($query, $service->table_name, $service->primary_key_name);
            $response = $service->connector->select($query, $service->parser);
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        if ($decode_json)
            $response = json_decode($response);
        return $response;
    }
}
?>