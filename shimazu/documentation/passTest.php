<?php
include_once "/../../alice/Caterpillar.php";
$cat = new Caterpillar();
echo '"'.$cat->encrypt("x").'"';
?>