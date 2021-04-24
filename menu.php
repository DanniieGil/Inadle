<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/inadle.css"/>  
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Inadle</title>
    <?php
    include "actions.php";
    ?>

</head>
<body>

<p class="user-info" id="user-info"></p>
<script>
        Getuser();
</script>

<div class="container__menu"> 
    <button class="button__menu" onclick="window.location.href='../publicone.php'">PUBLICAR MANUAL</button>
    <button class="button__menu" onclick="window.location.href='../list_products.php'">LISTA PUBLICACIONES</button>
    <button class="button__menu" onclick="window.location.href='../quaestiones.php'">PREGUNTAS Y RESPUESTAS</button>
    <button class="button__menu" onclick="window.location.href='../replicantis.php'" disabled>AMAZON LINK</button>
    <button class="button__menu" onclick="TESTUSER()" disabled>CREATE TEST USER</button>
</div>

</body>
</html> 