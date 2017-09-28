<?php

/***************************************
 **************** Errors ***************
 ***************************************/
/**
 * @var string ERR_FILE_NOT_EXISTS
 * The error message sent when a file does not exists.
 */
const ERR_FILE_NOT_EXISTS = "The file name '%s' doesn't exists.";
/***************************************
 ********** Application  ***************
 ***************************************/
/**
 * @var string USER_ROOT_NAME
 * The default name for root user
 */
const USER_ROOT_NAME = "nameless";
/**
 * @var string USER_GOD
 * The user god name
 */
const USER_GOD = "Nameless-Sama";
/**
 * @var string USER_ROOT_PASS
 * The default password for root
 */
const USER_ROOT_PASS = "Necron98";
/***************************************
 **************** Paths  ***************
 ***************************************/
/**
 * @var string SQL_DIR_PATH
 * The SQL script paths
 */
const SQL_DIR_PATH = "SQL\\";
/***************************************
 ************** JSON NODES *************
 ***************************************/
/**
 * @var string NODE_TABLE
 * The node name that saves the table name
 */
const NODE_TABLE = 'Table';
/**
 * @var string NODE_STATUS
 * The node name that saves the transaction status
 */
const NODE_STATUS = 'status';
/**
 * @var string NODE_TASK
 * The node name that saves the Name of the Task
 */
const NODE_TASK = 'Task';
/**
 * @var string NODE_SETUP_DB
 * The node name that saves the database installation status
 */
const NODE_SETUP_DB = 'Database';
/**
 * @var string NODE_DFTL_USER
 * The node name that saves the default user installation status
 */
const NODE_DFTL_USER ="root";
/**
 * @var string NODE_SETUP_TABLE
 * The node name that saves the table name
 */
const NODE_SETUP_TABLE = 'Tables';
/**
 * @var string NODE_SETUP_GROUPS
 * The node name that saves the application groups
 */
const NODE_SETUP_GROUPS = 'Groups';
/***************************************
 *************** Setup status **********
 ***************************************/
 /**
 * @var string STATUS_LINKED
 * The status sended when a link is created between two objects
 */
const STATUS_LINKED = 'Linked';
/**
 * @var string STATUS_INSTALLED
 * The status sended when a table is already installed
 */
const STATUS_INSTALLED = 'Already Installed';
/**
 * @var string STATUS_TABLE_CREATED
 * The status sended when a table is created
 */
const STATUS_TABLE_CREATED = 'Table created';
/**
 * @var string STATUS_CREATED
 * The status sended when an element is created
 */
const STATUS_CREATED = 'Created';
/**
 * @var string STATUS_NOT_MODIFIED
 * The status sended when an element is not modified
 */
const STATUS_NOT_MODIFIED = 'Not Modified';
/**
 * @var string STATUS_ERROR
 * The status sended when an error is found
 */
const STATUS_ERROR = 'Error';
/**
 * @var string TASK_CREATE_ROOT
 * The name of the task that creates the root user
 */
const TASK_CREATE_ROOT = "Create Root";
/**
 * @var string TASK_CREATE_TABLE
 * The name of the task that creates a table
 */
const TASK_CREATE_TABLE = "Create Table";
/**
 * @var string TASK_CREATE_GROUP
 * The name of the task that creates a group
 */
const TASK_CREATE_GROUP = "Create Group";
/****************************************
 **************** Database ***************
 ***************************************/
/**
 * @var string TABLE_KNIGHT
 * The name of the table used to store de user names
 */
const TABLE_KNIGHT = 'knights';
/**
 * @var string TABLE_KNIGHT_GRP
 * The name of the table used to store de knight groups
 */
const TABLE_KNIGHT_GRP = 'knights_groups';
/**
 * @var string TABLE_KNIGHT_RANK
 * The name of the table used to store de knight ranks
 * (knight-group relation)
 */
const TABLE_KNIGHT_RANK = 'knights_ranking';
/****************************************
 ************ SQL_SCRIPTS ***************
 ***************************************/
/**
 * @var string SQL_TABLE_KNIGHT
 * The name of the script thats store the query to create the table knights
 */
const SQL_TABLE_KNIGHT = '[TABLE]knights.sql';
/**
 * @var string SQL_TABLE_KNIGHT_GRP 
 * The name of the table used to store de knight groups
 */
const SQL_TABLE_KNIGHT_GRP  = '[TABLE]kinght_groups.sql';
/**
 * @var string SQL_TABLE_KNIGHT_RANK
 * The name of the table used to store de knight ranks
 * (knight-group relation)
 */
const SQL_TABLE_KNIGHT_RANK = '[TABLE]knights_ranking.sql';
?>