<?php
include_once "Warai.php";
include_once "HasamiUtils.php";
/**
 * Hasami RESTful Service Class
 *
 * Creates and manage a simple REST service that makes a transaction to a MySQL database.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class HasamiRESTfulService
{
    /**
     * @var HasamiWrapper Access to the web service wrapper
     */
    public $web_service;
    /**
     * @var string Saves the last error executed on a query..
     */
    protected $query_error;
    /**
     * @var callback Defines the service task
     * (HasamiRESTfulService $service): string
     */
    public $service_task;
    /**
     * __construct
     *
     * Initialize a new instance of the Hasami RESTful service class.
     * 
     * @param HasamiWrapper $web_service The web service wrapprer
     */
    public function __construct($web_service)
    {
        $this->web_service = $web_service;
    }
    /**
     * Extract the values from the body in the same order that the $table_fields are defined.
     * @param string[] $table_fields The name of the table fields in a given order.
     * @param stdClass $body The message body
     * @return string[] The values sorted.
     */
    public function extract_values($table_fields, $body = NULL)
    {
        if (is_null($body))
            $body = $this->web_service->body;
        $values = array();
        foreach ($table_fields as &$field_name) {
            if (property_exists($body, $field_name))
                array_push($values, $body->$field_name);
        }
        if (count($table_fields) != count($values))
            throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_INSERT, $this->concat_fields($this->table_insert_fields)));
        return $values;
    }
    /**
     * Gets the service response
     *
     * @return The server response
     */
    public function get_response_result()
    {
        if (!is_null($this->service_task))
            $result = call_user_func_array($this->service_task, array($this));
        else
            $result = empty_response();
        return $result;
    }
    /**
     * Concatenate a collection of fields with comas
     *
     * @param string[]|int[] $fields The fields to concatenate
     * @return string The fields concatenated
     */
    protected function concat_fields($fields)
    {
        $str = "";
        foreach ($fields as &$value)
            $str .= $value . ", ";
        return substr($str, 0, strlen($str) - 2);
    }

}