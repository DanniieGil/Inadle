

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/menu.css" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="menu.php" method="post">
        <input type="submit" value="Menu Principal" class="button"></input> 
    </form>

    <h1>Ejemplo Conexión Base de Datos</h1>
 <form action="conexion.php" method="POST">           
            <input type ="submit" value= "Conectar">
        </form> <br>
<form action="close.php" method="POST">
<input type ="submit" value= "Desconectar">
        </form> <br>
<form action="consulta.php" method="POST">
<input type ="submit" value= "Consultar">
        </form> <br>


    <?php
$host  = "sql10.freemysqlhosting.net";
$user  = "sql10396732";
$pass  = "47PRwHIvys";
// Create connection
$connection = mysqli_connect($host, $user, $pass);
// Check connection
if(!$connection) 
        {
            echo "No se ha podido conectar con el servidor" . mysql_error();
        }
  else
        {
            echo "Hemos conectado al servidor <br>" ;
        }
?>
</body>
</html>