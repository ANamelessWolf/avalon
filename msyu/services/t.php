<?php
function include_class($upp, $path, $classes)
{
    $ds = DIRECTORY_SEPARATOR;
    $path = "";
    for ($i = 1; $i <= $upp; $i++)
        $path .= $ds . '..';
    $path = realpath(dirname(__FILE__) . $path);
    return $path;
}
echo phpinfo();
// echo include_class(2,"urabe","hola.php")."<br>";
// echo include_class(1,"urabe","hola.php")."<br>";
?>