<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/inadle.css"/>  
    <script src="./js/jquery.min.js" type="text/javascript"></script>   
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inadle</title>
 
</head>
<body>
<p class="user-info" id="user-info"></p>
<?php include "actions.php"?>
<script>
    Getuser();
</script>




<div class="menu__list_products">
<button class="button__menu" onclick="window.location.href='../menu.php'">MENU PRINCIPAL</button>   
<button class="button__menu" onclick="Exhangemoney()" id="Exhangemoneybtn">USD/COP</button>  
<p id="exchangeUSDCOP"></p>
</div>


    <script>
        GetListProduct();
    </script>
    <p id="proba"></p>
    <table border="1" id="ListaPublicaciones" class="ListaPublicaciones">
        <thead>
            <th>ID</th>
            <th>Title</th>
            <th>Precio</th>
            <th>Status</th>
            <th>Action</th>
            <th>Amazon ASIN</th>
            <th>Amazon USD</th>
            <th>Amazon COP</th>
            <th>Price Variance</th>
            <th>Status BD</th>
            <th>UPDATE</th>
            <th>HEALTH</th>
            <th>SOLD QTY</th>
            <th>CREATED</th>
            
        </thead>
        <tbody id=cuerpoTabla>
        </tbody>
    
    </table>
   
</body>
</html>


