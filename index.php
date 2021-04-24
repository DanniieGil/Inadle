<html>

<head>
    <title>Inadle DropShipping</title>
    <!-- App ID: 7186806967479210-->
    <!-- Secret Key: M9SHWPfDgwZk3Zb5hBgTvqpL3Z0w6C85-->
    <!-- Redirect URI: https://inadle.herokuapp.com/ -->
    <link rel="stylesheet" href="./css/inadle.css" />
    <script src="./js/jquery.min.js" type="text/javascript"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=DotGothic16&display=swap" rel="stylesheet">
</head>

<body>

    <form class="login__menu" action="login.php" method="POST">
        <input class="button__menu" type="submit" value="LOGIN"></input>
    </form>

    <?php
    $codigo_acceso = $_GET['code'];
    if ($codigo_acceso == "") {
        header("Location: http://auth.mercadolibre.com.co/authorization?response_type=code&client_id=7186806967479210&redirect_uri=http://localhost:8000/");
        exit();
    } else {
        session_start();
        $_SESSION['codigo_acceso'] = $codigo_acceso;
    }
    session_start();
    $_SESSION['codigo_acceso'] = $codigo_acceso;
    ?>

    </script>

</body>

</html>