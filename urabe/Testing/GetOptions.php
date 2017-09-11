<?php
if (isset($_SERVER['PATH_INFO'])) {
    $request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
    var_dump($request);
}
var_dump($_GET);
?>