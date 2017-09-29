<?php 
/****************************************
 ************ Servicios Disponibles ******
 *****************************************/
/**
 * @var string SERVICE_USER
 * El nombre del servicio que administra a los usuarios
 */
const SERVICE_USER = 'User';
/**
 * @var string VIGILANT_KEY
 * La llave del grupo vigilante
 */
const VIGILANT_KEY = '{A5070BC1-E488-4ff9-9989-CAD186CB3ABC}';
/**
 * @var string GROUP_ADMIN_KEY
 * La llave de un administrador de grupo
 */
const GROUP_ADMIN_KEY = '{61666F71-FDDF-42c6-A815-4E52DBBCD9B9}';
/**
 * @var string CDMX_USER_KEY
 * La llave de un usuario registrado en el sistema de CDMX
 */
const CDMX_USER_KEY = '{3BC046AA-B130-49a1-A723-914776761DAF}';
/**
 * @var string CDMX_USER_KEY
 * La llave de la papelera de reciclaje
 */
const RECYCLER_KEY = '{CA109C50-2F7B-4283-816E-54D24060F13D}';
/****************************************
 ***** Grupos de niveles de acceso ******
 ***************************************/
/**
 * @var string GROUP_VIGILANT
 * El nombre del grupo vigilante
 */
const GROUP_VIGILANT = "vigilante";
/**
 * @var string GROUP_ADMIN_GRP
 * El nombre del grupo administradores de grupo
 */
const GROUP_ADMIN_GRP = "grp_admin";
/**
 * @var string GROUP_NAMELESS
 * El nombre del grupo de super usuarios
 */
const GROUP_SUPER = "super";
/**
 * @var string GROUP_CDMX_USER
 * El nombre del grupo para usuarios de CDMX
 */
const GROUP_CDMX_USER = "cdmx_user";
/**
 * @var string GROUP_RECYCLE_BIN
 * El nombre del grupo papelera de reciclaje
 */
const GROUP_RECYCLE_BIN = "recycle_bin";

/****************************************
 ************** Tasks *******************
 *****************************************/
/**
 * @var string CDMX_TASK_CHECK
 * El nombre de la tarea que revisa los datos del usuario
 */
const CDMX_TASK_CHECK = "Check";
/**
 * @var string CDMX_TASK_JOIN_GROUP
 * El nombre de la tarea que agregá un usuario a un grupo
 */
const CDMX_TASK_JOIN_GROUP = "Join";
/**
 * @var string CDMX_TASK_LEAVE_GROUP
 * El nombre de la tarea en la que un usuario abandona a un grupo
 */
const CDMX_TASK_LEAVE_GROUP = "Leave";
/**
 * @var string CDMX_TASK_CREATE_ADMIN
 * El nombre de la tarea que crea un administrador
 */
const CDMX_TASK_CREATE_ADMIN = "NAdmin";
/**
 * @var string CDMX_TASK_CREATE_VIGILANT
 * El nombre de la tarea que crea un vigilante
 */
const CDMX_TASK_CREATE_VIGILANT = "NVigilant";
/****************************************
 ************** Errores y Mensajes *******
 *****************************************/
/**
 * @var string ERR_BAD_LOGIN
 * El resultado para cuando los datos de sesión son incorrectos.
 */
const ERR_BAD_LOGIN = "Los datos de inicio de sesión son incorrectos.";
/**
 * @var string ERR_CREATING_USER
 * El mensaje de error cuando no se puede crear el usuario
 */
const ERR_CREATING_USER = "No se pudo crear el usuario, detalles [%s].";
/**
 * @var string ERR_LOC_MISSING
 * El mensaje de error cuando no se encuentran ubicaciones en el body
 */
const ERR_LOC_MISSING = "No se pudo crear el proyecto, se requiere por lo menos una ubicación.";
/**
 * @var string ERR_USER_MISSING
 * El mensaje de error cuando no se encuentran el id del usuario
 */
