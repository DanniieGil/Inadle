<?php
echo "7";
$data = json_decode(file_get_contents('php://input'), true);
echo "Welcome ". $data[0] . "<br />";
?>