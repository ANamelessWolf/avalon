<?php
include_once "Warai.php";
include_once "HasamiRESTfulService.php";
/**
 * Selection View Class
 * Defines a selection to a database that use a GET service. 
 * This service defines a selection to a MySQL table.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class Selection
{
    /**
     * @var string Defines a selection query.
     */
    public $query;
    /**
     * @var mixed[] Defines the vars used on the selection query.
     */
    public $get_vars;
    /**
     * @var GETService The webservice GET action
     */
    public $GET;
    /**
     * @var callback Defines the condition that must be match to use the selection
     */
    public $condition;
    /**
     * __construct
     *
     * Initialize a new instance of the Selection class.
     * 
     * @param GETService $service The GET service.
     * @param string $query The selection query.
     * @param string $vars GET variables used on the selection query.
     */
    public function __construct($service, $query, $vars)
    {
        $this->GET = $service;
        $this->query = $query;
        $this->get_vars = array();
        $num_args = func_num_args();
        if (!is_null($vars)) {
            for ($i = 2; $i < $num_args; $i++) {
                $var = func_get_arg($i);
                if (isset($_GET[$var]))
                    $this->get_vars[$var] = $_GET[$var];
                else
                    $this->get_vars[$var] = NULL;
            }
        }
        $this->condition = function () {
            return $this->is_valid();
        };
    }
    /**
     * Gets the server response
     */
    public function get_response()
    {
        $connector = $this->GET->web_service->connector;
        $parser = $this->GET->web_service->parser;
        $keys = array();
        $values = array();
        foreach ($this->get_vars as $key => $value) {
            array_push($keys, "@" . $key);
            array_push($values, $this->get_vars[$key]);
        }
        $query = str_replace($keys, $values, $this->query);
        return $connector->select($this->query, $parser);
    }
    /**
     * Check if one or more properties are defined on the GET variables
     * 
     * @return bool TRUE if all variables are defined in the GET variables.
     */
    public function is_valid()
    {
        $flag = TRUE;
        foreach ($this->get_vars as $key => $value)
            $flag = $flag && !is_null($value);
        return $flag;
    }
}
?>