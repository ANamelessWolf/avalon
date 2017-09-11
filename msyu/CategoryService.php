<?php
    include "../Urabe.php";
    include "../AvalonId.php";
    //Campos de la tabla
    define('TABLE', 'nameless_category');
    define('FIELD_ID', 'id_category');
    define('FIELD_SUB_CAT_ID', 'id_subcategory');
    define('FIELD_NAME', 'name');
    define('FIELD_DESC', 'description');
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
                    '"'.FIELD_ID.'": '.$row["id_category"].', '.
                    '"'.FIELD_SUB_CAT_ID.'": '.$row["id_subcategory"].', '.
                    '"'.FIELD_NAME.'": "'.$row["name"].'", '.
                    '"'.FIELD_DESC.'": "'.$row["description"].'" '.
                ' }';
        };
        if(isset($_GET['cat_id']))
        {
            $query = "SELECT * FROM ".TABLE." WHERE ".FIELD_ID."= ".$_GET['cat_id'];
            $result = $conn->Select($query, $parseCategory);
        }
        else
            $result = $conn->SelectAll(TABLE, $parseCategory);
        break;
        case 'PUT':
            $cat_id = $input->id_category;
            $fields = array(FIELD_NAME, FIELD_DESC, FIELD_SUB_CAT_ID);
            $values = array($input->name, $input->description, $input->id_subcategory);
            $result = $conn->UpdateByCondition(TABLE, $fields, $values, FIELD_ID, $cat_id);
            if(!$result)
                $result = 0;
        break;
        case 'POST':
            $fields = array(FIELD_NAME, FIELD_DESC, FIELD_SUB_CAT_ID);
            $values = array($input->name, $input->description, $input->id_subcategory);
            $result = $conn->Insert(TABLE, $fields, $values);
            if(!$result)
                $result = 0;
        break;
        case 'DELETE':
          $cat_id = $input->id_category;
          $result = $conn->DeleteByCondition(TABLE, FIELD_ID, $cat_id);
            if(!$result)
                $result = 0;
        break;
    }
    $conn->Close();
    echo $result;
?>