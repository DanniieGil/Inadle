<?php
/* Leemos los datos proporcionados en los parámetros GET */
$datos = [
  'user_id' => $_GET['user_id'],
  'resource' => $_GET['resource'],
];
/* Guardamos la información en un archivo de registro */
file_put_contents(
  'registro.log',
  json_encode($datos) . PHP_EOL,
  FILE_APPEND
);
?>