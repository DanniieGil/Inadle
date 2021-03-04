<html>
<head>
    <title>Inadle DropShipping</title>
    <!-- App ID: 7186806967479210-->
    <!-- Secret Key: M9SHWPfDgwZk3Zb5hBgTvqpL3Z0w6C85-->
    <!-- Redirect URI: https://inadle.herokuapp.com/ -->
    <link rel="stylesheet" href="./css/menu.css" />
    <script src="./js/jquery.min.js" type="text/javascript"></script>   

</head>
<body>

<script>


</script>

<form action="login.php" method="POST">
    <input class ="button" type="submit" value="LOGIN"></input></form>

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


<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.2.9/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  var firebaseConfig = {
    apiKey: "AIzaSyDZbUPymOlPdMnRc7tjG0r8hFN-451CBaE",
    authDomain: "inadle-ship.firebaseapp.com",
    projectId: "inadle-ship",
    storageBucket: "inadle-ship.appspot.com",
    messagingSenderId: "50840900907",
    appId: "1:50840900907:web:97c2f9cff2c24226f4216e",
    measurementId: "G-8FNMBMNXDN"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>

</body>
</html>