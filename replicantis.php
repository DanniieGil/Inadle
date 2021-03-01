<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/list_products.css" />    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INADLE</title>
    
</head>
<body>
<p id="test"></p>
<form action="menu.php" method="post">
<input type="submit" value="MENÚ PRINCIPAL" class="button"></input> 
</form>
<?php 
include ('actions.php');
?>

<h3 align="center">REPLICANTIS MERCADOLIBRE</h3>
<p>Link Mercadolibre:   <input type="text" size = "80%"> <button class="button" Type="button">QUERY</button>     </p>

</body>
</html>