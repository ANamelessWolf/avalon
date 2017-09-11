<?php
include_once "Warai.php";
/**
 * Row Definition Class
 * 
 * This class saves the table fields definitions. Each table field is asociated with a table field name and table data type.
 * This class treats the database fields types in three types; strings dates and numbers.
 * @api Makoto Urabe
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class FieldDefintion
{
    /**
     * @var string STRING_FORMAT
     * The format that saves the JSON data with a string format.
     */
    const STRING_FORMAT = '"%s" : "%s"';
    /**
     * @var string INTEGER_FORMAT
     * The format that saves the JSON data with a number format.
     */
    const INTEGER_FORMAT = '"%s" : %s';
    /**
     * @var string STRING_FORMAT
     * The format that saves the JSON data with a date format.
     */
    const DATE_FORMAT = '"%s" : "%s", "%s_db" : "%s"';
    /**
     * @var string The field name
     */
    public $field_name;
    /**
     * @var string The field data type
     */
    public $data_type;
    /**
     *
     * Initialize a new instance of a FieldDefintion class
     *
     * @param string $field The field name
     * @param string $data_type The field data type
     */
    public function __construct($field, $data_type)
    {
        $this->field_name = $field;
        $this->data_type = $data_type;
    }
    /**
     * Gets the value from a string in the row definition data type
     *
     * @param string $value The selected value as string
     * @return mixed The value as the same type of the table definition.
     */
    public function GetValue($value)
    {
        $integer_types = array("tinyint", "smallint", "mediumint", "bigint", "serial", "int", "bit");
        $float_types = array("decimal", "float", "real", "double", "numeric");
        if (in_array($this->data_type, $integer_types))
            return is_null($value) ? 0 : intval($value);
        else if (in_array($this->data_type, $float_types))
            return is_null($value) ? 0.0 : floatval($value);
        else if ($this->data_type == "boolean")
            return is_null($value) ? FALSE : boolval($value);
        else
            return is_null($value) ? "" : $value;
    }
    /**
     * Verify if the current data type is a date type
     * @param string $data_type The field data type
     */
    function is_date()
    {
        $date_types = array("date", "datetime", "time");
        return in_array($this->data_type, $date_types);
    }
}
?>