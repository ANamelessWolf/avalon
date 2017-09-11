<?php
include_once "HasamiUtils.php";
include_once "Warai.php";
/**
 * This class obtains the web service options from the URL path, each parameter is associated with a key defined in the constructor. 
 * The parameters on the URL must be in order.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class HasamiURLParameters
{
    /**
     * @var mixed[] The URL parameters 
     */
    public $parameters;
    /**
     * __construct
     *
     * Initialize a new instance of the Hasami URL parameters.
     */
    function __construct($input_params = NULL)
    {
        if (is_null($input_params)) {
            $this->parameters = array();
            if (isset($_SERVER['PATH_INFO']))
                $request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
            else
                $request = array();
            $values_count = count($request);
        //Los parámetros deben ir en parejas
            if ( ($values_count % 2) == 0) {

                for ($i = 1; $i < $values_count; $i += 2)
                    if ($i < $values_count)
                    $this->parameters[$request[$i - 1]] = $request[$i];
            }
            else
                throw new Exception(ERR_URL_PARAM_FORMAT, 1);
        }
        else
            $this->parameters = $input_params;
    }
    /**
     * Excecutes an url parameter task
     *
     * @param HasamiWrapper $service The web service
     * @param string $table The table name
     * @param callback $task The service task using the url parameters
     * @param mixed $args The field names used on the task
     * @return string The server response
     */
    function run_tasks($service, $table, $task, $args)
    {
        if ($table == $this->parameters[KEY_SERVICE]) {
            //Extraemos los campos a utilizar en el servicio
            $input = array();
            $num_args = func_num_args();
            for ($i = 3; $i < $num_args; $i++) {
                $property = func_get_arg($i);
                if ($this->exists($property))
                    $fields[$property] = $this->parameters[$property];
                else
                    return error_response(ERR_URL_PARAM_MISSING);
            }
            return $task($service, $input);
        }
        else
            return bad_table_response($this->url_parameters[KEY_SERVICE]);
    }
    /**
     * Check if an URL parameter key exists on the URL parameters
     *
     * @param string $parameter_key
     * @return True if the parameter exists on the paramater key
     */
    public function exists($parameter_key)
    {
        return array_key_exists($parameter_key, $this->parameters) && !is_null($this->parameters[$parameter_key]);
    }
    /**
     * Check if an URL parameter key exists and is equals to a given value
     *
     * @param string $parameter_key The parameter key to validate
     * @param mixed $parameter_value The parameter value to validate
     * @return True if the parameter exists and its equals to a given value
     */
    public function equals_to($parameter_key, $parameter_value)
    {
        return exists($parameter_key) && $this->parameters[$parameter_key] == $parameter_value;
    }
}
?>