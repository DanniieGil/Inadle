<?php
//----------------- SOLICITUD DEL TOKEN ACCESS 
function Request_Token($CodigoAcceso){
    $url = "https://api.mercadolibre.com/oauth/token";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array("Content-Type: application/json", );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    
    $data = <<<DATA
    {"grant_type": "authorization_code",
    "client_id" : "7186806967479210",
    "client_secret" : "M9SHWPfDgwZk3Zb5hBgTvqpL3Z0w6C85" ,
    "code" : $CodigoAcceso,
    "redirect_uri" : "https://inadle.herokuapp.com/"} 
    DATA;
    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($curl);
    curl_close($curl);
    $obj = json_decode($resp);
    //var_dump($obj);
    
    $_SESSION["token_acceso"] = $obj -> {'access_token'};}?>
<script>
//----------------- INFORMACION DEL USUARIO LOGUEADO 
function Getuser(){
    const url = "https://api.mercadolibre.com/users/me";
    var xhr = new XMLHttpRequest();
    var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
    const tokenacces = "Bearer "+ token;
    xhr.open("GET", url);
    xhr.setRequestHeader("Authorization", tokenacces);
    
    xhr.onreadystatechange = function () {
       if (xhr.readyState === 4) { //console.log(xhr.status); console.log(xhr.response);
          var result = JSON.parse(xhr.response);
          document.getElementById("demo").innerHTML = 'Id:' + result.id + ' Nickname: ' + result.nickname;
          sessionStorage.setItem("user_id", result.id);
          let user_id = sessionStorage.getItem('user_id');
          console.log(user_id);
         }};
    xhr.send();}

//----------------- DETALLE DE PRODUCTO INDIVIDUAL
function ProductDetailed(ItemId){
   var url = "https://api.mercadolibre.com/items/"+ItemId;
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   var xhr = new XMLHttpRequest();
   xhr.open("GET", url);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.setRequestHeader("Authorization", tokenacces); 
   const $cuerpoTabla = document.querySelector("#cuerpoTabla");
   xhr.onreadystatechange = function () {
   if (xhr.readyState === 4) {
      //console.log(xhr.status);
      //console.log(xhr.responseText);
      var result = JSON.parse(xhr.responseText);
      
      const $tr = document.createElement("tr");
      $cuerpoTabla.appendChild($tr);
      let $IdItem = document.createElement("td");
      $IdItem.textContent = result.id;
      $tr.appendChild($IdItem);
      let $Title = document.createElement("td");
      $Title.textContent = result.title;
      $tr.appendChild($Title);
      let $Price = document.createElement("td");
      $Price.textContent = result.price;
      $tr.appendChild($Price);
      let $Status = document.createElement("td");
      $Status.textContent = result.status;
      $tr.appendChild($Status);
      let $Actions = document.createElement("td");

      var btnPause = document.createElement("BUTTON"); btnPause.innerHTML = "PAUSE"; 
      btnPause.setAttribute("id",  result.id); btnPause.setAttribute("onclick", "PAUSED_PUBLICATION(this)");

      var btnActive = document.createElement("BUTTON"); btnActive.innerHTML = "ACTIVE"; 
      btnActive.setAttribute("id",  result.id); btnActive.setAttribute("onclick", "ACTIVE_PUBLICATION(this)");

      var btnInactive = document.createElement("BUTTON"); btnInactive.innerHTML = "INACTIVE"; 
      btnInactive.setAttribute("id",  result.id); btnInactive.setAttribute("onclick", "INACTIVE_PUBLICATION(this)");

      var btnClosed = document.createElement("BUTTON"); btnClosed.innerHTML = "CLOSED"; 
      btnClosed.setAttribute("id",  result.id); btnClosed.setAttribute("onclick", "CLOSED_PUBLICATION(this)");

      var btnDeleted = document.createElement("BUTTON"); btnDeleted.innerHTML = "DELETED"; 
      btnDeleted.setAttribute("id",  result.id); btnDeleted.setAttribute("onclick", "DELETED_PUBLICATION(this)");

      $Actions.appendChild(btnPause); 
      $Actions.appendChild(btnActive); 
      $Actions.appendChild(btnInactive);
      $Actions.appendChild(btnClosed); 
      $Actions.appendChild(btnDeleted); 
      $tr.appendChild($Actions);
      
      

   }};
   xhr.send();}

//----------------- LISTA DE TODOS LOS PRODUCTOS
function GetListProduct(){
   let user_id = sessionStorage.getItem('user_id');
   var url = "https://api.mercadolibre.com/users/"+ user_id + "/items/search?search_type=scan";
   var xhr = new XMLHttpRequest();
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   xhr.open("GET", url);
   var result = "";
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.setRequestHeader("Authorization", tokenacces);
   
   xhr.onreadystatechange = function () {
   if (xhr.readyState === 4) {
      //console.log(xhr.status);
      //console.log(xhr.responseText);
      result = JSON.parse(xhr.response);

      

      result.results.forEach(element => {
         ProductDetailed(element)
      });
   };
   };
   
   xhr.send();}

