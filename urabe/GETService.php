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
     * @var callback Defines the url parameter selection
     * (HasamiWrapper $service)::string
     */
    public $url_parameter_selection;
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
            $selection = selection_all($this);
            return $selection->get_response();
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
     * Gets a selection query using the url paramaters
     * @throws Exception An exception is thrown when the url parameter method (selection_by_url_parameters) is not defined.
     * @return string The server response
     */
    public function select_with_URL_parameters_vars()
    {
        if (is_null($this->url_parameter_selection))
            throw new Exception(sprintf(ERR_METHOD_NOT_DEFINED, "selection_by_url_parameters"));
        else
            return $this->url_parameter_selection($this->web_service);
    }
    /**
     * Adds a new GET var type selection
     *
     * @param Selection $selection
     * @return void
     */
    public function add_GET_vars_type_selection($selection)
    {
        array_push($this->selections_by_get_vars, $selection);
    }
    /**
     * Removes a GET var type selection
     *
     * @param int $index The index of the selection to remove
     * @throws Exception An exception is thrown when the index is out of bounds.
     * @return void
     */
    public function remove_at_GET_vars_type_selection($index)
    {
        $selection_count = count($this->selections_by_get_vars);
        if ($index < 0 || $index >= $selection_count)
            throw new Exception(sprintf(ERR_BAD_INDEX, $index));
        unset($this->selections_by_get_vars[$i]);
        $this->selections_by_get_vars = array_values($this->selections_by_get_vars);
    }
}
?>