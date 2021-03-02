<?php
echo "test2";
  // Capturando el objeto JSON
  $body = @file_get_contents('php://input'); 

  // Decodificando el objeto JSON
  $event_json = json_decode($body);
  var_dump($event_json);
?>