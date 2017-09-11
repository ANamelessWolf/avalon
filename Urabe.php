<?php
include "DbId.php";
    class UrabeMySql
    {
        //La información de la conexión
        var $connection_id;
        //El objeto de conexión a MySql
        var $connection;
        //El mensaje de error
        var $error;
        //Una bandera que indica si se establecio la conexión a la BD
        var $is_connected;
        //Crea una nueva clase para realizar una transacción
        //a la base de datos
        function UrabeMySql($conn_id)
        {
            $this->connection_id = $conn_id;
            //Se realiza la conexión a la base de datos
            $this->connection = new mysqli(
                $this->connection_id->server, 
                $this->connection_id->username, 
                $this->connection_id->password, 
                $this->connection_id->db);
            //Válida la conexión en caso de existir un error se guarda
            //en la variable de resultado
            if ($this->connection->connect_error)
            {
                $this->error = "Connection failed: ".$this->connection->connect_error;
                $this->is_connected = false;
            }
            else
                $this->is_connected = true;
        }
        //Realiza una prueba de conexión
        function TestConnection()
        {
            if($this->is_connected)
                return "Connected";
            else
                return "Not Connected";
        }
        //Realizá un query de selección
        function Select($query, $selectedFunc)
        {
            $qResult = $this->connection->query($query);
            if(gettype($qResult)=="object")
            {
                if ($qResult->num_rows > 0)
                {
                    $result = '{"result" : [';
                    // Se obtiene el resultado de la selección
                    while($row = $qResult->fetch_assoc())
                      $result .= $selectedFunc($row).", ";
                    $result = substr($result, 0, strlen($result) - 2). ' ], "query_result": "TRUE"}';
                }
                else
                    $result = '{"result" : [ ], "query_result": "TRUE"}';
            }
            else
                $result = '{"query_result": "FALSE"}';
            return $result;
        }
        //Realizá un query de selección
        function SelectAll($table, $selectedFunc)
        {
            return $this->Select("SELECT * FROM ".$table, $selectedFunc);
        }
        //Realizá un query de inserción
        function Insert($table, $fields, $values)
        {
            $f = '';
            $v = '';
            $max = count($fields);
            if($this->is_connected)
            {            
                $query = 'INSERT INTO '.$table.' (';
                for($i=0;$i<$max;$i++)
                {
                    $f.= '`'.$fields[$i].'`, ';
                    if(gettype($values[$i])=='integer' || gettype($values[$i])=='double')
                        $v.=$values[$i].", ";
                    else
                        $v.="'".$values[$i]."', ";
                }
                $f=substr($f,0,strlen($f)-2).') ';
                $v="VALUES (".substr($v,0,strlen($v)-2).")";
                $query=$query.$f.$v;
                $result = $this->connection->query($query);
                return $result;
            }
            else
                return FALSE;
        }        
        //Realizá un query de inserción
        function RunQuery($query)
        {
            if($this->is_connected)
                return $this->connection->query($query);
            else
                return FALSE;
        }
        //Realiza un query de actualización
        function Update($table, $fields, $values, $condition)
        {
            $max = count($fields);
            if($this->is_connected)
            {
                $query='UPDATE '.$table.' SET ';
                for($i=0;$i<$max;$i++)
                {
                    $query.='`'.$fields[$i].'`= ';
                    if(gettype($values[$i])=='integer' || gettype($values[$i])=='double')
                        $query.=$values[$i].", ";
                    else
                        $query.="'".$values[$i]."', ";
                }
                $query=substr($query,0,strlen($query)-2);
                $query.=' WHERE '.$condition;
                $result = $this->connection->query($query);
                return $result;
            }
            else
                return FALSE;
        }
        //Realiza un query de actualización
        function UpdateByCondition($table, $fields, $values, $field, $value)
        {
            $max = count($fields);
            if($this->is_connected)
            {
                $query='UPDATE '.$table.' SET ';
                for($i=0;$i<$max;$i++)
                {
                    $query.='`'.$fields[$i].'`= ';
                    if(gettype($values[$i])=='integer' || gettype($values[$i])=='double')
                        $query.=$values[$i].", ";
                    else
                        $query.="'".$values[$i]."', ";
                }
                $query=substr($query,0,strlen($query)-2);
                $query.=' WHERE '.$field.' = ';
                if(gettype($value)=='integer' || gettype($value)=='double')
                    $query.=$value;
                else
                    $query.="'".$value;
                $result = $this->connection->query($query);
                return $result;
            }
            else
                return FALSE;
        }
        //Realiza un query de actualización
        function Delete($table, $condition)
        {
            if($this->is_connected)
            {
                $query='DELETE FROM '.$table.' WHERE '.$condition;
                $result = $this->connection->query($query);
                return $result;
            }
            else
                return FALSE;
        }
        //Realiza un query de actualización
        function DeleteByCondition($table, $field, $value)
        {
            if($this->is_connected)
            {
                $query='DELETE FROM '.$table.' WHERE `'.$field.'` = '.$value;
                $result = $this->connection->query($query);
                if(gettype($value)=='integer' || gettype($value)=='double')
                    $query.=$value;
                else
                    $query.="'".$value;
                return $result;
            }
            else
                return FALSE;
        }
        //Cierra la conexión activa
        function Close()
        {
            if($this->is_connected)
            {
                $this->connection->close();
                $this->is_connected=false;
            }
        }        
    }
?>