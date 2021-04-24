<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/inadle.css" /> 

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INADLE</title>
    <script src="./js/jquery.min.js" type="text/javascript"></script>
    <?php include "actions.php"?>
</head>
<body>
<p class="user-info" id="user-info"></p>

<script>
    Getuser();
</script>

<button class="button__menu" onclick="window.location.href='../menu.php'">MENU PRINCIPAL</button> 


 
<h3 align="center">¿QUAESTIONES?</h3>
<select name="FILTER_QUESTION" id="FILTER_QUESTION" onchange="QUAESTIONES()" class="btn waves-effect waves-light">
    <option value="UNANSWERED">UNANSWERED</option>
    <option value="">ALL</option>
    <option value="ANSWERED">ANSWERED</option>
</select>


<table border="1" id="ListaPublicaciones">
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
<input type="submit" class="button__menu" value="RESPONDER">
</form>
<?php 
$IdQuestion=$_GET["Id_Question"];
$TextQuestion=$_GET["Text_Question"];
if ($IdQuestion != null) {
    ANSWER2($IdQuestion, $TextQuestion);
} 
?>

<script>QUAESTIONES()</script>

</body>
</html>

