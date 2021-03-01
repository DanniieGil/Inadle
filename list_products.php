<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="./css/list_products.css" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inadle</title>
    <?php 
        include("actions.php");
            session_start();
            $tokenacceso = $_SESSION['token_acceso'];
            echo $tokenacceso;
    ?>
  
</head>
<body>

     
    <form action="menu.php" method="post">
        <input type="submit" value="Menu Principal" class="button"></input> 
    </form>
    <script>
        GetListProduct();
    </script>
    <p id="proba"></p>
    <table border="1">
        <thead>
            <th>ID</th>
            <th>Title</th>
            <th>Precio</th>
            <th>Status</th>
            <th>Action</th>
        </thead>
        <tbody id=cuerpoTabla>
        </tbody>
    
    </table>
   
</body>
</html>


