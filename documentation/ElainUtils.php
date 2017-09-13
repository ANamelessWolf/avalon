<?php

/**
 * A group of tools using for documenting and testing
 * 
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
include_once "/../urabe/HasamiURLParameters.php";
include_once "/../urabe/HasamiWrapper.php";
include_once "/../urabe/HasamiUtils.php";
include_once "/../urabe/Warai.php";
include_once "AppData.php";
/**
 * Opens a file and gets the file content as a string
 * with utf8 file encoding
 *
 * @param string $file_path The file path
 * @return string The file content
 */
function open_file($file_path)
{
    if (file_exists($file_path)) {
        $file_string = file_get_contents($file_path);
        //Removemos comentarios del archivo
        $file_string = preg_replace('!/\*.*?\*/!s', '', $file_string);
        $file_string = preg_replace('/(\/\/).*/', '', $file_string);
        $file_string = preg_replace('/\n\s*\n/', "\n", $file_string);
        //Se códifica en UTF8
        $file_string = utf8_encode($file_string);
        return $file_string;
    }
    else
        throw new Exception(sprintf(ERR_FILE_NOT_EXISTS, $file_path));
}
/**
 * The server response used when a table is created or checked
 *
 * @param string $table_name The table name
 * @param string $status The table status
 * @param boolean $query_result The transaction query result
 * @param string $error The transaction error
 * @return The server response
 */
function response_table_creation($table_name, $status, $query_result = TRUE, $error = "")
{
    return service_response(array(NODE_TABLE => $table_name, NODE_STATUS => $status), $query_result, $error);
}
/**
 * The server response used when a group is created or checked
 *
 * @param string $group_name The group name
 * @param int $group_id The group id
 * @param string $status The task status
 * @param boolean $json_decoded if true the response is obtained decoded as an object
 * @param boolean $query_result The transaction query result
 * @param string $error The transaction error
 * @return string|stdclass The server response
 */
function response_group_creation($group_name, $group_id, $status, $json_decoded = FALSE, $query_result = TRUE, $error = "")
{
    $response = service_response(array(NODE_TABLE => $group_name, NODE_STATUS => $status, NODE_KEY => $group_id), $query_result, $error);
    if ($json_decoded)
        return json_decode($response);
    else
        return $response;
}
?>