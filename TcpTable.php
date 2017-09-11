<?php
    include "Urabe.php";
    define('TABLE', 'TcpTable');
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
    $conn = new UrabeMySql(new DBId());
    $result = "";
    switch ($method) 
    {
        case 'GET':
            $result = $conn->SelectAll(TABLE, function($row)
            { return '{ "message": "'.$row["message"].'" }'; });
        break;
        case 'PUT':
            $result = 0;
        break;
        case 'POST':
            $fields = array("message");
            $values = array($input->message);
            $result = $conn->Insert(TABLE, $fields, $values);
            if(!$result)
                $result = 0;
        break;
        case 'DELETE':
            $result = $conn->RunQuery("DELETE FROM ".TABLE);
            if(!$result)
                $result = 0;
        break;
    }
    $conn->Close();
    echo $result;
?>