<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../composer/vendor/autoload.php';
require '/clases/AccesoDatos.php';
require '/clases/cdApi.php';
require '/clases/MWparaCORS.php';
require '/clases/MWparaAutentificar.php';
require '/clases/usuario.php';
require '/clases/AutentificadorJWT.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/*
¡La primera línea es la más importante! A su vez en el modo de 
desarrollo para obtener información sobre los errores
 (sin él, Slim por lo menos registrar los errores por lo que si está utilizando
  el construido en PHP webserver, entonces usted verá en la salida de la consola 
  que es útil).

  La segunda línea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera más predecible.
*/

$app = new \Slim\App(["settings" => $config]);

$app->post('/crearToken/', function (Request $request, Response $response) {
  $ArrayDeParametros = $request->getParsedBody();
  return Usuario::verificarCrearToken($ArrayDeParametros);
});

/*
  EJEMPLOS DE MIDDLEWARE

 https://github.com/oscarotero/psr7-middlewares

 "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NDA4NjExNzEsImV4cCI6MTU0MDg2MTIzMSwiYXVkIjoiODM1ZDA5MzJlOTgxMzdiNTEzMDQ4ZDBiMTU0MTM2YzU5MjAzODgxMyIsImRhdGEiOnsidXN1YXJpbyI6InNhbWFudGhhIiwicGVyZmlsIjoiYWRtaW5pc3RyYWRvciIsImNsYXZlIjoic2FtYW50aGExMjMifSwiYXBwIjoiQVBJIFJFU1QgQ0QgMjAxNyJ9.QAEbPuM7HGPWdBO0iaUlF3Vyh08sjcCfTRYnpRFl-rg"

1)alta usuario
2)login
3)mostrar verificando el jwt

usar token que tenga vencimiento


 */


/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/cd', function () {
 
  $this->get('/', \cdApi::class . ':traerTodos')->add(\MWparaCORS::class . ':HabilitarCORSTodos');
 
  $this->get('/{id}', \cdApi::class . ':traerUno')->add(\MWparaCORS::class . ':HabilitarCORSTodos');

  $this->post('/', \cdApi::class . ':CargarUno');

  $this->delete('/', \cdApi::class . ':BorrarUno');

  $this->put('/', \cdApi::class . ':ModificarUno');
     
//})->add(\MWparaAutentificar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORS8080');
})->add(\MWparaAutentificar::class . ':VerificarUsuario')->add(\MWparaCORS::class . ':HabilitarCORS8080');

$app->run();