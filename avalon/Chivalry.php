<?php

/**
 * Defines constants and messages relative to the Avalon API.
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
/****************************************
 **************** Key names *************
 ***************************************/

/****************************************
 **************** Database ***************
 ***************************************/
/**
 * @var string KNIGHT_TABLE
 * The name of the table used to store de user names
 */
const KNIGHT_TABLE = 'knights';
/**
 * @var string KNIGHT_FIELD_ID
 * The name of the table field for the user id
 */
const KNIGHT_FIELD_ID = 'knight_id';
/**
 * @var string KNIGHT_FIELD_NAME
 * The name of the table field for the user name
 */
const KNIGHT_FIELD_NAME = 'user_name';
/**
 * @var string KNIGHT_FIELD_PASS
 * The name of the table field for the user password
 */
const KNIGHT_FIELD_PASS = 'password';
/**
 * @var string KNIGHT_GRP_TABLE
 * The name of the table used to store de knight groups
 */
const KNIGHT_GRP_TABLE = 'knights_groups';
/**
 * @var string KNIGHT_GRP_FIELD_ID
 * The name of the table field for the group id
 */
const KNIGHT_GRP_FIELD_ID = 'group_id';
/**
 * @var string KNIGHT_GRP_FIELD_NAME
 * The name of the table field for the user name
 */
const KNIGHT_GRP_FIELD_NAME = 'group_name';
/**
 * @var string KNIGHT_RANK_TABLE
 * The name of the table used to store de knight ranks
 * (knight-group relation)
 */
const KNIGHT_RANK_TABLE = 'knights_ranking';
/***************************************
 **************** Errors ***************
 ***************************************/
/**
 * @var string ERR_TASK_UNDEFINED
 * The error message sent when the task is missing or not Defined.
 */
const ERR_TASK_UNDEFINED = "The given task was not found on the service";
/**
 * @var string ERR_UNKNOWN_GROUP
 * The error message sent when the group does not exists.
 */
const ERR_UNKNOWN_GROUP = "The group '%s' doesn't exists on the database.";
/**
 * @var string ERR_ACCESS_DENIED
 * The error message sent when access is restricted to the service.
 */
const ERR_ACCESS_DENIED = "Access denied";
/**
 * @var string ERR_ACCESS_DENIED
 * The error message sent when access is restricted to the service.
 */
const ERR_ACCESS_DENIED = "Access denied";
?>