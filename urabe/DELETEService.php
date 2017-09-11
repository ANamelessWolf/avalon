<?php
include_once "Warai.php";
include_once "HasamiRESTfulService.php";
/**
 * DELETE Service Class
 * Defines a RESTful service with the DELETE verb that is used to delete data from the database. 
 * This service contains a collection of methods to do delete rows from a MySQL table.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class DELETEService extends HasamiRESTfulService
{
    /**
     * __construct
     *
     * Initialize a new instance of the DELETE Service class.
     * 
     * @param HasamiWrapper $web_service The web service wrapprer
     */
    public function __construct($web_service)
    {
        parent::__construct($web_service);
        $this->service_task = function ($sender) {
            return $this->default_DELETE_action();
        };
    }
    /**
     * Defines the default DELETE action. The action deletes one or more rows from the table using a condition 
     * where the primary key value has to be equal to the value contained in the body.
     * The values to update are taken from the body and must have the same keys as the table fields names.
     * @throws Exception An exception is thrown When the body is null or when an error occurred on the update method.
     * @return The server response
     */
    public function default_PUT_action()
    {
        try {
            if (is_null($this->web_service->body))
                throw new Exception(ERR_NULL_BODY);
            return $this->update_by_field($this->web_service->primary_key_name);
        } catch (Exception $e) {
            return error_response($e->getMessage());
        }
    }
    /**
     * Deletes one or more rows
     *
     * Executes a delete query to the database that matchs a $condition, the table name is read from the wrapper.
     * @param string $condition The condition to match.
     * @return string The server response
     */
    public function delete($condition)
    {
        $connector = $this->web_service->connector;
        $query_result = $connector->delete($this->web_service->table_name, $condition);
        $this->query_error = $connector->error;
        return $this->get_response_result($query_result);
    }
    /**
     * Deletes one or more rows
     *
     * Executes a delete query to the database, the table name is read from the wrapper and the condition values is obtained from the body.
     * The value on the body must have the same key as the table field name.
     * The update condition is defined as the $field_name equals to the $body->$field_name value.
     * @param string $field_name The name of the table field used as condition.
     * @throws Exception An exception is thrown When the body doesn't have enough data to make a delete value.
     * @return string The server response
     */
    public function delete_by_field($field_name)
    {
        if (!$this->web_service->body_has($field_name))
            throw new Exception(sprintf(ERR_INCOMPLETE_BODY_CONDITION, sprintf(ERR_INCOMPLETE_BODY_CONDITION, $field_name)));
        else {
            $connector = $this->web_service->connector;
            $query_result = $connector->delete_by_field($this->web_service->table_name, $field_name, $this->web_service->body->$field_name);
            $this->query_error = $connector->error;
            return $this->get_response_result($query_result);
        }
    }
    /**
     * Generates a response result from the query result.
     * 
     * @param bool $query_result The query result as a JSON string.
     * @return string The server response
     */
    public function get_response_result($query_result)
    {
        return $query_result;
    }
}