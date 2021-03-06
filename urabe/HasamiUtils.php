<?php
include_once "JsonPrettyPrint.php";
include_once "JsonPrettyPrintLight.php";
include_once "GETService.php";
include_once "HasamiRESTfulService.php";
include_once "HasamiURLParameters.php";
include_once "Selection.php";
/**
 * Defines the tools to manage web services and database queries.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */

/**
 * Check wether a JSON result has values
 * @param stdClass $json_result The json result to validate.
 * @return bool True if the JSON result has values.
 */
function has_result($json_result)
{
    return result_is_valid($json_result) && count($json_result->result) > 0;
}
/**
 * Check if a JSON result came from a valid query
 * @param stdClass $json_result The json result to validate.
 * @return bool TRUE if the result is valid
 */
function result_is_valid($json_result)
{
    return !is_null($json_result) && $json_result->query_result;
}
/**
 * Gets the row definition data type enum
 * @param string $data_type The field data type name
 */
function get_row_definition_type($data_type)
{
    $integerTypes = array("tinyint", "smallint", "mediumint", "bigint", "int", "bit");
    $floatTypes = array("decimal", "float", "real", "double", "boolean", "serial");
    $dateTypes = array("date", "datetime", "time");
    if (in_array($data_type, $numberTypes))
        return 1;
    else if (in_array($data_type, $dateTypes))
        return 2;
    else
        return 0;
}
/**
 * Angular date format
 *
 * Formats a date in angular date format.
 * yyyyMMddT00:00:00
 * 
 * @param string date The date to format
 * @return string The date in angular default format
 */
function date_format_angular($date)
{
    $data = date_parse($date);
    $format = "%s%02d%02dT00:00:00";
    return sprintf($format, $data["year"], $data["month"], $data["day"]);
}
/**
 * Label date format
 *
 * Formats a date in label format.
 * DAY MONTH YEAR
 * 
 * @param string date The date to format
 * @return string The date in angular default format
 */
function date_format_label($date)
{
    $data = date_parse($date);
    $months = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $format = "%s %s %s";
    return sprintf($format, $data["day"], $months[$data["month"] - 1], $data["year"]);
}
/**
 * Creates a pretty json print from a JSON object, defining a pretty
 * print format. 
 *
 * @param stdClass $json The JSON object
 * @param JsonPrettyPrint $format The class
 * @param bool $background_dark True if a dark background is applied
 * @return string The json object in the pretty print format.
 */
function pretty_print_format($json, $format = NULL, $background_dark = TRUE)
{
    if (is_null($format) && $background_dark)
        $format = new JsonPrettyPrint();
    else if (is_null($format))
        $format = new JsonPrettyPrintLight();
    if ($background_dark)
        $json_string = '<body bgcolor="#394034">';
    else
        $json_string = "";
    $json_string .= $format->get_format($json);
    if ($background_dark)
        $json_string .= '</body>';
    return $json_string;
}
/**
 * Removes an item from an array
 *
 * Gets an array without a bunch of items.
 * 
 * @param mixed[] $array The source array-
 * @param mixed $items The items to remove
 * @return mixed[] Returns an array without the $items
 */
function remove_from_array($array, $items)
{
    $new_array = array();
    $num_args = func_num_args();
    $ignore_items = array();
    for ($i = 1; $i < $num_args; $i++)
        array_push($ignore_items, func_get_arg($i));
    foreach ($array as &$item) {
        if (!in_array($item, $ignore_items))
            array_push($new_array, $item);
    }
    return $new_array;
}
/**
 * Creates an empty response
 *
 * @return string The server response
 */
function empty_response()
{
    return service_response();
}
/**
 * Prints the server response
 * 
 * @param HasamiWrapper $service The web service
 * @param HasamiURLParameters $url_params The web service url parameters
 * @return string The server response
 */
function print_response($service, $url_params)
{
    if ($url_params->exists(KEY_PRETTY_PRINT)) {
        $style = $url_params->parameters[KEY_PRETTY_PRINT];
        if ($style == PRETTY_PRINT_DARK || $style == PRETTY_PRINT_LIGHT)
            $response = $service->get_response(TRUE, PRETTY_PRINT_DARK == $style);
        else
            $response = $service->get_response();
    }
    else
        $response = $service->get_response();
    return $response;
}
/**
 * Creates an error response
 * @param string $error The error message
 * @return string The server response
 */
function error_response($error)
{
    return service_response(array(), FALSE, $error);
}
/**
 * Creates an error response when the table name does not matchs the web service
 * @param string $table_name The table name
 * @return string The server response
 */
function bad_table_response($table_name)
{
    return error_response();
}
/**
 * Gets a response from the server
 *
 * @param mixed[] $result The data result as an array
 * @param boolean $query_result The transaction query result
 * @param string $error The transaction error
 * @param bool $encode True if the response is sent encoded
 * @return string|mixed[] The server response
 */
function service_response($result = array(), $query_result = FALSE, $error = "", $encode = TRUE)
{
    if ($encode)
        return json_encode(array(NODE_RESULT => $result, NODE_QUERY_RESULT => $query_result, NODE_ERROR => $error));
    else
        return array(NODE_RESULT => $result, NODE_QUERY_RESULT => $query_result, NODE_ERROR => $error);
}

/**
 * Defines a selection view that selects all data on the table
 * @param GETService GET Web service GET
 * @return Selection The selection view
 */
function selection_all($GET)
{
    $query = "SELECT * FROM " . $GET->web_service->table_name;
    return new Selection($GET, $query, NULL);
}
/**
 * Defines a selection view that selects data that matchs a field
 * @param GETService GET Web service GET
 * @return Selection The selection view
 */
function selection_by_field($GET, $field_name)
{
    $query = "SELECT * FROM " . $table_name . " WHERE " . $field_name . " = @" . $field_name;
    return new Selection($GET, $query, $field_name);
}
/**
 * Creates a selection query that select all fields that matchs an equal comparative condition
 * @param string $table_name The table name
 * @param string $field_name The field name
 * @param string $value The value to match
 * @return string The selection query
 */
function query_select_by_field($table_name, $field_name, $value)
{
    $query = "SELECT * FROM " . $table_name . " WHERE " . $field_name . " = " . $value;
    return $query;
}
/**
 * Injects a property into an object when the property does not exist
 * and has a not null property value.
 *
 * @param stdClass $object The object to insert the property
 * @param string $property_name The name of the property
 * @param mixed $property_value The property value
 * @return void
 */
function inject_if_not_in(&$object, $property_name, $property_value)
{
    if (!is_null($object) && !is_null($property_value) && !property_exists($object, $property_name))
        $object->{$property_name} = $property_value;
}
/**
 * Runs a service petition via a specific task
 *
 * @param HasamiWrapper $service The web service
 * @param callback $action The web service action
 * @return string the 
 */
function send_service_petition($service, $action)
{
    try {
        if (is_null($service->url_parameters) || !$service->url_parameters->exists(KEY_TASK)) {
            http_response_code(400);
            throw new Exception(ERR_TASK_UNDEFINED);
        }
        else {

            $task = $service->url_parameters->parameters[KEY_TASK];
            $response = $service->$action($task);
        }
    } catch (Exception $e) {
        $response = error_response($e->getMessage());
    }
    return $response;
}
?>