const ERR_USER_MISSING = "No se encontro el usuario con el id [%d]";
/**
 * @var string ERR_DEL_SYSTEM_GROUP
 * El mensaje de error cuando se intenta borrar un grupo de sistema
 */
const ERR_DEL_SYSTEM_GROUP = "No se puede borrar un grupo de sistema [%s]";
/**
 * @var string MSG_LOGOUT
 * El mensaje cuando se cierra la sesión en la aplicación
 */
const MSG_LOGOUT = "Sesión cerrada.";
/**
 * @var string MSG_DFTL_NO_OBS
 * El mensaje cuando no existe ninguna observación
 */
const MSG_DFTL_NO_OBS = "Ninguna observación.";
/****************************************
 ** Nombres de tablas y campos de la BD **
 *****************************************/
/**
 * @var string USER_TABLE
 * El nombre de la tabla de usuarios.
 */
const USER_TABLE = 'usuarios';
/**
 * @var string USER_GROUP_TABLE
 * El nombre de la vista de datos de usuario.
 */
const USER_GROUP_TABLE = 'user_groups';
/**
 * @var string USER_FIELD_ID
 * El nombre para el campo clave de usuario.
 */
const USER_FIELD_ID = 'clv_usuario';
/**
 * @var string USER_FIELD_NAME
 * El nombre para el campo nombre real del usuario.
 */
const USER_FIELD_NAME = 'nombre';
/**
 * @var string USER_FIELD_TYPE
 * El nombre para el campo tipo usuario.
 */
const USER_FIELD_TYPE = 'tipo_usuario';
/**
 * @var string PROJECT_TABLE
 * El nombre de la tabla de proyectos.
 */
const PROJECT_TABLE = 'proyectos';
/**
 * @var string PROJECT_TABLE_LOCATION
 * El nombre de la tabla de ubicaciones de proyectos.
 */
const PROJECT_TABLE_LOCATION = 'proyecto_datos_geograficos';
/**
 * @var string PROJECT_FIELD_ID
 * El nombre para el campo clave de proyecto.
 */
const PROJECT_FIELD_ID = 'clv_proyecto';
/**
 * @var string PROJECT_FIELD_TYPE
 * El nombre para el campo tipo de proyecto
 */
const PROJECT_FIELD_TYPE = 'tipo_proyecto';
/**
 * @var string PROJECT_FIELD_DATE_START
 * El nombre para el campo fecha de inicio de contrato".
 */
const PROJECT_FIELD_DATE_START = "fecha_inicio_contrato";
/**
 * @var string PROJECT_FIELD_DATE_END
 * El nombre para el campo fecha de fin de contrato".
 */
const PROJECT_FIELD_DATE_END = "fecha_termino_contrato";
/**
 * @var string PROJECT_FIELD_MONTO
 * El nombre para el campo monto de contrato.
 */
const PROJECT_FIELD_MONTO = 'monto_contrato';
/**
 * @var string PROJECT_FIELD_NAME
 * El nombre para el campo nombre de proyecto.
 */
const PROJECT_FIELD_NAME = 'nom_proyecto';
/**
 * @var string PROJECT_FIELD_EMP_IND
 * El nombre para el campo empleos indirectos.
 */
const PROJECT_FIELD_EMP_IND = 'empleos_indirectos';
/**
 * @var string PROJECT_FIELD_EMP_TMP
 * El nombre para el campo empleos temporales.
 */
const PROJECT_FIELD_EMP_TMP = 'empleos_temporales';
/**
 * @var string PROJECT_FIELD_OBS
 * El nombre para el campo observaciones.
 */
const PROJECT_FIELD_OBS = 'observaciones';
/**
 * @var string PROJECT_FIELD_CONTRAC
 * El nombre para el campo contractual de proyecto.
 */
const PROJECT_FIELD_CONTRAC = 'contractual';
/**
 * @var string PROJECT_FIELD_ESC
 * El nombre para el campo escalatorio de proyecto.
 */
const PROJECT_FIELD_ESC = 'escalatorio';
/**
 * @var string PROJECT_FIELD_ADD
 * El nombre para el campo adicional de proyecto.
 */
