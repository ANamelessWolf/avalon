<?php
//Urabe 
include_once "/../urabe/HasamiURLParameters.php";
include_once "/../urabe/HasamiWrapper.php";
include_once "/../urabe/HasamiUtils.php";
include_once "/../urabe/Warai.php";
include_once "/../avalon/Excalibur.php";
include_once "/../avalon/Morgana.php";
include_once "/../avalon/services/KnightService.php";
include_once "/../avalon/services/KnightGroupService.php";
include_once "ElainUtils.php";
include_once "AppData.php";


//Inicia Sesión con credenciales falsas
$m = new MorganaSession(USER_GOD, array(GROUP_NAMELESS));
$m->login();
//Inicia el proceso de instalación
$e = new Elaine(new Excalibur());
$result = $e->install();
//Se cierra la falsa sesión
$m->logout();
echo $result;
/**
 * Installs the avalon aplication database using its ruler the lady of the lake Elain
 * 
 * @version 1.0.0
 * @api Avalon
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 Nameless Studios
 */
class Elaine
{
    /**
     * @var Urabe The MySQL Connector
     */
    public $connector;
    /**
     * __construct
     *
     * Initialize a new instance of Avalon instaler
     * @param KanojoX $connection_id The connection id
     */
    public function __construct($connection_id)
    {
        $this->connector = new Urabe($connection_id);
    }
    public function install()
    {
        //1: Crear las tablas
        $table_result = $this->install_tables();
        //2: Se imprimen los resultados
        $response = array(
            NODE_SETUP_TABLE => $table_result
        );
        return json_encode($response);
    }
    /**
     * Create the application tables
     *
     * @return string The server response
     */
    public function install_tables()
    {
        $table_result = array();
        //Las tablas a instalar
        $tables = array(
            TABLE_KNIGHT => SQL_TABLE_KNIGHT,
            TABLE_KNIGHT_GRP => SQL_TABLE_KNIGHT_GRP,
            TABLE_KNIGHT_RANK => SQL_TABLE_KNIGHT_RANK
        );
        foreach ($tables as $table => $sql_file) {
            $response = $this->create_table($sql_file, $table);
            $result = json_decode($response);
            $result->{NODE_TASK} = TASK_CREATE_TABLE;
            $status = $result->{NODE_RESULT}->{NODE_STATUS};
            array_push($table_result, $result);
            //Se añade el usuario root si la tabla se acaba de crear
            if ($table == TABLE_KNIGHT && $status == STATUS_TABLE_CREATED)
                array_push($table_result, $this->create_root());
            else if ($table == TABLE_KNIGHT_GRP && $status == STATUS_TABLE_CREATED)
                array_push($table_result, $this->create_groups());
            else if ($table == TABLE_KNIGHT_GRP && $status == STATUS_INSTALLED)
                array_push($table_result, $this->update_groups());
            else if ($table == TABLE_KNIGHT_RANK && $status == STATUS_TABLE_CREATED)
                array_push($table_result, $this->register_root());
        }
        return $table_result;
    }
    /**
     * Creates a table from a script file
     *
     * @param string $sql_file The sql file path
     * @return string The server response
     */
    public function create_table($sql_file, $table_name)
    {
        try {
            if ($this->connector->table_exists($table_name))
                $response = response_table_creation($table_name, STATUS_INSTALLED, TRUE, $this->connector->error);
            else {
                $query = open_file(SQL_DIR_PATH . $sql_file);
                $result = $this->connector->query($query);
                $result = json_decode($result);
                if ($result->{NODE_QUERY_RESULT})
                    $response = response_table_creation($table_name, STATUS_TABLE_CREATED, TRUE, "");
                else
                    $response = response_table_creation($table_name, STATUS_ERROR, FALSE, $result->{NODE_ERROR});
            }
        } catch (Exception $e) {
            $response = response_table_creation($table_name, STATUS_ERROR, FALSE, $e->getMessage());
        }
        return $response;
    }
    /**
     * Creates the default roor user on the database
     *
     * @return stdClass The service result response as an JSON object
     */
    public function create_root()
    {
        $service = new KnightService();
        $service->body = json_decode("{}");
        $service_result = $service->join_the_realm(USER_ROOT_NAME, USER_ROOT_PASS);
        $service_result = json_decode($service_result);
        $service_result->{NODE_TASK} = TASK_CREATE_ROOT;
        $service->close();
        return $service_result;
    }
    /**
     * Inserts the list of groups defined on the application
     *
     * @param mixed[] $grp_names The collection of groups to create
     * @param KnightGroupService $active_service The web service
     * @return mixed[] The server response as an array
     */
    public function create_groups($grp_names = NULL, $active_service = NULL)
    {
        $response = array();
        if (is_null($grp_names))
            $grps = get_group_names();
        else
            $grps = $grp_names;
        if (is_null($active_service))
            $service = new KnightGroupService(NULL);
        else
            $service = $active_service;
        $service->method = "POST";
        foreach ($grps as $grp_name => $value) {
            $service->body = json_decode("{}");
            $service->body->{KNIGHT_GRP_FIELD_NAME} = $grp_name;
            $service_result = $service->get_response();
            $service_result = json_decode($service_result);
            $service_result->{NODE_TASK} = TASK_CREATE_GROUP;
            array_push($response, $service_result);
        }
        $service->close();
        return $response;
    }
    /**
     * Agregá los grupos que sean nuevos en la aplicación y no esten definidos en la base de datos
     *
     * @return mixed[] The server response as an array
     */
    public function update_groups()
    {
        $response = array();
        $service = new KnightGroupService(NULL);
        $service->method = "GET";
        $groups = json_decode($service->get_response());
        $grps = get_group_names();
        $grp_names = array();
        if (has_result($groups))
            $grps_db = array();
        //Se extraen los grupos que existen en la base
        foreach ($groups->{NODE_RESULT} as &$grp)
            array_push($grps_db, $grp->{KNIGHT_GRP_FIELD_NAME});
        //Se seleccionan los grupos que esten definidos en la aplicación
        //y no esten definidos en la base
        foreach ($grps as $key => $value) {
            if (array_key_exists($key, $grps_db))
                array_push($response, service_response(array(KNIGHT_GRP_FIELD_NAME => $key, NODE_STATUS => STATUS_INSTALLED), TRUE, "", FALSE));
            else
                $grp_names[$key] = $value;
        }
        //Los que falten se vuelven a crear
        $response_created = create_groups($grp_names, $service);
        foreach ($response_created as $r)
            array_push($response, $r);
        return $response;
    }
    /**
     * Register root in the nameless group
     *
     * @return stdClass The service result response as an JSON object
     */
    public function register_root()
    {
        $url_params = new HasamiURLParameters(array(
            KEY_SERVICE => SERVICE_KNIGHT,
            KEY_TASK => TASK_SELECT,
            KNIGHT_FIELD_NAME => USER_ROOT_NAME,
            KNIGHT_GRP_FIELD_NAME => GROUP_NAMELESS
        ));
        //Seleccionamos el id del usuario
        $k_service = new KnightService($url_params);
        $k_service->method = 'POST';
        $user_response = $k_service->get_response();
        //
    }
}
?>