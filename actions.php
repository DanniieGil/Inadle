<?php
function ANSWER2($IdQuestion, $TextQuestion)
{
   session_start();
   $token_acceso2 = "Bearer " . $_SESSION["token_acceso"];
   $url = "https://api.mercadolibre.com/answers";
   $curl = curl_init($url);
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_POST, true);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   $headers = array(
      "Access-Control-Allow-Credentials: true",
      "Authorization: $token_acceso2",
      "Content-Type: application/json",
   );
   curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
   $data = <<<DATA
   {
   "question_id": $IdQuestion, 
    "text": "$TextQuestion"
   }
   DATA;
   curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
   //for debug only!
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   $resp = curl_exec($curl);
   curl_close($curl);
}
//----------------- SOLICITUD DEL TOKEN ACCESS 
function Request_Token($CodigoAcceso)
{
   $url = "https://api.mercadolibre.com/oauth/token";
   $curl = curl_init($url);
   curl_setopt($curl, CURLOPT_URL, $url);
   curl_setopt($curl, CURLOPT_POST, true);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   $headers = array("Content-Type: application/json",);
   curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

   $data = <<<DATA
    {"grant_type": "authorization_code",
    "client_id" : "7186806967479210",
    "client_secret" : "M9SHWPfDgwZk3Zb5hBgTvqpL3Z0w6C85" ,
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

   $_SESSION["token_acceso"] = $obj->{'access_token'};
}
?>

<script>
   //----------------- INFORMACION DEL USUARIO LOGUEADO 
   function Getuser() {
      const url = "https://api.mercadolibre.com/users/me";
      var xhr = new XMLHttpRequest();
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      xhr.open("GET", url);
      xhr.setRequestHeader("Authorization", tokenacces);

      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) { //console.log(xhr.status); console.log(xhr.response);
            var result = JSON.parse(xhr.response);
            document.getElementById("user-info").innerHTML = 'Id:' + result.id + ' Nickname: ' + result.nickname + ' - Token: ' + token;
            sessionStorage.setItem("user_id", result.id);
            let user_id = sessionStorage.getItem('user_id');
         }
      };
      xhr.send();
   }

   //----------------- DETALLE DE PRODUCTO INDIVIDUAL
   function ProductDetailed(ItemId) {
      var url = "https://api.mercadolibre.com/items/" + ItemId;
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      var xhr = new XMLHttpRequest();
      xhr.open("GET", url);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.setRequestHeader("Authorization", tokenacces);
      const $cuerpoTabla = document.querySelector("#cuerpoTabla");
      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            //console.log(xhr.status);
            //console.log(xhr.responseText);
            var result = JSON.parse(xhr.responseText);

            const $tr = document.createElement("tr");
            $cuerpoTabla.appendChild($tr);

            let $IdItem = document.createElement("td");
            var settings = {
               "url": "https://api.mercadolibre.com/items/" + result.id,
               "method": "GET",
               "timeout": 0,
            };
            $.ajax(settings).done(function(response) {
               $IdItem.innerHTML = result.id.link(response.permalink);
            });


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

            var btnPause = document.createElement("BUTTON");
            btnPause.innerHTML = "PAUSE";
            btnPause.setAttribute("id", result.id);
            btnPause.setAttribute("onclick", "PAUSED_PUBLICATION(this)");
            btnPause.setAttribute("class", "button__lista_productos__positive");

            var btnActive = document.createElement("BUTTON");
            btnActive.innerHTML = "ACTIVE";
            btnActive.setAttribute("id", result.id);
            btnActive.setAttribute("onclick", "ACTIVE_PUBLICATION(this)");
            btnActive.setAttribute("class", "button__lista_productos__positive");

            var btnInactive = document.createElement("BUTTON");
            btnInactive.innerHTML = "INACTIVE";
            btnInactive.setAttribute("id", result.id);
            btnInactive.setAttribute("onclick", "INACTIVE_PUBLICATION(this)");
            btnInactive.setAttribute("class", "button__lista_productos__positive");

            var btnClosed = document.createElement("BUTTON");
            btnClosed.innerHTML = "CLOSED";
            btnClosed.setAttribute("id", result.id);
            btnClosed.setAttribute("onclick", "CLOSED_PUBLICATION(this)");
            btnClosed.setAttribute("class", "button__lista_productos__negative");

            var btnDeleted = document.createElement("BUTTON");
            btnDeleted.innerHTML = "DELETED";
            btnDeleted.setAttribute("id", result.id);
            btnDeleted.setAttribute("onclick", "DELETED_PUBLICATION(this)");
            btnDeleted.setAttribute("class", "button__lista_productos__negative");

            $Actions.appendChild(btnPause);
            $Actions.appendChild(btnActive);
            $Actions.appendChild(btnInactive);
            $Actions.appendChild(btnClosed);
            $Actions.appendChild(btnDeleted);
            $tr.appendChild($Actions);

            //DATA DE AMAZON.COM
            let $ASIN = document.createElement("td");
            firebase.database().ref('Publicaciones/' + result.id + '/Id_Amazon').on('value', (snap) => {
               var ASINtxt = snap.val();
               var Amazonpage = "https://www.amazon.com/dp/" + ASINtxt;
               //console.log(snap.val());
               $ASIN.innerHTML = ASINtxt.link(Amazonpage);
            });

            let $Precio_AMA = document.createElement("td");
            firebase.database().ref('Publicaciones/' + result.id + '/Precio_AMA').on('value', (snap) => {
               //console.log(snap.val());
               $Precio_AMA.textContent = snap.val();
            });

            let $Precio_AMAconverted = document.createElement("td");
            firebase.database().ref('Publicaciones/' + result.id + '/Precio_AMA').on('value', (snap) => {
               $Precio_AMAconverted.textContent = snap.val() * document.getElementById("exchangeUSDCOP").innerHTML;
            });

            let $Price_Variance = document.createElement("td");
            firebase.database().ref('Publicaciones/' + result.id + '/Precio_AMA').on('value', (snap) => {
               var var_num = result.price - $Precio_AMAconverted.textContent;
               var porc = (result.price * 100) / $Precio_AMAconverted.textContent - 100
               $Price_Variance.textContent = "$ " + var_num + " [" + porc.toFixed(0) + "%]";
            });

            let $Status_DB = document.createElement("td");
            firebase.database().ref('Publicaciones/' + result.id + '/Status').on('value', (snap) => {
               $Status_DB.textContent = snap.val();
            });

            let $UpdatePrice = document.createElement("td");
            var btnPriceAjust = document.createElement("BUTTON");
            btnPriceAjust.innerHTML = "UPDATE";
            firebase.database().ref('Publicaciones/' + result.id + '/Status').on('value', (snap) => {
               btnPriceAjust.setAttribute("id", result.id);
               btnPriceAjust.setAttribute("onclick", "UPDATEPriceProduct(this)");
               btnPriceAjust.setAttribute("class", "button__lista_productos__negative");
               $UpdatePrice.appendChild(btnPriceAjust);
            });

            $tr.appendChild($ASIN);
            $tr.appendChild($Precio_AMA);
            $tr.appendChild($Precio_AMAconverted);
            $tr.appendChild($Price_Variance);
            $tr.appendChild($Status_DB);
            $tr.appendChild($UpdatePrice);

            // FIN DATA AMAZON.COM

            let $Health = document.createElement("td");
            let $Sold = document.createElement("td");
            let $DateCreated = document.createElement("td");
            var settings = {
               "url": "https://api.mercadolibre.com/items/" + result.id,
               "method": "GET",
               "timeout": 0,
            };
            $.ajax(settings).done(function(response) {
               $Health.textContent = response.health * 100 + '%';
               $Sold.textContent = response.sold_quantity;


               var horautc = parseInt(response.date_created.substr(11, 2))
               if (horautc >= 1 && horautc <= 4) {
                  var new_hora = parseInt(response.date_created.substr(11, 2)) + 19;
                  $DateCreated.textContent = response.date_created.substr(0, 10) + " - [" + new_hora + ":" + response.date_created.substr(14, 5) + "]";
               } else {
                  var new_hora = parseInt(response.date_created.substr(11, 2)) - 5;
                  $DateCreated.textContent = response.date_created.substr(0, 10) + " - [" + new_hora + ":" + response.date_created.substr(14, 5) + "]";
               }

            });
            $tr.appendChild($Health);
            $tr.appendChild($Sold);
            $tr.appendChild($DateCreated);








         }
      };
      xhr.send();
   }

   //----------------- LISTA DE TODOS LOS PRODUCTOS
   function GetListProduct() {
      const dbinadle = firebase.database();
      const root_pub = dbinadle.ref('Prices');
      root_pub.on('value', (snapshot) => {
         const data = snapshot.val();
         console.log(data);
         document.getElementById("exchangeUSDCOP").innerHTML = data.USDCOP.toFixed(0);
      });




      let user_id = sessionStorage.getItem('user_id');
      var url = "https://api.mercadolibre.com/users/" + user_id + "/items/search?limit=999";
      var xhr = new XMLHttpRequest();
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      xhr.open("GET", url);
      var result = "";
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.setRequestHeader("Authorization", tokenacces);

      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            //console.log(xhr.status);
            //console.log(xhr.responseText);
            result = JSON.parse(xhr.response);
            for (let i = 0; i < result.results.length; i++) {
               ProductDetailed(result.results[i])
            }
         };
      };

      xhr.send();
   }

   //----------------- PUBLICADOR MANUAL 
   function PublicarOne() {
      var url = "https://api.mercadolibre.com/items";
      var xhr = new XMLHttpRequest();
      xhr.open("POST", url);
      var bearer = "Bearer " + "<?php session_start();
                                 echo $_SESSION['token_acceso']; ?>";
      xhr.setRequestHeader("Authorization", bearer);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            //console.log(xhr.status); 
            //console.log(xhr.responseText);

            // **********************************************************************************************************
            //------------------------------ //ADD DATABASE // --------------*
            // **********************************************************************************************************
            const Id_PublicacionBD = JSON.parse(this.responseText);
            const aux = document.getElementById("Id_Amazon").value.indexOf("/dp/");
            const Id_Amazon = document.getElementById("Id_Amazon").value.substr(aux + 4, 10);
            console.log(Id_Amazon);
            const Precio_CO = document.getElementById("price").value
            const Precio_AMA = document.getElementById("Precio_Amazon").value
            //console.log(Id_PublicacionBD.id + Id_Amazon);
            const dbinadle = firebase.database();
            const root_pub = dbinadle.ref('Publicaciones');
            var daniele = Id_PublicacionBD.id;
            xhr.addEventListener('load', (e) => {
               e.preventDefault();
               root_pub.child(daniele).set({
                  "Id_Amazon": Id_Amazon,
                  "Precio_CO": Precio_CO,
                  "Precio_AMA": Precio_AMA,
                  "Status": "Active"
               });
            })
            // ************************************************************************************************************* 
            // ************************************************************************************************************* 
            // *************************************************************************************************************   
         }
      };

      var categoryID = document.getElementById("category_id").value;



      var settings = {
         "url": "https://api.mercadolibre.com/categories/" + categoryID + "/attributes/",
         "method": "GET",
         "timeout": 0,
      };

      $.ajax(settings).done(function(response) {
         var dinamicE1 = [];
         for (let i = 0; i < response.length; i++) {
            dinamicE1.push({
               'id': document.getElementById("ATTRIBUTE_" + i).innerHTML,
               'value_name': document.getElementById("VALUE_" + i).value
            })
         }


         var data = {
            "title": document.getElementById("title").value,
            "category_id": document.getElementById("category_id").value,
            "price": document.getElementById("price").value,
            "currency_id": document.getElementById("currency_id").value,
            "available_quantity": document.getElementById("available_quantity").value,
            "buying_mode": "buy_it_now",
            "condition": document.getElementById("condition").value,
            "listing_type_id": document.getElementById("listing_type_id").value,
            "description": {
               "plain_text": document.getElementById("description").value
            },
            "video_id": document.getElementById("video_id").value,
            "sale_terms": [{
                  "id": "MANUFACTURING_TIME",
                  "value_name": (document.getElementById("MANUFACTURING_TIME_number").value + "días")
               },
               {
                  "id": "WARRANTY_TYPE",
                  "value_name": document.getElementById("WARRANTY_TYPE").value
               },
               {
                  "id": "WARRANTY_TIME",
                  "value_name": (document.getElementById("WARRANTY_TIME_a").value + " " + document.getElementById("WARRANTY_TIME_b").value)
               }
            ],
            "pictures": [{
                  "source": document.getElementById("foto_1").value
               },
               {
                  "source": document.getElementById("foto_2").value
               },
               {
                  "source": document.getElementById("foto_3").value
               },
               {
                  "source": document.getElementById("foto_4").value
               },
               {
                  "source": document.getElementById("foto_5").value
               },
               {
                  "source": document.getElementById("foto_6").value
               }
            ],
            "attributes": dinamicE1,
            "variations": [{
               "picture_ids": [
                  document.getElementById("foto_1").value,
                  document.getElementById("foto_2").value,
                  document.getElementById("foto_3").value,
                  document.getElementById("foto_4").value,
                  document.getElementById("foto_5").value,
                  document.getElementById("foto_6").value,
               ],
               "available_quantity": document.getElementById("available_quantity").value,
               "price": document.getElementById("price").value,
               "attribute_combinations": [{
                  "id": "COLOR",
                  "value_name": document.getElementById("color").value,
               }]
            }]
         };





         var data2 = JSON.stringify(data);
         console.log(data)
         xhr.send(data2);
      });






   }

   //----------------- PREDICTOR AUTOMATICO DE CATEGORIA
   function Predictor() {

      // LIMPIA INPUT VIEJOS DE PREDICCION
      document.getElementById("category_id").value = "";
      document.getElementById("category_name").value = "";
      document.getElementById("BRAND").value = "";
      document.getElementById("LINE").value = "";
      document.getElementById("MODEL").value = "";


      // LIMPIA TABLA



      var titulo_pub = document.getElementById("title").value;
      var url = "https://api.mercadolibre.com/sites/MCO/domain_discovery/search?limit=1&q=" + titulo_pub;
      var xhrt = new XMLHttpRequest();
      xhrt.open("GET", url);
      xhrt.onreadystatechange = function() {
         if (xhrt.readyState === 4) { //console.log(xhrt.status); console.log(xhrt.response);
            var results = JSON.parse(xhrt.response);
            var categoryID = results['0']['category_id'];
            var categoryNAME = results['0']['category_name'];
            if (categoryID != undefined) {
               document.getElementById("category_id").value = categoryID
            };
            if (categoryNAME != undefined) {
               document.getElementById("category_name").value = categoryNAME
            };

            //******************/ * * * * * * * * * * * * * * * * * * * * * *  /****************************//
            //******************/ A T R  I B U T O S   A D I C I O N A L E S /****************************//
            document.getElementById("attrib_Adds").innerHTML = "ATRIBUTOS ADICIONALES";
            var $body = document.getElementsByTagName("body")[0];
            var $tabla = document.createElement("table");
            var $tblBody = document.createElement("tbody");

            $tabla.setAttribute("id", "Tabla_Attributes");
            $("#Tabla_Attributes").remove();



            var settings = {
               "url": "https://api.mercadolibre.com/categories/" + categoryID + "/attributes/",
               "method": "GET",
               "timeout": 0,
            };
            $.ajax(settings).done(function(response) {

               var hilera2 = document.createElement("tr");

               var celda14 = document.createElement("th");
               var textoCelda14 = document.createTextNode("EN-ATTRIB");
               celda14.appendChild(textoCelda14);

               var celda7 = document.createElement("th");
               var textoCelda7 = document.createTextNode("ES-ATTRIB");
               celda7.appendChild(textoCelda7);

               var celda8 = document.createElement("th");
               var textoCelda8 = document.createTextNode("REQUIRED");
               celda8.appendChild(textoCelda8);

               var celda9 = document.createElement("th");
               var textoCelda9 = document.createTextNode("VALUE");
               celda9.appendChild(textoCelda9);

               var celda10 = document.createElement("th");
               var textoCelda10 = document.createTextNode("VALUE TYPE");
               celda10.appendChild(textoCelda10);

               var celda11 = document.createElement("th");
               var textoCelda11 = document.createTextNode("DEFAULT UNIT");
               celda11.appendChild(textoCelda11);

               var celda12 = document.createElement("th");
               var textoCelda12 = document.createTextNode("ALLOWED UNITS");
               celda12.appendChild(textoCelda12);

               var celda13 = document.createElement("th");
               var textoCelda13 = document.createTextNode("INPUTS");
               celda13.appendChild(textoCelda13);

               hilera2.appendChild(celda14);
               hilera2.appendChild(celda7);
               hilera2.appendChild(celda8);
               hilera2.appendChild(celda9);
               hilera2.appendChild(celda10);
               hilera2.appendChild(celda11);
               hilera2.appendChild(celda12);
               hilera2.appendChild(celda13);

               $tblBody.appendChild(hilera2);
               for (let i = 0; i < response.length; i++) {
                  var hilera = document.createElement("tr");


                  var celda0 = document.createElement("td");
                  var textoCelda0 = document.createTextNode(response[i].id);
                  celda0.setAttribute("id", 'ATTRIBUTE_' + i)


                  var celda1 = document.createElement("td");
                  var textoCelda1 = document.createTextNode(response[i].name);
                  celda1.setAttribute("id", 'MarcaES' + i)

                  var celda2 = document.createElement("td");

                  if (response[i].tags.required != undefined) {
                     var textoCelda2 = document.createTextNode(response[i].tags.required);
                     var celda3 = document.createElement("td");
                  } else {
                     var textoCelda2 = document.createTextNode("-");
                     var celda3 = document.createElement("td");
                  }

                  if (response[i].values != undefined) {
                     var term = response[i].values;
                     for (let j = 0; j < term.length; j++) {
                        var textoCelda3 = document.createTextNode('(' + j + ') ' + response[i].values[j].name + '     ');
                        celda3.appendChild(textoCelda3);
                     }
                  }

                  var celda4 = document.createElement("td");
                  var textoCelda4 = document.createTextNode(response[i].value_type);

                  if (response[i].default_unit != undefined) {
                     var celda5 = document.createElement("td");
                     var textoCelda5 = document.createTextNode(response[i].default_unit);
                  } else {
                     var celda5 = document.createElement("td");
                     var textoCelda5 = document.createTextNode("-");
                  }

                  if (response[i].allowed_units != undefined) {
                     var term = response[i].allowed_units;
                     var celda6 = document.createElement("td");
                     for (let j = 0; j < term.length; j++) {
                        var textoCelda6 = document.createTextNode(response[i].allowed_units[j].name + ' - ');
                        celda6.appendChild(textoCelda6);
                     }
                  } else {
                     var celda6 = document.createElement("td");
                     var textoCelda6 = document.createTextNode(" - ");
                     celda6.appendChild(textoCelda6);
                  }


                  var celda7 = document.createElement("td");
                  var textoCelda7 = document.createElement("input");
                  textoCelda7.setAttribute("id", "VALUE_" + i)
                  celda7.appendChild(textoCelda7);



                  celda0.appendChild(textoCelda0);
                  celda1.appendChild(textoCelda1);
                  celda2.appendChild(textoCelda2);
                  celda4.appendChild(textoCelda4);
                  celda5.appendChild(textoCelda5);

                  hilera.appendChild(celda0);
                  hilera.appendChild(celda1);
                  hilera.appendChild(celda2);
                  hilera.appendChild(celda3);
                  hilera.appendChild(celda4);
                  hilera.appendChild(celda5);
                  hilera.appendChild(celda6);
                  hilera.appendChild(celda7);

                  $tblBody.appendChild(hilera);

                  $tabla.appendChild($tblBody);
                  $body.appendChild($tabla);
                  $tabla.setAttribute("border", "3");



                  if (response[i].name == "Marca") {
                     document.getElementById("VALUE_" + i).value = document.getElementById("BRAND").value
                  } else if (response[i].name == "Línea") {
                     document.getElementById("VALUE_" + i).value = document.getElementById("LINE").value
                  } else if (response[i].name == "Modelo") {
                     document.getElementById("VALUE_" + i).value = document.getElementById("MODEL").value
                  } else if (response[i].name == "Modelo alfanumérico") {
                     document.getElementById("VALUE_" + i).value = document.getElementById("MODEL").value
                  } else if (response[i].name == "Código universal de producto") {
                     document.getElementById("VALUE_" + i).value = document.getElementById("GTIN").value
                  }



               }







            });




            //******************/ * * * * * * * * * * * * * * * * * * * * * *  /****************************//
            //******************/ * * * * * * * * * * * * * * * * * * * * * *  /****************************//













            var Checking = results['0']["attributes"];
            if (Checking == "") {
               document.getElementById("BRAND").value = categoryNAME;
               document.getElementById("MODEL").value = categoryNAME;
               document.getElementById("LINE").value = categoryNAME;
            } else if (Checking.length == 1) {
               var vBRAND = results['0']['attributes'][0]['value_name'];
               document.getElementById("BRAND").value = vBRAND;
               document.getElementById("MODEL").value = vBRAND;
               document.getElementById("LINE").value = vBRAND;
            } else if (Checking.length >= 2) {
               //BRAND
               var vBRAND = results['0']['attributes'][0]['value_name'];
               document.getElementById("BRAND").value = vBRAND;

               //CHECK MODEL
               var proba1 = results['0']['attributes'][1]['id'];
               if (proba1 == "MODEL" || proba1 == "ALPHANUMERIC_MODEL") {
                  var vMODEL = results['0']['attributes'][1]['value_name'];
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
                     var vMODEL = results['0']['attributes'][2]['value_name'];
                     document.getElementById("MODEL").value = vMODEL;
                  } else if (proba2 == "LINE") {
                     var vLINE = results['0']['attributes'][2]['value_name'];
                     document.getElementById("LINE").value = vLINE;
                  } else {
                     console.log("ok");
                  }
               }

            };


         }
      }
      xhrt.send(null);








   }

   //----------------- ACTIONS: Active, Inactive, Pauses, Closed, Deleted.
   function PAUSED_PUBLICATION(ITEM_ID) {
      var url = "https://api.mercadolibre.com/items/" + ITEM_ID.id;
      var xhr = new XMLHttpRequest();
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      xhr.open("PUT", url);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.setRequestHeader("Authorization", tokenacces);
      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            //console.log(xhr.status);
            //console.log(xhr.responseText);

            //PUBLICATION EN FIRE BASE
            var pruebe = JSON.parse(this.responseText);
            console.log(pruebe.status);
            if (pruebe.status == "Active" || pruebe.status == "paused") {
               const dbinadle = firebase.database();
               const root_pub = dbinadle.ref('Publicaciones');
               var daniele = ITEM_ID.id;
               xhr.addEventListener('load', (e) => {
                  e.preventDefault();
                  var StatusUpdate = {
                     "Status": "Paused"
                  }
                  root_pub.child(daniele).update(StatusUpdate)
               })
            }
         }
      };

      var data = `{
   "status":"paused"
   }`;
      xhr.send(data);


   }

   function ACTIVE_PUBLICATION(ITEM_ID) {
      var url = "https://api.mercadolibre.com/items/" + ITEM_ID.id;
      var xhr = new XMLHttpRequest();
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      xhr.open("PUT", url);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.setRequestHeader("Authorization", tokenacces);
      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            //console.log(xhr.status);
            //console.log(xhr.responseText);

            //PUBLICATION EN FIRE BASE
            var pruebe = JSON.parse(this.responseText);
            console.log(pruebe.status);
            if (pruebe.status == "active" || pruebe.status == "pause") {
               const dbinadle = firebase.database();
               const root_pub = dbinadle.ref('Publicaciones');
               var daniele = ITEM_ID.id;
               xhr.addEventListener('load', (e) => {
                  e.preventDefault();
                  var StatusUpdate = {
                     "Status": "Active"
                  }
                  root_pub.child(daniele).update(StatusUpdate)
               })
            }
         }
      };

      var data = `{
   "status":"active"
   }`;
      xhr.send(data);

   }

   function INACTIVE_PUBLICATION(ITEM_ID) {
      var url = "https://api.mercadolibre.com/items/" + ITEM_ID.id;
      var xhr = new XMLHttpRequest();
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      xhr.open("PUT", url);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.setRequestHeader("Authorization", tokenacces);
      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);

            //PUBLICATION EN FIRE BASE
            var pruebe = JSON.parse(this.responseText);
            console.log(pruebe.status);
            if (pruebe.error != "validation_error") {
               const dbinadle = firebase.database();
               const root_pub = dbinadle.ref('Publicaciones');
               var daniele = ITEM_ID.id;
               xhr.addEventListener('load', (e) => {
                  e.preventDefault();
                  var StatusUpdate = {
                     "Status": "Inactive"
                  }
                  root_pub.child(daniele).update(StatusUpdate)
               })
            }



         }
      };

      var data = `{
   "status":"inactive"
   }`;
      xhr.send(data);


   }

   function CLOSED_PUBLICATION(ITEM_ID) {
      var url2 = "https://api.mercadolibre.com/items/" + ITEM_ID.id;
      var xhr2 = new XMLHttpRequest();
      var token2 = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces2 = "Bearer " + token2;
      xhr2.open("PUT", url2);
      xhr2.setRequestHeader("Content-Type", "application/json");
      xhr2.setRequestHeader("Authorization", tokenacces2);
      xhr2.onreadystatechange = function() {
         if (xhr2.readyState === 4) {
            console.log(xhr2.status);
            console.log(xhr2.responseText);

            var pruebe = JSON.parse(this.responseText);
            console.log(pruebe.status);
            if (pruebe.error != "validation_error") {
               const dbinadle = firebase.database();
               const root_pub = dbinadle.ref('Publicaciones');
               var daniele = ITEM_ID.id;
               xhr2.addEventListener('load', (e) => {
                  e.preventDefault();
                  var StatusUpdate = {
                     "Status": "Closed"
                  }
                  root_pub.child(daniele).update(StatusUpdate)
               })
            }


         }
      };

      var data = `{
   "status":"closed"
   }`;
      xhr2.send(data);


   }

   function DELETED_PUBLICATION(ITEM_ID) {
      CLOSED_PUBLICATION(ITEM_ID)
      var url = "https://api.mercadolibre.com/items/" + ITEM_ID.id;
      var xhr = new XMLHttpRequest();
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      xhr.open("PUT", url);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.setRequestHeader("Authorization", tokenacces);
      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);

            //DELETED PUBLICATION EN FIRE BASE
            var pruebe = JSON.parse(this.responseText);
            if (pruebe.error != "validation_error") {

               const dbinadle = firebase.database();
               const root_pub = dbinadle.ref('Publicaciones');
               var daniele = ITEM_ID.id;
               xhr.addEventListener('load', (e) => {
                  e.preventDefault();
                  root_pub.child(daniele).remove()
               })
            }
         }
      };

      var data = `{
   "deleted":"true"
   }`;
      xhr.send(data);


   }

   //----------------- QUESTIONS AND ANSWER
   function TESTUSER() {
      var url = "https://api.mercadolibre.com/users/test_user";
      var xhr = new XMLHttpRequest();
      xhr.open("POST", url);
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      xhr.setRequestHeader("Authorization", tokenacces);
      xhr.setRequestHeader("Content-Type", "application/json");
      xhr.onreadystatechange = function() {
         if (xhr.readyState === 4) {
            console.log(xhr.status);
            console.log(xhr.responseText);
         }
      };
      var data = `{
         "site_id":"MCO"
   }`;
      xhr.send(data);
   }

   function QUAESTIONES() {
      const $elemento = document.querySelector("#TablaQuestions");
      $elemento.innerHTML = "";
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      var filtro = document.getElementById("FILTER_QUESTION").value
      var settings = {
         "url": "https://api.mercadolibre.com/my/received_questions/search?search_type=scan&sort_fields=date_created&sort_types=DESC&status=" + filtro,
         "method": "GET",
         "timeout": 0,
         "headers": {
            "Authorization": tokenacces
         },
      };
      $.ajax(settings).done(function(response) {

         //TABLA DE PREGUNTAS Y RESPUESTAS
         const $cuerpoTabla = document.querySelector("#TablaQuestions");
         response.questions.forEach(element => {
            const $tr = document.createElement("tr");
            $cuerpoTabla.appendChild($tr);

            //Id
            let $IdItem1 = document.createElement("td");
            $IdItem1.textContent = element.id;
            $IdItem1.setAttribute("id", element.id);
            $tr.appendChild($IdItem1);

            //Date Created
            let $IdItem2 = document.createElement("td");

            $IdItem2.textContent = element.date_created;
            $tr.appendChild($IdItem2);

            //Item Id
            let $IdItem3 = document.createElement("td");

            var settings = {
               "url": "https://api.mercadolibre.com/items/" + element.item_id,
               "method": "GET",
               "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
               console.log(response);
               $IdItem3.innerHTML = element.item_id.link(response.permalink);

            });


            $tr.appendChild($IdItem3);

            //Status
            let $IdItem4 = document.createElement("td");
            $IdItem4.textContent = element.status;
            $tr.appendChild($IdItem4);

            //Question
            let $IdItem5 = document.createElement("td");
            $IdItem5.textContent = element.text;
            $tr.appendChild($IdItem5);
            if (element.answer != null) {
               //Answer
               let $IdItem6 = document.createElement("td");
               $IdItem6.textContent = element.answer.text;
               $tr.appendChild($IdItem6);

            } else {
               let $IdItem6 = document.createElement("td");
               $IdItem6.textContent = "";
               $tr.appendChild($IdItem6);

               //ACTIONS FOR QUESTION
               let $Actions = document.createElement("td");
               //var btnResponse = document.createElement("BUTTON"); btnResponse.innerHTML = "RESPONDER"; 
               //btnResponse.setAttribute("id",  element.id); btnResponse.setAttribute("onclick", "ANSWER(this)");
               var btnDelete = document.createElement("BUTTON");
               btnDelete.innerHTML = "ELIMINAR";
               btnDelete.setAttribute("id", element.id);
               btnDelete.setAttribute("onclick", "DELETED(this)");
               //$Actions.appendChild(btnResponse); 
               $Actions.appendChild(btnDelete);
               $tr.appendChild($Actions);
            }
         });
      });
   }

   function DELETED(ID_QUESTION) {
      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;
      var settings = {
         "url": "https://api.mercadolibre.com/questions/" + ID_QUESTION.id,
         "method": "DELETE",
         "timeout": 0,
         "headers": {
            "Authorization": tokenacces
         },
      };
      $.ajax(settings).done(function(response) {
         console.log(response)
         QUAESTIONES();;;
      });
   }


   //AUX EXCHANGE MONEY. LIMIT REQUEST PER HOUR 100
   function Exhangemoney() {
      fetch("https://free.currconv.com/api/v7/convert?q=USD_COP&compact=ultra&apiKey=290d9ab77c5b1705a90f")
         .then(respuesta => respuesta.json())
         .then(respuestaDecodificada => {
            var daniele = respuestaDecodificada.USD_COP;
            document.getElementById("exchangeUSDCOP").innerHTML = daniele;
            const dbinadle = firebase.database();
            const root_pub = dbinadle.ref('Prices');
            Exhangemoneybtn.addEventListener('click', (e) => {
               e.preventDefault();
               root_pub.child('USDCOP').set(daniele);
            })
         });
   }



   //UPDATE PRICE PRODUCT IN MERCADOLIBRE AND AMAZON
   function UPDATEPriceProduct(ITEM_ID) {

      var Precio_AMA;
      var PrecioUSDCOP = document.getElementById("exchangeUSDCOP").innerHTML;
      //firebase.database().ref('Prices/USDCOP/').on('value',(snap)=>{
      // PrecioUSDCOP = snap.val()});

      firebase.database().ref('Publicaciones/' + ITEM_ID.id + '/Precio_AMA').on('value', (snap) => {
         Precio_AMA = snap.val()
      });
      var PrecioAjustado = Math.round(Precio_AMA * PrecioUSDCOP * 1.30);

      var token = ("<?php session_start();
                     echo $_SESSION['token_acceso']; ?>");
      const tokenacces = "Bearer " + token;



      var settings = {
         "url": "https://api.mercadolibre.com/items/" + ITEM_ID.id + "/variations",
         "method": "GET",
         "timeout": 0,
      };

      $.ajax(settings).done(function(response) {

         for (let i = 0; i < response.length; i++) {

            var auxiliary = "https://api.mercadolibre.com/items/" + ITEM_ID.id;
            var settings = {
               "url": auxiliary,
               "method": "PUT",
               "timeout": 0,
               "headers": {
                  "Authorization": tokenacces,
                  "Content-Type": "application/json"
               },
               "data": JSON.stringify({
                  "variations": [{
                     "id": response[i].id,
                     "price": PrecioAjustado


                  }]
               }),
            };

            $.ajax(settings).done(function(response) {
               console.log(response);
            });
         }

      });



   }
</script>


<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.10/firebase-database.js"></script>
<script>
   var firebaseConfig = {
      apiKey: "AIzaSyCx5r4YkrVNTS47_ECxU8iyKsgVCpKkcy4",
      authDomain: "inadle-database.firebaseapp.com",
      databaseURL: "https://inadle-database-default-rtdb.firebaseio.com",
      projectId: "inadle-database",
      storageBucket: "inadle-database.appspot.com",
      messagingSenderId: "505796457555",
      appId: "1:505796457555:web:2479f61a0acee5e8f0c2a6"
   };
   firebase.initializeApp(firebaseConfig);
</script>