//----------------- PUBLICADOR MANUAL 
function PublicarOne(){
   var url = "https://api.mercadolibre.com/items";
   var xhr = new XMLHttpRequest();
   xhr.open("POST", url);
   var bearer="Bearer " + "<?php  session_start(); echo $_SESSION['token_acceso']  ;?>"; 
   xhr.setRequestHeader("Authorization", bearer);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
         //console.log(xhr.status); 
         console.log(xhr.response);
      }};

   var data = {
         "title": document.getElementById("title").value,
         "category_id":document.getElementById("category_id").value,
         "price": document.getElementById("price").value,
         "currency_id":document.getElementById("currency_id").value,
         "available_quantity":document.getElementById("available_quantity").value,
         "buying_mode":"buy_it_now",
         "condition":document.getElementById("condition").value,
         "listing_type_id":document.getElementById("listing_type_id").value,
         "description":{
            "plain_text":document.getElementById("description").value
         },
         "video_id":document.getElementById("video_id").value,
         "sale_terms":[
            {
               "id":"MANUFACTURING_TIME",
               "value_name":(document.getElementById("MANUFACTURING_TIME_number").value + "días")
            },
            {
               "id":"WARRANTY_TYPE",
               "value_name":document.getElementById("WARRANTY_TYPE").value
            },
            {
               "id":"WARRANTY_TIME",
               "value_name": (document.getElementById("WARRANTY_TIME_a").value + " " + document.getElementById("WARRANTY_TIME_b").value)
            }
         ],
         "pictures":[
            {"source":document.getElementById("foto_1").value},
            //{  "source":document.getElementById("foto_2").value},
            //{  "source":document.getElementById("foto_3").value},
            //{  "source":document.getElementById("foto_4").value},
            //{  "source":document.getElementById("foto_5").value},
            //{  "source":document.getElementById("foto_6").value}
         ],
         "attributes":[
            {
               "id":"GTIN",
               "value_name":document.getElementById("GTIN").value
            },
            {
               "id":"BRAND",
               "value_name":document.getElementById("BRAND").value
            },
            {
               "id":"MODEL",
               "value_name":document.getElementById("MODEL").value
            },
            {
               "id":"ALPHANUMERIC_MODEL",
               "value_name":document.getElementById("MODEL").value
            },
            {
               "id":"LINE",
               "value_name":document.getElementById("LINE").value
            },
         ],
         "variations": [
            {    
               "picture_ids": [
                  document.getElementById("foto_1").value
               ],
               "available_quantity" : document.getElementById("available_quantity").value,
               "price": document.getElementById("price").value,
               "attribute_combinations": [
                     {
                        "id": "COLOR",
                        "value_name": document.getElementById("color").value,
                     }
               ]
            }
         ]
   };
   var data2 = JSON.stringify(data);
   xhr.send(data2);}

//----------------- PREDICTOR AUTOMATICO DE CATEGORIA
function Predictor(){
   
   // LIMPIA INPUT VIEJOS DE PREDICCION
   document.getElementById("category_id").value = "";
   document.getElementById("category_name").value = "";
   document.getElementById("BRAND").value = "";
   document.getElementById("LINE").value = "";
   document.getElementById("MODEL").value = "";
   
   var titulo_pub = document.getElementById("title").value;
   var url = "https://api.mercadolibre.com/sites/MCO/domain_discovery/search?limit=1&q="+titulo_pub;
   var xhrt = new XMLHttpRequest();
   xhrt.open("GET", url);
   xhrt.onreadystatechange = function () {
   if (xhrt.readyState === 4) {//console.log(xhrt.status); console.log(xhrt.response);
      var results = JSON.parse(xhrt.response);
      console.log(results['0']);
      var categoryID = results['0']['category_id'];
      var categoryNAME =results['0']['category_name'];
      if (categoryID != undefined) { document.getElementById("category_id").value = categoryID};
      if (categoryNAME != undefined) { document.getElementById("category_name").value = categoryNAME};

      var Checking = results['0']["attributes"];
      if (Checking == "") {
         document.getElementById("BRAND").value = categoryNAME;
         document.getElementById("MODEL").value = categoryNAME;
         document.getElementById("LINE").value = categoryNAME;
      } else if (Checking.length == 1) {
         var vBRAND =  results['0']['attributes'][0]['value_name'];
         document.getElementById("BRAND").value = vBRAND;
         document.getElementById("MODEL").value = vBRAND;
         document.getElementById("LINE").value = vBRAND;
      } else if (Checking.length >= 2) {
         //BRAND
         var vBRAND =  results['0']['attributes'][0]['value_name'];
         document.getElementById("BRAND").value = vBRAND;
            
         //CHECK MODEL
            var proba1 = results['0']['attributes'][1]['id'];
            console.log(results);
            if (proba1 == "MODEL" || proba1 == "ALPHANUMERIC_MODEL" ) {
               var vMODEL =  results['0']['attributes'][1]['value_name'];
               document.getElementById("MODEL").value = vMODEL;
               console.log(proba1);
               console.log(vMODEL);
            } else if (proba1 == "LINE") {
               var vLINE = results['0']['attributes'][1]['value_name'];
               document.getElementById("LINE").value = vLINE;
            }
    
         //CHECK LINE   
         check3 = results['0']['attributes'][2];
            if (check3 == undefined) {
               document.getElementById("LINE").value = vBRAND;
            } else {
               var proba2 = results['0']['attributes'][2]['id'];
                if (proba2 == "MODEL") {
               var vMODEL =  results['0']['attributes'][2]['value_name'];
               document.getElementById("MODEL").value = vMODEL;
               } else if (proba2 == "LINE") {
               var vLINE = results['0']['attributes'][2]['value_name'];
               document.getElementById("LINE").value = vLINE;
               } else{
                  console.log("ok");
               }
            }

      }; 
  

   }
   }
   xhrt.send(null);}

