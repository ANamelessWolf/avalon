<?php
/****************************************
 ************* Database *****************
 ***************************************/
/**
 * @var string CATEGORY_TABLE
 * The name of the category table
 */
const CATEGORY_TABLE = 'category';
/**
 * @var string CATEGORY_FIELD_ID
 * The name of the category id
 */
const CATEGORY_FIELD_ID = 'cat_id';
/**
 * @var string CATEGORY_FIELD_PARENT_ID
 * The name of the category parent id
 */
const CATEGORY_FIELD_PARENT_ID = 'parent_cat_id';
/**
 * @var string UNIT_TABLE
 * The name of the units table
 */
const UNIT_TABLE = 'units';
/**
 * @var string UNIT_FIELD_ID
 * The name of the units id
 */
const UNIT_FIELD_ID = 'unit_id';
/***************************************
 **************** Errors ***************
 ***************************************/
 /**
 * @var string ERR_CAT_MISSING
 * The error message sent when the category not exists.
 */
const ERR_CAT_MISSING = 'The request category was not found';
?>