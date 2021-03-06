<?php

/**
 * Defines constants and messages relative to the Urabe API.
 * @version 1.0.0
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
/***************************************
 **************** Errors ***************
 ***************************************/
/**
 * @var string ERR_BAD_INDEX
 * The error message sent when the index is out of bounds.
 */
const ERR_BAD_INDEX = 'Bad index, no such index %s in array.';
/**
 * @var string ERR_BAD_TABLE
 * The error message sent when the given table does not matchs its service.
 */
const ERR_BAD_TABLE = 'The table %s does not match the selected service.';
/**
 * @var string ERR_BAD_URL
 * The error message sent when the url parameters are not sent in pairs
 */
const ERR_URL_PARAM_FORMAT = 'The url has an invalid format, URL parameters must be sent in pairs.';
/**
 * @var string ERR_BODY_IS_NULL
 * The error message sent when the the body is null.
 */
const ERR_BODY_IS_NULL = 'An error ocurred parsing the message body. The body message is null or invalid';
/**
 * @var string ERR_BODY_NOT_ARRAY
 * The error message sent when the the body is needed to be an array.
 */
const ERR_BODY_NOT_ARRAY = 'The body must be an array.';
/**
 * @var string ERR_CONNECTION
 * The error message sent when the connection to the database is closed.
 */
const ERR_CONNECTION_CLOSED = 'The connection to the database is closed.';
/**
 * @var string ERR_INCOMPLETE_BODY
 * The error message sent when the the body is missing data.
 */
const ERR_INCOMPLETE_BODY = 'The body does not contain enough data to %s. Needed fields [%s].';
/**
 * @var string ERR_INCOMPLETE_BODY_CONDITION
 * The error message sent when the the condition fields are missing on the body.
 */
const ERR_INCOMPLETE_BODY_CONDITION = 'The body does not contain enough data to fit the condition. Needed fields [%s].';
/**
 * @var string ERR_MISS_INSERT_FIELDS
 * The error message sent when there are no insert fields.
 */
const ERR_MISS_INSERT_FIELDS = 'No insertion fields have been defined, "table_insert_fields" is NULL.';
/**
 * @var string ERR_MISS_UPDATE_FIELDS
 * The error message sent when there are no update fields.
 */
const ERR_MISS_UPDATE_FIELDS = 'No update fields have been defined, "table_update_fields" is NULL.';
/**
 * @var string ERR_MISS_PARAM
 * The error message sent when a parameter is missing.
 */
const ERR_MISS_PARAM = "The parameter '%s' was not found on the response.";
/**
 * @var string ERR_MISS_TABLE
 * The error message sent when the table does not exists.
 */
const ERR_MISS_TABLE = "The table '%s' doesn't exists on the database.";
/**
 * @var string ERR_METHOD_NOT_DEFINED
 * The error message sent when a callback is used with no definition.
 */
const ERR_METHOD_NOT_DEFINED = 'An error occurred trying to call %s.';
/**
 * @var string ERR_NULL_BODY
 * The error message sent when the body is null.
 */
const ERR_NULL_BODY = 'An error occurred reading the message body, the body does not contain a valid JSON format.';
/**
 * @var string ERR_INVALID_SERVICE
 * The error message sent when no service name is specified.
 */
const ERR_INVALID_SERVICE = 'No service especified';
/**
 * @var string ERR_INVALID_SERVICE
 * The error message sent when no service name is specified.
 */
const ERR_SERVICE_RESTRICTED = 'This service can not be access via the verbose %s';
/**
 * @var string ERR_TASK_UNDEFINED
 * The error message sent when the task is missing or not Defined.
 */
const ERR_TASK_UNDEFINED = "The given task was not found on the service";
/***************************************
 ************** CAPTIONS ***************
 ***************************************/
/**
 * @var string CAP_DELETE
 * String caption for Delete.
 */
const CAP_DELETE = 'Delete';
/**
 * @var string CAP_UPDATE
 * String caption for Update.
 */
const CAP_UPDATE = 'Update';
/**
 * @var string CAP_INSERT
 * String caption for Insert.
 */
const CAP_INSERT = 'Insert';
/***************************************
 ************** JSON NODES *************
 ***************************************/
/**
 * @var string NODE_RESULT
 * The node name that saves the transaction result
 */
const NODE_RESULT = 'result';
/**
 * @var string NODE_QUERY
 * The node name that saves the transaction query
 */
const NODE_QUERY = 'query';
/**
 * @var string NODE_QUERY_RESULT
 * The node name that saves the transaction query result
 */
const NODE_QUERY_RESULT = 'query_result';
/**
 * @var string NODE_ERROR
 * The node name that saves the transaction error
 */
const NODE_ERROR = 'error';
/**
 * @var string NODE_KEY
 * The node name that saves an element key
 */
const NODE_KEY = 'key';
/****************************************
 ************ URL PARAMS KEYS ************
 *****************************************/
/**
 * @var string KEY_SERVICE
 * The parameter key that defines a service name
 */
const KEY_SERVICE = 'service';
/**
 * @var string KEY_TASK
 * The parameter key that defines a service task
 */
const KEY_TASK = 'task';
/**
 * @var string KEY_PRETTY_PRINT
 * The parameter key that defines a service task
 */
const KEY_PRETTY_PRINT = 'PP';
/**
 * @var string PRETTY_PRINT_DARK
 * The parameter key that specifies a dark theme with pretty print
 */
const PRETTY_PRINT_DARK = 'Dark';
/**
 * @var string PRETTY_PRINT_LIGHT
 * The parameter key that specifies a light theme with pretty print
 */
const PRETTY_PRINT_LIGHT = 'Light';
/****************************************
 ************ Servicios Disponibles ******
 *****************************************/
/**
 * @var string SERVICE_KNIGHT
 * The service name to administrate application users
 */
const SERVICE_KNIGHT = 'Knight';
/**
 * @var string SERVICE_KNIGHT_GROUP
 * The service name to administrate application groups
 */
const SERVICE_KNIGHT_GROUP = 'Groups';
/**
 * @var string SERVICE_MSYU_UNIT
 * The service name to administrate application groups
 */
const SERVICE_MSYU_UNIT = 'Units';
/****************************************
 ************ Available Tasks*************
 *****************************************/
/**
 * @var string TASK_ADD
 * The task name to add an element to the table
 */
const TASK_ADD = 'Add';
/**
 * @var string TASK_CREATE
 * The task name used to create a category
 */
const TASK_CREATE = 'Create';
/**
 * @var string TASK_LOGIN
 * The task name that creates a login on the databse
 */
const TASK_LOGIN = 'Login';
/**
 * @var string TASK_LOGOUT
 * The task name that logouts from the database
 */
const TASK_LOGOUT = 'Logout';
/**
 * @var string TASK_GET
 * The task name that gets data from the database
 */
const TASK_GET = 'Get';
/**
 * @var string TASK_LINK
 * The task name to link an element to another
 */
const TASK_LINK = 'Link';
/**
 * @var string TASK_SELECT
 * The task name to select an element to the table
 */
const TASK_SELECT = 'Select';
/*****************************************
 ************ FUNCTION NAMES **************
 *****************************************/
/**
 * @var string F_POST
 * The name of the POST action function
 */
const F_POST = 'POST_action';
/**
 * @var string F_GET
 * The name of the GET action function
 */
const F_GET = 'GET_action';
?>