<?php
//Urabe 
include_once "/../../../../urabe/HasamiURLParameters.php";
include_once "/../../../../urabe/HasamiWrapper.php";
include_once "/../../../../urabe/Warai.php";
//CDMX
include_once "/../AppConst.php";
include_once "UserService.php";
try {
    $url_params = new HasamiURLParameters();
    $response = "";
    $service;
    if ($url_params->exists(KEY_SERVICE)) {
        http_response_code(200);
        switch ($url_params->parameters[KEY_SERVICE]) {
            case SERVICE_USER :
                $service = new UserService($url_params);
                break;
            default :
                throw new Exception(ERR_INVALID_SERVICE);
        }
        //La respuesta del servidor
        $response = print_response($service, $url_params);
    }
    else {
        http_response_code(404);
        throw new Exception(ERR_INVALID_SERVICE);
    }
} catch (Exception $e) {
    $response = error_response($e->getMessage());
}
echo $response;
?>