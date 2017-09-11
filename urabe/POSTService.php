<?php
include_once "Warai.php";
include_once "HasamiRESTfulService.php";
/**
 * POST Service Class
 * Defines a RESTful service with the POST verb that is often used to **create** new data to the database. 
 * This service contains a collection of methods to do inserts to a MySQL table.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class POSTService extends HasamiRESTfulService
{
    /**
     * @var string[] The name of the fields to insert 
     */
    public $table_insert_fields;
    /**
     * @var string Once the data is inserted, the data is returned and ordered by this field name.
     */
    public $order_result_field_name;
    /**
     * __construct
     *
     * Initialize a new instance of the POST Service class.
     * 
     * @param HasamiWrapper $web_service The web service wrapprer
     */
    public function __construct($web_service)
    {
        parent::__construct($web_service);
        $this->service_task = function ($sender) {
            return $this->default_POST_action();
        };
    }
    /**
     * Defines the default post action. The action inserts an entry to the table specifying all the table fields 
     * on the body excepts by the primary key.
     *
     * @throws Exception An exception is thrown When the body is null or when an error occurred on the insert method.
     * @return The server response
     */
    public function default_POST_action()
    {
        try {
            if (is_null($this->web_service->body))
                throw new Exception(ERR_NULL_BODY);
            $this->table_insert_fields = remove_from_array($this->web_service->table_field_names, $this->web_service->primary_key_name);
            return $this->insert();
        } catch (Exception $e) {
            return error_response($e->getMessage());
        }
    }
    /**
     * Insert a new entry to the database
     *
     * Executes an insert query to the database, the table name is read from the wrapper and the insertion values are obtained from the body.
     * The values on the body must have the same keys as the table fields names.
     * The field names are defined on the variable $this->table_insert_fields
     * @throws Exception An exception is thrown When the body doesn't have enough data to make an insert.
     * @return string The server response
     */
    public function insert()
    {
        if (is_null($this->table_insert_fields))
            throw new Exception(ERR_MISS_INSERT_FIELDS);
        else {
            if ($this->input_valid()) {
                $values = $this->extract_values($this->table_insert_fields);
                $connector = $this->web_service->connector;
                $query_result = $connector->insert($this->web_service->table_name, $this->table_insert_fields, $values);
                $this->query_error = $connector->error;
                return $this->get_response_result($query_result);
            }
            else
                throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_INSERT, $this->concat_fields($this->table_insert_fields)));
        }
    }
    /**
     * Inserts a bulk of data
     *
     * Executes an insert query to the database, the table name is read from the wrapper and the insertion values are obtained from the body.
     * The body must be a JSON array, to allows to iterate to each insert value.
     * The values on the body must have the same keys as the table fields names.
     * The field names are defined on the variable $this->table_insert_fields
     * @throws Exception An exception is thrown When the body isn't an array or when it doesn't have enough data to make an insert.
     * @return string The server response
     */
    public function insert_bulk()
    {
        if (is_null($this->table_insert_fields))
            throw new Exception(ERR_MISS_INSERT_FIELDS);
        else if (!is_array($this->web_service->body))
            throw new Exception(ERR_BODY_NOT_ARRAY);
        else {
            $bulk = array();
            $connector = $this->web_service->connector;
            foreach ($this->web_service->body as &$value) {
                if ($this->input_valid($value))
                    array_push($bulk, $this->extract_values($this->table_insert_fields, $body));
                else
                    throw new Exception(sprintf(ERR_INCOMPLETE_BODY, CAP_INSERT, $this->concat_fields($this->table_insert_fields)));
            }
            $query_result = $connector->insert_bulk($this->web_service->table_name, $this->table_insert_fields, $bulk);
            $this->query_error = $connector->error;
            return $this->get_bulk_response_result($query_result, count($this->web_service->body));
        }
    }
    /**
     * Validates body
     *
     * Check if all values needed to insert are defined on the body.
     * @param stdClass $body The message body
     * @return string TRUE when the body has all the information to insert.
     */
    private function input_valid($body = NULL)
    {
        $inser_fields_count = count($this->table_insert_fields);
        $body_count = 0;
        if (is_null($body))
            $body = $this->web_service->body;
        if (is_null($body))
            throw new Exception(ERR_BODY_IS_NULL);
        foreach ($body as $key => $value)
            if (in_array($key, $this->table_insert_fields))
            $body_count++;
        return $inser_fields_count == $body_count;
    }
    /**
     * Generates a response result from the query result.
     * 
     * @param bool $query_result The query result as a JSON string.
     * @return string The server response
     */
    public function get_response_result($query_result)
    {
        return $this->get_bulk_response_result($query_result, 1);
    }
    /**
     * Generates a response result from the query result.
     * 
     * @param bool $query_result The query result as a JSON string.
     * @param int $limit_total The number of rows to select on the response.
     * @return string The server response
     */
    public function get_bulk_response_result($query_result, $limit_total)
    {
        $json_result = json_decode($query_result);
        if ($json_result->{NODE_QUERY_RESULT}) {
            $query = sprintf("SELECT * FROM `%s` ORDER BY @rowid DESC LIMIT %d", $this->web_service->table_name, $limit_total);
            $result = $this->web_service->connector->select($query, $this->web_service->parser);
            $json_result->{NODE_RESULT} = json_decode($result)->{NODE_RESULT};
            return json_encode($json_result);
        }
        else
            return $query_result;
    }
}
?>