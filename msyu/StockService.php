<?php
    include "../Urabe.php";
    include "../AvalonId.php";
    //Campos de la tabla
    define('TABLE', 'nameless_units');
    define('FIELD_ID', 'id_stock');
    define('FIELD_ID_UNITS', 'id_units');
    define('FIELD_ID_CATEGORY', 'id_category');
    define('FIELD_NAME', 'name');
    define('FIELD_SIZE', 'size');
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
                    '"'.FIELD_ID.'": '.$row["id_stock"].', '.
                    '"'.FIELD_ID_UNITS.'": "'.$row["id_units"].'", '.
                    '"'.FIELD_ID_CATEGORY.'": "'.$row["id_category"].'", '.
                    '"'.FIELD_NAME.'": "'.$row["name"].'", '.
                    '"'.FIELD_SIZE.'": '.$row["size"].
                  ' }';
        };
        if(isset($_GET['sotck_id']))
        {
            $query = "SELECT * FROM ".TABLE." WHERE ".FIELD_ID." = ".$_GET['sotck_id'];
            $result = $conn->Select($query, $parseCategory);
        }
        else    
            $result = $conn->SelectAll(TABLE, $parseCategory);
        break;
        case 'PUT':
            $stock_id = $input->id_stock;
            $fields = array(FIELD_ID_UNITS, FIELD_ID_CATEGORY, FIELD_NAME, FIELD_SIZE);
            $values = array($input->id_stock, $input->id_units, $input->id_category, $input->name, $input->size);
            $result = $conn->UpdateByCondition(TABLE, $fields, $values, FIELD_ID, $stock_id);
            if(!$result)
                $result = 0;    
        break;
        case 'POST':
            $fields = array(FIELD_ID_UNITS, FIELD_ID_CATEGORY, FIELD_NAME, FIELD_SIZE);
            $values = array($input->id_stock, $input->id_units, $input->id_category, $input->name, $input->size);
            $result = $conn->Insert(TABLE, $fields, $values);
            if(!$result)
                $result = 0;
        break;
        case 'DELETE':
          $stock_id = $input->id_stock;
          $result = $conn->DeleteByCondition(TABLE, FIELD_ID, $stock_id);
            if(!$result)
                $result = 0;
        break;
    }
    $conn->Close();
    echo $result;
?>