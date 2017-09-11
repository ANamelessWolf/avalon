<?php
    include "../Urabe.php";
    include "../AvalonId.php";
    //Campos de la tabla
    define('TABLE', 'nameless_units');
    define('FIELD_ID', 'id_units');
    define('FIELD_SYMBOL', 'symbol');
    define('FIELD_NAME', 'name');
    define('FIELD_ID_BASE_UNITS', 'id_baseunits');
    define('FIELD_FACTOR', 'factor');
    define('STDIN',fopen("php://stdin","r"));
    //Obtiene la entrada del tipo de web service
    $method = $_SERVER['REQUEST_METHOD'];
    $bodyMethods = array("PUT", "POST", "DELETE");
    //Put, Post y DELETE necesitan body
    if(in_array($method,$bodyMethods))
    {
        $input = file_get_contents('php://input');
        $input = json_decode($input);
    }
    //El administrador de conexión
    $conn = new UrabeMySql(new AvalonId());
    $result = "";
    switch ($method) 
    {
        case 'GET':
        //Realizá el parseo de la información de la clase de categorías.
        $parseCategory = function ($row)
       { 
           return '{ '.
                    '"'.FIELD_ID.'": '.$row["id_units"].', '.
                    '"'.FIELD_SYMBOL.'": "'.$row["symbol"].'", '.
                    '"'.FIELD_NAME.'": "'.$row["name"].'", '.
                    '"'.FIELD_ID_BASE_UNITS.'": '.$row["id_baseunits"].', '.                    
                    '"'.FIELD_FACTOR.'": '.$row["factor"].
                ' }';
        };
        if(isset($_GET['unit_id']))
        {
            $query = "SELECT * FROM ".TABLE." WHERE ".FIELD_ID."= ".$_GET['unit_id'];
            $result = $conn->Select($query, $parseCategory);
        }
        elseif(isset($_GET['from_unit']) && isset($_GET['to_unit']) && isset($_GET['value']))
        {
            $from_unit = $conn->Select("SELECT * FROM ".TABLE." WHERE ".FIELD_ID."= ".$_GET['from_unit'], $parseCategory);
            $from_unit = json_decode($from_unit);
            $from_unit = $from_unit->result[0];
            $to_unit = $conn->Select("SELECT * FROM ".TABLE." WHERE ".FIELD_ID."= ".$_GET['to_unit'], $parseCategory);
            $to_unit = json_decode($to_unit);
            $to_unit = $to_unit->result[0];
            if($from_unit->id_units==$to_unit->id_baseunits)
            {
               $result = $_GET['value'] / $to_unit->factor;
               $result = '{ '.
                            '"result" : '.$result.', '.
                            '"symbol" : "'.$to_unit->symbol.'"'.
                         ' }';
            }
            elseif($from_unit->id_baseunits==$to_unit->id_baseunits)
            {
               $result = ($from_unit->factor * $_GET['value']) / $to_unit->factor;
               $result = '{ '.
                            '"result" : '.$result.', '.
                            '"symbol" : "'.$to_unit->symbol.'"'.
                         ' }';
            }

            else
               $result = 0;
        }
        else    
            $result = $conn->SelectAll(TABLE, $parseCategory);
        break;
        case 'PUT':
            $unit_id = $input->id_units;
            $fields = array(FIELD_SYMBOL, FIELD_NAME, FIELD_ID_BASE_UNITS, FIELD_FACTOR);
            $values = array($input->symbol, $input->name, $input->id_baseunits, $input->factor);
            $result = $conn->UpdateByCondition(TABLE, $fields, $values, FIELD_ID, $unit_id);
            if(!$result)
                $result = 0;
        break;
        case 'POST':
            $fields = array(FIELD_SYMBOL, FIELD_NAME, FIELD_ID_BASE_UNITS, FIELD_FACTOR);
            $values = array($input->symbol, $input->name, $input->id_baseunits, $input->factor);
            $result = $conn->Insert(TABLE, $fields, $values);
            if(!$result)
                $result = 0;
        break;
        case 'DELETE':
          $unit_id = $input->id_units;
          $result = $conn->DeleteByCondition(TABLE, FIELD_ID, $unit_id);
            if(!$result)
                $result = 0;
        break;
    }
    $conn->Close();
    echo $result;
?>