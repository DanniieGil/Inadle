<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/list_products.css" />    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INADLE</title>
    <script src="./js/jquery.min.js" type="text/javascript"></script>
    
</head>
<body>
<p id="test"></p>
<form action="menu.php" method="post">
<input type="submit" value="MENÚ PRINCIPAL" class="button"></input> 
</form>



<h3 align="center">¿QUAESTIONES?</h3>
<select name="FILTER_QUESTION" id="FILTER_QUESTION" onchange="QUAESTIONES()">
    <option value="UNANSWERED">UNANSWERED</option>
    <option value="">ALL</option>
    <option value="ANSWERED">ANSWERED</option>
</select> <br><br>


<table border="1" class="table table-hover">
        <thead>
            <th>ID</th>
            <th>DATE CREATED</th>
            <th>ITEM ID</th>
            <th>STATUS</th>
            <th>QUESTION</th>
            <th>ANSWER</th>
            <th>ACTIONS</th>
            
        </thead>
        <tbody id=TablaQuestions>
        </tbody>
    </table> <br>



 
<form name="form1" method="GET" action="quaestiones.php">
<textarea name="Text_Question" cols="30" rows="10" style="margin: 0px; width: 801px; height: 157px;"></textarea><br>
Codigo Pregunta: <input type="text" name="Id_Question"/>
<input type="submit"/ value="RESPONDER">
</form>
<?php include ('actions.php');
$IdQuestion=$_GET["Id_Question"];
$TextQuestion=$_GET["Text_Question"];
if ($IdQuestion != null) {
    ANSWER2($IdQuestion, $TextQuestion);
} 

?>

<script>QUAESTIONES()</script>

</body>
</html>

