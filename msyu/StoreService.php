<?php
    include "../Urabe.php";
    include "../AvalonId.php";
    //Campos de la tabla
    define('TABLE', 'nameless_category');
    define('FIELD_ID', 'id_store');
    define('FIELD_NAME', 'name');
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
        $parseMethod = function ($row)
       { 
           return '{ '.
                    '"'.FIELD_ID.'": '.$row["id_store"].', '.
                    '"'.FIELD_NAME.'": "'.$row["name"].'"'.
                ' }';
        };
        if(isset($_GET['store_id']))
        {
            $query = "SELECT * FROM ".TABLE." WHERE ".FIELD_ID."= ".$_GET['store_id'];
            $result = $conn->Select($query, $parseMethod);
        }
        else
            $result = $conn->SelectAll(TABLE, $parseMethod);
        break;
        case 'PUT':
            $store_id = $input->id_store;
            $fields = array(FIELD_NAME);
            $values = array($input->name);
            $result = $conn->UpdateByCondition(TABLE, $fields, $values, FIELD_ID, $store_id;
            if(!$result)
                $result = 0;
        break;
        case 'POST':
            $fields = array(FIELD_NAME);
            $values = array($input->name);
            $result = $conn->Insert(TABLE, $fields, $values);
            if(!$result)
                $result = 0;
        break;
        case 'DELETE':
          $store_id = $input->id_store;
          $result = $conn->DeleteByCondition(TABLE, FIELD_ID, $store_id);
            if(!$result)
                $result = 0;
        break;
    }
    $conn->Close();
    echo $result;
?>