<?php
    session_start();
    $url = "https://api.mercadolibre.com/oauth/token";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array("Content-Type: application/json", );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $codigoacceso = $_SESSION['codigo_acceso'];

    $data = <<<DATA
    {"grant_type": "authorization_code",
    "client_id" : "7186806967479210",
    "client_secret" : "M9SHWPfDgwZk3Zb5hBgTvqpL3Z0w6C85" ,
    "code" : $codigoacceso,
    "redirect_uri" : "http://localhost:8000/"} 
    DATA;
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($curl);
    curl_close($curl);
    $obj = json_decode($resp);
    
    $_SESSION["token_acceso"] = $obj -> {'access_token'};
    header("Location:http://localhost:8000/menu.php");
?>