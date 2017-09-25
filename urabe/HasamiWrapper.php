<?php
include_once "Urabe.php";
include_once "MysteriousParser.php";
include_once "FieldDefintion.php";
include_once "HasamiURLParameters.php";
include_once "POSTService.php";
include_once "PUTService.php";
include_once "DELETEService.php";

include_once "HasamiUtils.php";
/**
 * A Hasami Wrapper is a web service wrapper Class
 * This class encapsulate and manage webservice tasks like PUT, POST, DELETE and GET
 * @property-read string $table_field_names
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class HasamiWrapper
{
    /**
     * @var string Gets the request HTTP verbs like POST, GET, PUT, PATCH, and DELETE. 
     */
    public $method;
    /**
     * @var stdClass The body message
     */
    public $body;
    /**
     * @var MysteriousParser Manage the data extraction behavior.
     */
    public $parser;
    /**
     * @var string El nombre de la base de datos a la que uno esta conectado
     */
    protected $database;
    /**
     * @var Urabe The MySQL Connector
     */
    public $connector;
    /**
     * @var HasamiURLParameters The parameters defined on the URL path
     */
    public $url_parameters;
    /**
     * @var string The Table name
     */
    public $table_name;
    /**
     * @var FieldDefintion[] The definition of the table fields.
     */
    public $table_fields;
    /**
     * @var string[] The name of the table fields
     */
    public $table_field_names;
    /**
     * @var string Gets or sets the name of the table field that would be use as a primary key
     */
    public $primary_key_name;
    /**
     * @var bool Enables or disables REST Service Get
     */
    public $enable_GET;
    /**
     * @var bool Enables or disables REST Service POST
     */
    public $enable_POST;
    /**
     * @var bool Enables or disables REST Service PUT
     */
    public $enable_PUT;
    /**
     * @var bool Enables or disables REST Service DELETE
     */
    public $enable_DELETE;
    /**
     * @var GETService The webservice GET action
     */
    public $GET;
    /**
     * @var POSTService The webservice POST action
     */
    public $POST;
    /**
     * @var PUTService The webservice PUT action
     */
    public $PUT;
    /**
     * @var DELETEService The webservice DELETE action
     */
    public $DELETE;
    /**
     * __construct
     *
     * Initialize a new instance of a HasamiWrapper Class
     * @param string $table_name The table name.
     * @param string|NULL $primary_key The name of the primary key.
     * @param KanojoX $connection_id The connection id
     */
    public function __construct($table_name, $primary_key, $connection_id)
    {
        $this->table_name = $table_name;
        $this->connector = new Urabe($connection_id);
        $this->table_fields = $this->connector->get_table_definition($this->table_name);
        $this->table_field_names = $this->extract_field_names();
        $this->primary_key_name = $primary_key;
        $this->parser = new MysteriousParser($this->table_fields);
        $this->database = $this->connector->database_name;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->init_body();
        //Por default se permiten todos los servicios
        $this->enable_GET = TRUE;
        $this->enable_PUT = TRUE;
        $this->enable_DELETE = TRUE;
        $this->enable_POST = TRUE;
        //Se inicializan los métodos a los que tiene disponible el servidor.
        $this->POST = new POSTService($this);
        $this->PUT = new PUTService($this);
        $this->DELETE = new DELETEService($this);
        $this->GET = new GETService($this);
    }
    /**
     * Gets the web service response
     * @param bool $pretty_print TRUE if the response is printed with the pretty print format
     * @param bool $dark_theme TRUE if the response is printed with the pretty print dark theme other wise uses the light theme
     * @param bool $security TRUE if the response is excecuted with security. Check current user session
     * @return string The web service response
     */
    public function get_response($pretty_print = FALSE, $dark_theme = TRUE, $security = FALSE)
    {
        $result = "";
        switch ($this->method) {
            case 'GET' :
                $result = $this->get_server_response($this->GET, $this->enable_GET);
                break;
            case 'PUT' :
                $result = $this->get_server_response($this->PUT, $this->enable_PUT);
                break;
            case 'POST' :
                $result = $this->get_server_response($this->POST, $this->enable_POST);
                break;
            case 'DELETE' :
                $result = $this->get_server_response($this->DELETE, $this->enable_DELETE);
                break;
        }
        if ($pretty_print)
            return pretty_print_format(json_decode($result), null, $dark_theme);
        else
            return $result;
    }
    /**
     * Get a server response via a web service query
     *
     * @param HasamiRESTfulService $service The restful service
     * @param bool $is_enabled Check if the service is disabled.
     * @throws Exception An exception is thrown when an error is found on the petition or
     * when the access to the webservice is restricted.
     * @return string The server response
     */
    private function get_server_response($service, $is_enabled)
    {
        try {
            //Web service is disabled
            if (!$is_enabled) {
                http_response_code(403);
                throw new Exception(sprintf(ERR_SERVICE_RESTRICTED, $this->method));
            }
            //Web service work around
            if (is_string($service->service_task))
                $result = $this->{$service->service_task}($this);
            else
                $result = call_user_func_array($service->service_task, array($this));
        } catch (Exception $e) {
            $result = error_response($e->getMessage());
        }
        return $result;
    }

    /**
     * Close the connection to MySQL
     * @return void
     */
    public function close()
    {
        $this->connector->close();
    }
    /**
     * Initialize the body object extracting the data from the file contents 
     * php://input
     * @return void
     */
    public function init_body()
    {
        $body_methods = array("PUT", "POST", "DELETE");
        if (in_array($this->method, $body_methods)) {
            $this->body = file_get_contents('php://input');
            $this->body = json_decode($this->body);
        }
        else
            $this->body = NULL;
    }
    /**
     * Extract the table field names from the $table_fields object definition
     * @return string[] The names of the table fields
     */
    public function extract_field_names()
    {
        $table_field_names = array();
        foreach ($this->table_fields as &$value)
            array_push($table_field_names, $value->field_name);
        return $table_field_names;
    }
    /**
     * Check if the body has one ore more properties defined.
     * 
     * @param string $properties The name or names of the properties to validate.
     * @return string TRUE if all properties are defined on the body.
     */
    public function body_has($properties)
    {
        if (!is_null($this->body)) {
            $num_args = func_num_args();
            $flag = TRUE;
            for ($i = 0; $flag && $i < $num_args; $i++) {
                $property = func_get_arg($i);
                $flag = $flag && property_exists($this->body, $property);
            }
            return $flag;
        }
        else
            return FALSE;
    }
    /**
     * Check if the url parameters has one ore more properties defined.
     * 
     * @param string $properties The name or names of the properties to validate.
     * @return string TRUE if all properties are defined on the url paramaters.
     */
    public function is_in_URL_parameters($properties)
    {
        if (!is_null($this->url_parameters)) {
            $num_args = func_num_args();
            $flag = TRUE;
            for ($i = 0; $flag && $i < $num_args; $i++) {
                $property = func_get_arg($i);
                $flag = $flag && $this->url_parameters->exists($property);
            }
            return $flag;
        }
        else
            return FALSE;
    }
}
?>