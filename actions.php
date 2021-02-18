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
    "client_id" : "7773261594916645",
    "client_secret" : "nKMwXSOECQaj1xN73fFb3XWcKSYM2Otd" ,
    "code" : $CodigoAcceso,
    "redirect_uri" : "http://localhost:8000/"} 
    DATA;
    
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($curl);
    curl_close($curl);
    $obj = json_decode($resp);
    //var_dump($obj);
    
    $_SESSION["token_acceso"] = $obj -> {'access_token'};
}?>


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
          document.getElementById("demo").innerHTML = 'Id:' + result.id + ' Nikname: ' + result.nickname;
         console.log(result)
         }};
    xhr.send();
}

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
   xhr.send(data2);
}

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
            if (proba1 == "MODEL") {
               var vMODEL =  results['0']['attributes'][1]['value_name'];
               document.getElementById("MODEL").value = vMODEL;
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
   xhrt.send(null);
}



   </script>