const PROJECT_FIELD_ADD = 'adicional';
/**
 * @var string PROJECT_FIELD_EXTRA
 * El nombre para el campo extraordinaria de proyecto.
 */
const PROJECT_FIELD_EXTRA = 'extraordinaria';
/**
 * @var string PROJECT_FIELD_RECLAMOS
 * El nombre para el campo de reclamos.
 */
const PROJECT_FIELD_RECLAMOS = 'reclamos';
/**
 * @var string PROGRAM_TABLE
 * El nombre de la tabla de programas.
 */
const PROGRAM_TABLE = 'programas';
/**
 * @var string PROGRAM_FIELD_ID
 * El nombre para el campo clave de programa.
 */
const PROGRAM_FIELD_ID = 'clv_programa';
/**
 * @var string PROGRAM_FIELD_CONVENIO_ID
 * El nombre para el campo id de convenio.
 */
const PROGRAM_FIELD_CONVENIO_ID = 'id_convenio';
/**
 * @var string PROGRAM_FIELD_MONTO
 * El nombre para el campo monto de convenio.
 */
const PROGRAM_FIELD_MONTO = 'monto_convenio';
/**
 * @var string PROGRAM_FIELD_DATE
 * El nombre para el campo fecha de convenio.
 */
const PROGRAM_FIELD_DATE = 'fecha_convenio';
/**
 * @var string HISTORIC_TABLE
 * El nombre de la tabla de historicos.
 */
const HISTORIC_TABLE = 'historicos';
/**
 * @var string HISTORIC_FIELD_TOT_PROGRAM
 * El nombre para el campo total de avance programado.
 */
const HISTORIC_FIELD_TOT_PROGRAM = 'avance_programado_total';
/**
 * @var string HISTORIC_FIELD_TOT_PHYSICAL
 * El nombre para el campo total de avance fisíco.
 */
const HISTORIC_FIELD_TOT_PHYSICAL = 'avance_fisico_total';
/**
 * @var string HISTORIC_FIELD_TOT_FINANCIAL
 * El nombre para el campo total de avance financiero.
 */
const HISTORIC_FIELD_TOT_FINANCIAL = 'avance_financiero_total';
/**
 * @var string HISTORIC_FIELD_PERCENT_PROGRAM
 * El nombre para el campo total de avance programado.
 */
const HISTORIC_FIELD_PERCENT_PROGRAM = 'avance_programado_percent';
/**
 * @var string HISTORIC_FIELD_PERCENT_PHYSICAL
 * El nombre para el campo total de avance fisíco.
 */
const HISTORIC_FIELD_PERCENT_PHYSICAL = 'avance_financiero_percent';
/**
 * @var string HISTORIC_FIELD_PERCENT_FINANCIAL
 * El nombre para el campo total de avance financiero.
 */
const HISTORIC_FIELD_PERCENT_FINANCIAL = 'avance_financiero_total';
/**
 * @var string HISTORIC_DATA_TABLE
 * El nombre de la vista de historicos programados.
 */
const HISTORIC_DATA_TABLE = 'datos_historicos_programados';
/**
 * @var string PROGRESS_TABLE
 * El nombre de la tabla de avances.
 */
const PROGRESS_TABLE = 'avances';
/**
 * @var string PROGRESS_PROGRAM_TABLE
 * El nombre de la vista avances por programas.
 */
const PROGRESS_PROGRAM_TABLE = 'avances_programas';
/**
 * @var string PROGRESS_ONAC_TABLE
 * El nombre de la vista avances cuadro ONAC.
 */
const PROGRESS_ONAC_TABLE = 'avances_onac';
/**
 * @var string PROGRESS_DATES_TABLE
 * El nombre de la vista de fechas de avances historicos.
 */
const PROGRESS_DATES_TABLE = 'fechas_avances';
/**
 * @var string PROGRESS_HISTORIC_TABLE
 * El nombre de la vista avances historicos.
 */
const PROGRESS_HISTORIC_TABLE = 'datos_avances_historicos';
/**
 * @var string PROGRESS_FIELD_PROGRAM
 * El nombre para el campo avance programado.
 */
