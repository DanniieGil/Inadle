<?php
echo "6";
$data = json_decode(file_get_contents('php://input'), true);
echo "Welcome ". $data. "<br />";
?>