//----------------- ACTIONS: Active, Inactive, Pauses, Closed, Deleted.
function PAUSED_PUBLICATION(ITEM_ID){
   var url = "https://api.mercadolibre.com/items/"+ITEM_ID.id;
   var xhr = new XMLHttpRequest();
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   xhr.open("PUT", url);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.setRequestHeader("Authorization", tokenacces);
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
         console.log(xhr.status);
         console.log(xhr.responseText);
      }};

   var data = `{
   "status":"paused"
   }`;
   xhr.send(data);}

function ACTIVE_PUBLICATION(ITEM_ID){
   var url = "https://api.mercadolibre.com/items/"+ITEM_ID.id;
   var xhr = new XMLHttpRequest();
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   xhr.open("PUT", url);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.setRequestHeader("Authorization", tokenacces);
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
         console.log(xhr.status);
         console.log(xhr.responseText);
      }};

   var data = `{
   "status":"active"
   }`;
   xhr.send(data);}

function INACTIVE_PUBLICATION(ITEM_ID){
   var url = "https://api.mercadolibre.com/items/"+ITEM_ID.id;
   var xhr = new XMLHttpRequest();
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   xhr.open("PUT", url);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.setRequestHeader("Authorization", tokenacces);
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
         console.log(xhr.status);
         console.log(xhr.responseText);
      }};

   var data = `{
   "status":"inactive"
   }`;
   xhr.send(data);}

function CLOSED_PUBLICATION(ITEM_ID){
   var url = "https://api.mercadolibre.com/items/"+ITEM_ID.id;
   var xhr = new XMLHttpRequest();
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   xhr.open("PUT", url);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.setRequestHeader("Authorization", tokenacces);
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
         console.log(xhr.status);
         console.log(xhr.responseText);
      }};

   var data = `{
   "status":"closed"
   }`;
   xhr.send(data);}

function DELETED_PUBLICATION(ITEM_ID){
   var url = "https://api.mercadolibre.com/items/"+ITEM_ID.id;
   var xhr = new XMLHttpRequest();
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   xhr.open("PUT", url);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.setRequestHeader("Authorization", tokenacces);
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
         console.log(xhr.status);
         console.log(xhr.responseText);
      }};

   var data = `{
   "deleted":"true"
   }`;
   xhr.send(data);}


//----------------- ACTIONS: Active, Inactive, Pauses, Closed, Deleted.
function TESTUSER(){
   var url = "https://api.mercadolibre.com/users/test_user";
   var xhr = new XMLHttpRequest();
   xhr.open("POST", url);
   var token= ("<?php session_start(); echo $_SESSION['token_acceso']; ?>");
   const tokenacces = "Bearer "+ token;
   xhr.setRequestHeader("Authorization", tokenacces);
   xhr.setRequestHeader("Content-Type", "application/json");
   xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
         console.log(xhr.status);
         console.log(xhr.responseText);
      }};
   var data = `{
         "site_id":"MCO"
   }`;
   xhr.send(data);
}

function QUAESTIONES(){
   var url = "https://api.mercadolibre.com/questions/search?seller_id=722296690&sort_fields=item_id,date_created&sort_types=ASC";

var xhr = new XMLHttpRequest();
xhr.open("GET", url);

xhr.setRequestHeader("Access-Control-Allow-Headers", "Content-Type");
xhr.setRequestHeader("Access-Control-Allow-Origin", "https://api.mercadolibre.com/questions/search?seller_id=722296690&api_version=2");
xhr.setRequestHeader("Authorization", "Bearer APP_USR-7186806967479210-030122-2ab02539c7a095c10f8c2159baaa3d1f-722296690");

xhr.onreadystatechange = function () {
   if (xhr.readyState === 4) {
      console.log(xhr.status);
      console.log(xhr.responseText);
   }};

xhr.send();
}

</script>
