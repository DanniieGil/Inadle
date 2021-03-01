<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/menu.css" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Inadle</title>
    <?php
    include "actions.php";
    ?>
</head>
<body>

<p id="demo"></p>
   <script>
        Getuser();
   </script>
   <form action="publicone.php" method="post"><button type="submit" class="button">PUBLICAR INDIVIDUAL</button></form> <br>
   <form action="list_products.php" method="post"><button type="submit" class="button">LISTA DE PUBLICACIONES</button></form><br>
   <form action="replicantis.php" method="post"><button type="submit" class="button">REPLICANTIS MERCADOLIBRE</button></form><br>
   <form action="quaestiones.php" method="post"><button type="submit" class="button">QUAESTIONES</button></form><br>
   <button class="button" onclick="TESTUSER()" disabled>CREATE TEST USER</button>
   
    </ul>
</body>
</html>