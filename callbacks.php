<?php
echo "test1";
  // Capturando el objeto JSON
  $body = @file_get_contents('php://input'); 

  // Decodificando el objeto JSON
  $event_json = json_decode($body);  
?>