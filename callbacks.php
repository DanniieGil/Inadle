<?php
echo "8";
session_start();
$data = json_decode(file_get_contents('php://input'), true);
$_SESSION['application_id']   =  $data["application_id"];
echo $_SESSION['application_id'] ;
?>