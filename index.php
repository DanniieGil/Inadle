<html>
<head>
    <title>Inadle DropShipping</title>
    <!-- App ID: 7186806967479210-->
    <!-- Secret Key: M9SHWPfDgwZk3Zb5hBgTvqpL3Z0w6C85-->
    <!-- Redirect URI: https://inadle.herokuapp.com/ -->
    <link rel="stylesheet" href="./css/menu.css" />

</head>
<body>

<form action="login.php" method="POST">
    <input class ="button" type="submit" value="LOGIN"></input></form>

<?php
$codigo_acceso = $_GET['code'];
    if ($codigo_acceso == "") {
        header("Location: http://auth.mercadolibre.com.co/authorization?response_type=code&client_id=7186806967479210&redirect_uri=https://inadle.herokuapp.com/");
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