<html>
<head>
    <title>Inadle DropShipping</title>
    <!-- App ID: 7773261594916645 -->
    <!-- Secret Key: nKMwXSOECQaj1xN73fFb3XWcKSYM2Otd -->
    <!-- Redirect URI: http://localhost:8000/ -->
</head>
<body>

<form action="login.php" method="POST">
    <input type="submit" value="LOGIN"></input></form>

<?php
$codigo_acceso = $_GET['code'];
    if ($codigo_acceso == "") {
        header("Location: http://auth.mercadolibre.com.co/authorization?response_type=code&client_id=7773261594916645&redirect_uri=http://localhost:8000/");
        exit();
    } else {
    session_start();
    $_SESSION['codigo_acceso'] = $codigo_acceso;
   }
   session_start();
    $_SESSION['codigo_acceso'] = $codigo_acceso;
?>


<?php 


?>

</body>
</html>