<?php

 //comprobar si se recibió una solicitud POST

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   // Ejecutar un git reset --hard para quitar el repo local por el nuevo.
   system('cd ticketsmallorca/api/');
   system('git reset --hard');

  //Luego va un git pull

  system('git pull');

  // Mensaje confirmando que ha funcionado

  echo "Actualización exitosa";

} else {

  // Si no se recibe una solicitud POST, muestra mensaje de error

  http_response_code(400);

 echo "Error: Se esperaba una solicitud POST";

}

?>