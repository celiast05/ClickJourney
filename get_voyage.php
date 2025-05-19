<?php
header('Content-Type: application/json');
$jsonFile = file_get_contents('json/voyage.json');
echo $jsonFile;
?>