const PROGRESS_FIELD_PROGRAM = 'avance_programado';
/**
 * @var string PROGRESS_FIELD_PHYSICAL
 * El nombre para el campo avance físisco.
 */
const PROGRESS_FIELD_PHYSICAL = 'avance_fisico';
/**
 * @var string PROGRESS_FIELD_FINANCIAL
 * El nombre para el campo avance físisco.
 */
const PROGRESS_FIELD_FINANCIAL = 'avance_financiero';
/**
 * @var string CAPTURE_FIELD_ID
 * El nombre para el campo id de captura.
 */
const CAPTURE_FIELD_ID = 'id_captura';
/**
 * @var string CAPTURE_FIELD_DATE
 * El nombre para el campo fecha de captura.
 */
const CAPTURE_FIELD_DATE = 'fecha_captura';
/**
 * @var string BATCH_TABLE
 * El nombre de la tabla de partidas.
 */
const BATCH_TABLE = 'partidas';
/**
 * @var string BATCH_FIELD_ID
 * El nombre para el campo clave de partida.
 */
const BATCH_FIELD_ID = 'clv_partida';
/**
 * @var string BATCH_NAME
 * El nombre para el campo nombre de partida.
 */
const BATCH_FIELD_NAME = 'nom_partida';
/**
 * @var string BATCH_FIELD_START_DATE
 * El nombre para el campo fecha de inicio de partida.
 */
const BATCH_FIELD_START_DATE = 'fecha_inicio';
/**
 * @var string BATCH_FIELD_END_DATE
 * El nombre para el campo fecha de fin de partida.
 */
const BATCH_FIELD_END_DATE = 'fecha_termino';
/**
 * @var string BATCH_FIELD_MONTO
 * El nombre para el campo monto de partida.
 */
const BATCH_FIELD_MONTO = 'monto_total_programado';
/**
 * @var string LOCATION_TABLE
 * El nombre de la tabla de ubicaciones.
 */
const LOCATION_TABLE = 'datos_geograficos';
/**
 * @var string LOCATION_FIELD_ID
 * El nombre para el campo clave de ubicación.
 */
const LOCATION_FIELD_ID = 'clv_dg';
/**
 * @var string LOCATION_FIELD_TYPE
 * El nombre para el campo tipo de geometría.
 */
const LOCATION_FIELD_TYPE = 'tipo_geometria';
/**
 * @var string LOCATION_FIELD_LAT
 * El nombre para el campo latitud.
 */
const LOCATION_FIELD_LAT = 'latitud';
/**
 * @var string LOCATION_FIELD_LON
 * El nombre para el campo longitud.
 */
const LOCATION_FIELD_LON = 'longitud';
/**
 * @var string PHOTOGRAPHY_TABLE
 * El nombre de la tabla de fotografías.
 */
const PHOTOGRAPHY_TABLE = 'fotografias';
/**
 * @var string PHOTOGRAPHY_FIELD_ID
 * El nombre para el campo clave de fotografía.
 */
const PHOTOGRAPHY_FIELD_ID = 'clv_fotografia';
/**
 * @var string PHOTOGRAPHY_FIELD_NAME
 * El nombre para el campo nombre del archivo.
 */
const PHOTOGRAPHY_FIELD_NAME = 'path_fotografia';
/**
 * @var string PHOTOGRAPHY_FIELD_DESC
 * El nombre para el campo descripción de la fotografía.
 */
const PHOTOGRAPHY_FIELD_DESC = 'desc_fotografia';
/**
 * @var string PHOTOGRAPHY_FIELD_DATE
 * El nombre del campo fecha de ingreso de la fotografìa.
 */
const PHOTOGRAPHY_FIELD_DATE = 'fecha_ingreso';
/**
 * @var string PHOTOGRAPHY_FIELD_MAP
 * El nombre del campo mapa de la fotografìa.
 */
const PHOTOGRAPHY_FIELD_MAP = 'mapa';
/****************************************
 ************** Nodos ********************
 *****************************************/
