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

<h3 align="center">PUBLICADOR MANUAL</h3>
    <table>
    
    <tr><td><label>Titulo:</label></td>             
        <td><input type="text" id="title" maxlength="60" value="" size="60%"></input>
        <button class="button" onclick="Predictor()">PREDICTOR</button></td></tr>

    <tr><td><label>Categoria:</label></td>          
        <td><input type="text" id="category_id"></input>
        <input type="text" id="category_name"></input>
        <input type="text" id="BRAND"></input>
        <input type="text" id="LINE"></input>
        <input type="text" id="MODEL"></input>
        </td></td></tr>
    
    <tr><td><label>Precio:</label>                  
        <td><input type="text" id="price"> </input>
            <select name="moneda" id="currency_id">
                <option value="COP">COP</option>
                <option value="MX">MXN</option>
                <option value="ARS">ARG</option>
            </select>  
            
            <label>Cantidad:</label> 
            <input type="text" id="available_quantity" value="10" size="1%"> </input> 
            <label>Condicion:</label>
            <select name="conditions" id="condition"> 
                <option value="new">Nuevo</option>
                <option value="used">Usado</option></select>
                <label>Tipo de Publicacion:</label>
                <select name="listing_type_id" id="listing_type_id">
                <option value="gold_special">Clásica</option>
                <option value="gold_pro">Premium</option>
                <option value="free">Gratuita</option>
                <!-- <option value="gold_premium">Oro Premium</option> -->
                <!-- <option value="gold">Oro</option> -->
                <!--<option value="silver">Plata</option> -->
                <!--<option value="bronze">Bronce</option> -->
            </select>
            </td></tr>          

    <tr><td><label>Descripcion:</label>             
        <td><textarea name="descripcion" id="description" cols="100" rows="20"  maxlength="49999"> PRODUCTO DISPONIBLE DE 5 A 12 DÍAS DESPUÉS DE LA COMPRA.
--------------------------------------------------------------------------------------
Preguntar por disponibilidad antes de realizar la compra.

--------------------------------------------------------------------------------------

</textarea></td></tr>

    <tr><td><label>Color:</label>                   
        <td><input type="text" id="color" size="5%" value="Negro"> </input>
        <label>Video Id:</label>    
        <input type="text" id="video_id" size="15%"> </input>
        </td></tr>


    <tr><td><label>Garantia:</label> 
    <td><input type="text" id="WARRANTY_TIME_a" value="12" size="1%"></input>   
            <select name="WARRANTY_TIME_b" id="WARRANTY_TIME_b">
                    <option value="meses">meses</option>
                    <option value="días">días</option>
                    <option value="años">años</option>
            </select> 
            
           <select name="WARRANTY_TYPE" id="WARRANTY_TYPE">
                    <option value="Garantía de fábrica">Garantía de fábrica</option>
                    <option value="Garantía del vendedor">Garantía del vendedor</option>
                    <option value="Sin garantía">Sin garantía</option>
            </select> </td></tr>

    <tr><td><label>Dias Disponible</label> 
    <td><input type="text" id="MANUFACTURING_TIME_number" value="12" size="1%"></input>
    <label>Codigo Universal:</label> 
    <input type="text" id="GTIN" value="8437558160016" size="12%"></input> 
    </td></tr>

    <tr><td><label>Fotos:</label> </td><td>
            <input type="text" id="foto_1" size="7%">  </input>
            <input type="text" id="foto_2" size="7%" > </input>
            <input type="text" id="foto_3" size="7%">  </input>
            <input type="text" id="foto_4" size="7%">  </input>
            <input type="text" id="foto_5" size="7%">  </input>
            <input type="text" id="foto_6" size="7%">  </input>
    </td></tr>

    <td colspan="2" align="center"><button class="button" onclick="PublicarOne()">PUBLICAR</button></td>
    
    </table>

</body>
</html>