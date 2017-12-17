<?php
//Urabe 
include_once "../urabe/HasamiURLParameters.php";
include_once "../urabe/HasamiWrapper.php";
include_once "../urabe/Warai.php";
//Msyuu
include_once "Mashu.php";
include_once "services/CategoryService.php";
try {
    $url_params = new HasamiURLParameters();
    $response = "";
    $service;
    if ($url_params->exists(KEY_SERVICE)) {
        http_response_code(200);
        switch ($url_params->parameters[KEY_SERVICE]) {
            case SERVICE_CATEGORY :
                $service = new CategoryService($url_params);
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