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
include_once "/../avalon/services/KnightServiceRanking.php";
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
     * @var int The id of the administration group
     */
    private $admin_grp_id;
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
    /**
     * Install the aplication 
     *
     * @return The server response
     */
    public function install()
    {
        //1: Crear las tablas
        $table_result = $this->install_tables();
        //2: Se crean los grupos de la aplicación
        $app_group_result = $this->install_groups();
        //3: Se crea el usuario default
        $dftl_user_result = $this->install_root();
        //: Se imprimen los resultados
        $response = array(
            NODE_SETUP_DB => array(
                NODE_SETUP_TABLE => $table_result,
                NODE_SETUP_GROUPS => $app_group_result
            ),
            NODE_DFTL_USER => $dftl_user_result
        );
        return json_encode($response);
    }
    /**
     * Create the application tables
     *
     * @return mixed[] The server responses
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
        }
        return $table_result;
    }
    /**
     * Creates the application groups
     *
     * @return mixed[] The server responses
     */
    public function install_groups()
    {
        $response = array();
        $service = new KnightGroupService(NULL);
        //1: Se realizá la selección de los grupos instalados
        $installed_groups = $service->get_response();
        $installed_groups = get_groups_by_service($installed_groups);
        //2: Se realizá la selección de los grupos a instalar
        $app_groups = get_group_names();
        //3: Se insertan los grupos que no esten instalados
        $service->method = "POST";
        foreach ($app_groups as $grp_name => $value) {
            if (array_key_exists($grp_name, $installed_groups)) {
                array_push($response, response_group_creation($grp_name, $installed_groups[$grp_name], STATUS_INSTALLED, TRUE));
                if ($grp_name == GROUP_NAMELESS)
                    $this->admin_grp_id = $installed_groups[$grp_name];
            }
            else {
                $service->body = json_decode("{}");
                $service->body->{KNIGHT_GRP_FIELD_NAME} = $grp_name;
                $r = $service->get_response();
                $r = json_decode($r);
                if (has_result($r)) {
                    array_push($response, response_group_creation($grp_name, $r->result[0]->{KNIGHT_GRP_FIELD_ID}, STATUS_CREATED, TRUE));
                    if ($grp_name == GROUP_NAMELESS)
                        $this->admin_grp_id = $r->result[0]->{KNIGHT_GRP_FIELD_ID};
                }
                else
                    array_push($response, response_group_creation($grp_name, NAN, STATUS_ERROR, TRUE, $r->{NODE_QUERY_RESULT}, $r->{NODE_ERROR}));
            }

        }
        return $response;
    }
    /**
     * Installs the root user
     *
     * @return string The server response
     */
    public function install_root()
    {
        $result = (object)array(KNIGHT_FIELD_NAME => USER_ROOT_NAME, NODE_STATUS => "");
        $k_service = new KnightService();
        $g_service = new KnightGroupService();
        $r_service = new KnightServiceRanking();
        //1: Checamos si existe root en la tabla
        $k_service->method = "POST";
        $k_service->url_parameters = new HasamiURLParameters(array(KEY_TASK => TASK_SELECT));
        $k_service->body = (object)array(KNIGHT_FIELD_NAME => USER_ROOT_NAME);
        $response = json_decode($k_service->get_response());
        //2: Se valída el vinculo al grupo de administrador
        if (has_result($response)) { //Existe el usuario
            $u_id = $response->{NODE_RESULT}[0]->{KNIGHT_FIELD_ID};
            //Revisar que exista en el grupo de administrador
            if ($g_service->belongs_to_group($u_id, $this->admin_grp_id))
                $result->{NODE_STATUS} = STATUS_INSTALLED;
            else {
                //Si no existe se crea el link
                $response = $r_service->create_link($u_id, $this->admin_grp_id);
                $response = json_decode($response);
                if ($response->{NODE_QUERY_RESULT})
                    $result->{NODE_STATUS} = STATUS_LINKED;
                else
                    $result->{NODE_ERROR} = STATUS_ERROR;
            }
            $response->{NODE_RESULT} = $result;
        }
        else {
            //Se crea el usuario root y el link al grupo
            $response = $k_service->join_the_realm(USER_ROOT_NAME, USER_ROOT_PASS);
            $response = json_decode($response);
            if (has_result($response)) {
                $u_id = $response->{NODE_RESULT}[0]->{KNIGHT_FIELD_ID};
                $response = $r_service->create_link($u_id, $this->admin_grp_id);
                $response = json_decode($response);
                if ($response->{NODE_QUERY_RESULT})
                    $result->{NODE_STATUS} = STATUS_CREATED;
                else
                    $result->{NODE_ERROR} = STATUS_ERROR;
            }
            else
                $result->{NODE_ERROR} = STATUS_ERROR;
            $response->{NODE_RESULT} = $result;
        }
        $r_service->close();
        $g_service->close();
        $k_service->close();
        return $response;
    }


    /**
     * Creates a table from a script file
     *
     * @param string $sql_file The sql file path
     * @return string The server response
     */
    private function create_table($sql_file, $table_name)
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
            KEY_TASK => TASK_SELECT
        ));
        $body = array(
            KNIGHT_FIELD_ID => -1,
            KNIGHT_FIELD_NAME => USER_ROOT_NAME,
            KNIGHT_GRP_FIELD_NAME => GROUP_NAMELESS
        );
        //Seleccionamos el id del usuario
        $k_service = new KnightService($url_params);
        $k_service->body = (object)$body;
        $k_service->method = 'POST';
        $user_response = json_decode($k_service->get_response());
        if (has_result($user_response)) {
            $body->{KNIGHT_FIELD_ID} = intval($user_response->{NODE_RESULT}[0]->{KNIGHT_FIELD_ID});
            $response = $k_service->join_group();
            return json_decode($response);
        }
        else
            return service_response(array(KNIGHT_FIELD_NAME => USER_ROOT_NAME, KNIGHT_GRP_FIELD_NAME => GROUP_NAMELESS, NODE_STATUS => STATUS_ERROR), $user_response->{NODE_QUERY_RESULT}, $user_response->{NODE_ERROR}, FALSE);
    }
}
?>