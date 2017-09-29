<?php 
//Urabe 
include_once "/../../../../urabe/HasamiURLParameters.php";
include_once "/../../../../urabe/HasamiWrapper.php";
include_once "/../../../../urabe/HasamiUtils.php";
include_once "/../../../../urabe/Warai.php";
//Avalon
include_once "/../../../../avalon/MorganaUtils.php";
include_once "/../../../../avalon/Chivalry.php";
//CDMX
include_once "/../AppConst.php";
include_once "/../AppAccess.php";
include_once "/../AppSession.php";
include_once "/../CDMXId.php";
include_once "ProgramService.php";
include_once "LocationService.php";
/**
 * Project Service Class
 * Esta clase se encarga de las peticiones que manejan la información de proyectos
 * @version 1.0.0
 * @api CDMX Seguimiento de obras
 * @author A nameless wolf <anamelessdeath@gmail.com>
 * @copyright 2015-2020 CADLabs
 */
class ProjectService extends HasamiWrapper
{
    /**
     * @var ProgramService Administra la conexión de acceso de programas.
     */
    public $p_service;
    /**
     * @var LocationService Administra la conexión de acceso de ubicaciones.
     */
    public $l_service;
    /**
     * __construct
     *
     * Inicializa una nueva instancia del servicio administración de proyectos
     * @param string $url_params Los párametros leidos de las URLS
     */
    function __construct($url_params = NULL)
    {
        $db_id = new CDMXId();
        parent::__construct(PROJECT_TABLE, PROJECT_FIELD_ID, $db_id);
        $this->p_service = new ProgramService($url_params, $db_id);
        $this->l_service = new LocationService($url_params, $db_id);
        $this->POST->service_task = function ($sender) {
            return send_service_petition($this, F_POST);
        };
    }
    /**
     * Define la acción de POST del servicio
     *
     * @param string $task La tarea seleccionada del servidor
     * @return string La respuesta del servidor
     */
    public function POST_action($task)
    {
        try {
            if (is_null($this->body)) {
                http_response_code(400);
                throw new Exception(ERR_BODY_IS_NULL);
            }
            else {
                if ($this->body_has(LOCATION_TABLE) && count($this->body->{LOCATION_TABLE}) > 0) {
                    inject_if_not_in($this->body, PROJECT_FIELD_EMP_IND, 0);
                    inject_if_not_in($this->body, PROJECT_FIELD_EMP_TMP, 0);
                    inject_if_not_in($this->body, PROJECT_FIELD_EMP_OBS, MSG_DFTL_NO_OBS);
                    $response = $this->POST->default_POST_action();
                    $response_project = json_decode($response);
                    if (has_result($response_project)) {
                        $response_program = $this->insert_program($response_project->{NODE_RESULT}[0]);
                        $response_location = $this->insert_location($this->body->{LOCATION_TABLE});

                    }
                    else {
                        http_response_code(409);
                        throw new Exception($response_project->{NODE_ERROR});
                    }

                }
                else {
                    http_response_code(400);
                    throw new Exception(ERR_LOC_MISSING);
                }
            }
        } catch (Exception $e) {
            $response = error_response($e->getMessage());
        }
        return $response;
    }
    /**
     * Inserta un nuevo programa a partir del resultado de un proyecto
     * insertado
     *
     * @param stdClass $result El resultado del proyecto insertado
     * @return stdClass La respuesta del servidor
     */
    public function insert_program($result)
    {
        inject_if_not_in($this->body, PROGRAM_FIELD_CONVENIO_ID, "");
        $program_body = (object)array(
            PROJECT_FIELD_ID => $result->{PROJECT_FIELD_ID},
            PROGRAM_FIELD_CONVENIO_ID => $this->body->{PROGRAM_FIELD_CONVENIO_ID},
            PROGRAM_FIELD_DATE => $this->body->{PROJECT_FIELD_DATE_START},
            PROGRAM_FIELD_MONTO => $this->body->{PROJECT_FIELD_MONTO}
        );
        $this->p_service->body = $program_body;
        return json_decode($this->p_service->POST->default_POST_action());
    }
    /**
     * Inserta una o más ubicaciones con base a un proyecto insertado
     * insertado
     *
     * @param stdClass $clv_proyecto La clave del proyecto insertado
     * @param stdClass[] $locations Las ubicaciones a insertar 
     * @return stdClass La respuesta del servidor
     */
    public function insert_location($clv_proyecto, $locations)
    {
        foreach ($locations as &$location)
            inject_if_not_in($location, PROJECT_FIELD_ID, $clv_proyecto);
        $this->l_service->body = $locations;
        return json_decode($this->l_service->POST->insert_bulk());
    }
    /**
     * Close the connection to MySQL
     * @return void
     */
    public function close()
    {
        $this->connector->close();
        $this->p_service->close();
    }
}
?>