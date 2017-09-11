<?php
include_once "Warai.php";
include_once "HasamiRESTfulService.php";
/**
 * PUT Service Class
 * Defines a RESTful service with the PUT verb that is used to update data to the database. 
 * This service contains a collection of methods to do updates to a MySQL table.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class PUTService extends HasamiRESTfulService
{
    /**
     * @var string[] The name of the fields to update 
     */
    public $table_update_fields;
    /**
     * __construct
     *
     * Initialize a new instance of the PUT Service class.
     * 
     * @param HasamiWrapper $web_service The web service wrapprer
     */
    public function __construct($web_service)
    {
        parent::__construct($web_service);
        $this->service_task = function ($sender) {
            return $this->default_PUT_action();
        };
    }
    /**
     * Defines the default PUT action. The action updates one or more rows from the table using a condition 
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
            $this->table_update_fields = remove_from_array($this->web_service->table_field_names, $this->web_service->primary_key_name);
            return $this->update_by_field($this->web_service->primary_key_name);
        } catch (Exception $e) {
            return error_response($e->getMessage());
        }
    }
    /**
     * Updates one or more rows
     *
     * Executes an update query to the database that matchs a $condition, the table name is read from the wrapper and the updates values are obtained from the body.
     * The values on the body must have the same keys as the table fields names.
     * The field names are defined on the variable $this->table_update_fields
     * @param string $condition The condition to match.
     * @throws Exception An exception is thrown When the body doesn't have enough data to make an update.
     * @return string The server response
     */
    public function update($condition)
    {
        if (is_null($this->table_update_fields))
            throw new Exception(ERR_MISS_UPDATE_FIELDS);
        else {
            if ($this->input_valid()) {
                $values = $this->extract_values($this->table_update_fields);
                $connector = $this->web_service->connector;
                $query_result = $connector->update($this->web_service->table_name, $this->table_update_fields, $values, $condition);
                $this->query_error = $connector->error;
                return $this->get_response_result($query_result);
            }
            else
                throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_UPDATE, $this->concat_fields($this->table_update_fields)));
        }
    }
    /**
     * Updates one or more rows
     *
     * Executes an update query to the database, the table name is read from the wrapper and the updates values are obtained from the body.
     * The values on the body must have the same keys as the table fields names.
     * The field names are defined on the variable $this->table_update_fields
     * The update condition is defined as the $field_name equals to the $body->$field_name value.
     * @param string $field_name The name of the table field used as condition.
     * @throws Exception An exception is thrown When the body doesn't have enough data to make an update or when the body doesn't have the $field_name value.
     * @return string The server response
     */
    public function update_by_field($field_name)
    {
        if (is_null($this->table_update_fields))
            throw new Exception(ERR_MISS_UPDATE_FIELDS);
        else if (!$this->web_service->body_has($field_name))
            throw new Exception(sprintf(ERR_INCOMPLETE_BODY_CONDITION, sprintf(ERR_INCOMPLETE_BODY_CONDITION, $field_name)));
        else {
            if ($this->input_valid()) {
                $values = $this->extract_values($this->table_update_fields);
                $connector = $this->web_service->connector;
                $query_result = $connector->update_by_field($this->web_service->table_name, $this->table_update_fields, $values, $field_name, $this->web_service->body->$field_name);
                $this->query_error = $connector->error;
                return $this->get_response_result($query_result);
            }
            else
                throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_UPDATE, $this->concat_fields($this->table_update_fields)));
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
        $json_result = json_decode($query_result);
        if ($json_result->{NODE_QUERY_RESULT}) {
            $result = array();
            foreach ($this->web_service->body as $key => $value)
                if (in_array($key, $this->table_update_fields))
                $result[$key] = $value;
            $json_result->{NODE_RESULT} = $result;
            return json_encode($json_result);
        }
        else
            return $query_result;
    }
    /**
     * Validates body
     *
     * Check if all values needed to update are defined on the body.
     * @param stdClass $body The message body
     * @return string TRUE when the body has all the information to update.
     */
    private function input_valid($body = NULL)
    {
        $inser_update_count = count($this->table_update_fields);
        $body_count = 0;
        if (is_null($body))
            $body = $this->web_service->body;
        foreach ($body as $key => $value)
            if (in_array($key, $this->table_update_fields))
            $body_count++;
        retupdate == $body_count;
    }
}
?>