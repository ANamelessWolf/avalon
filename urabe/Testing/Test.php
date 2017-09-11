<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    </head>
    <body>
        
    
<?php
include_once ("Urabe.php");
include_once ("KanojoX.php");
include_once ("HasamiUtils.php");
include_once ("HasamiWrapper.php");
include_once ("MysteriousParser.php");
// $conn = new KanojoX();
// $conn->db_name = "cdmx_obras";
 $urabe = new Urabe($conn);
// $tables = $urabe->select_table_names();
// $table_definitions = $urabe->get_table_definition("proyectos");
// $parser = new MysteriousParser($table_definitions);
 $result = $urabe->select("SELECT * FROM proyectos",$parser);
//echo $result;
// $nam = new HasamiWrapper("proyectos","clv_proyecto");
// echo $nam->POST->get_response();
print_array("uno");
print_array("uno", "dos");
print_array("uno", "dos", "tres");
function print_array($properties)
{
    $num_args = func_num_args();
    $flag = TRUE;
    for ($i = 0; $flag && $i < $num_args; $i++) {
        $property = func_get_arg($i);
        echo $property;
    }
    echo "</br>";
}
?>
</body>
</html>