/**
 * @var string NODE_MSG
 * El nombre del nodo que guarda los mensajes
 */
const NODE_MSG = 'msg';
/**
 * @var string MSG_NODE_PROJECT
 * El nombre para el nodo JSON que agrupa resultados de proyectos.
 */
const MSG_NODE_PROJECT = 'projects';
/**
 * @var string MSG_NODE_PROGRAM
 * El nombre para el nodo JSON que agrupa resultados de programs.
 */
const MSG_NODE_PROGRAM = 'programas';
/**
 * @var string MSG_NODE_LOC
 * El nombre para el nodo JSON que agrupa ubicaciones.
 */
const MSG_NODE_LOC = 'locations';
/**
 * @var string MSG_NODE_LOGIN
 * El nombre para el nodo JSON define un login.
 */
const MSG_NODE_LOGIN = 'login';
/**
 * @var string MSG_NODE_PHOTOGRAPHY
 * El nombre para el nodo JSON que agrupa fotografías.
 */
const MSG_NODE_PHOTOGRAPHY = 'photographies';
/**
 * @var string MSG_NODE_PROGRESS
 * El nombre para el nodo JSON que agrupa avances.
 */
const MSG_NODE_PROGRESS = 'avances';
/**
 * @var string MSG_NODE_HISTORIC
 * El nombre para el nodo JSON que agrupa historicos.
 */
const MSG_NODE_HISTORIC = 'historicos';
/**
 * @var string MSG_NODE_BATCH
 * El nombre para el nodo JSON que agrupa partidas.
 */
const MSG_NODE_BATCH = 'partidas';
/**
 * @var string MSG_NODE_ASOC
 * El nombre para el nodo JSON que agrupa asociados.
 */
const MSG_NODE_ASOC = 'asociados';
/**
 * @var string MSG_NODE_LABELS
 * El nombre para el nodo JSON que agrupa labels.
 */
const MSG_NODE_LABELS = 'labels';
/**
 * @var string MSG_NODE_SERIES
 * El nombre para el nodo JSON que agrupa series.
 */
const MSG_NODE_SERIES = 'series';
/**
 * @var string MSG_NODE_MONTOS
 * El nombre para el nodo JSON que agrupa montos.
 */
const MSG_NODE_MONTOS = 'montos';
/**
 * @var string MSG_NODE_DATES
 * El nombre para el nodo JSON que agrupa fechas.
 */
const MSG_NODE_DATES = 'dates';
/**
 * @var string MSG_NODE_PERCENT
 * El nombre para el nodo JSON que agrupa porcentajes.
 */
const MSG_NODE_PERCENT = 'percents';
/**
 * @var string MSG_NODE_FEATURE_COLL
 * El nombre para el nodo JSON que agrupa una colección de features.
 */
const MSG_NODE_FEATURE_COLL = 'FeatureCollection';
/**
 * @var string MSG_NODE_FEATURES
 * El nombre para el nodo JSON que agrupa una features.
 */
const MSG_NODE_FEATURES = 'features';
/**
 * @var string MSG_NODE_FEATURE_TYPE
 * El nombre para el nodo JSON que agrupa tipos de Google MAP.
 */
const MSG_NODE_MAP_TYPE = 'type';
/**
 * @var string MSG_NODE_MAP_GEO
 * El nombre para el nodo JSON que agrupa geometría de Google MAP.
 */
const MSG_NODE_MAP_GEO = 'geometry';
/**
 * @var string MSG_NODE_MAP_LOCATION
 * El nombre para el nodo JSON que agrupa ubicaciones de Google MAP.
 */
const MSG_NODE_MAP_LOCATION = 'coordinates';
/**
 * @var string MSG_NODE_RESULT
 * El nombre para el nodo JSON que agrupa resultados.
 */
const MSG_NODE_RESULT = 'result';
/**
 * @var string MSG_NODE_PROP
 * El nombre para el nodo JSON que agrupa propiedades.
 */
const MSG_NODE_PROP = 'properties';
?>