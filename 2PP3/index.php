<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


require './composer/vendor/autoload.php';
require_once './clases/AccesoDatos.php';
require_once './clases/AutentificadorJWT.php';
require_once './middleware/MWusuarios.php';
require_once './middleware/MWdb.php';
require_once './clases/usuario.php';
require_once './clases/compra.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;


$app = new \Slim\App(["settings" => $config]);

$app->group('/usuario', function()
{
  $this->post('/', \Usuario::class . ':altaUsuario');
  $this->get('/', \Usuario::class . ':mostrarUsuarios')->add(\MWusuarios::class . ':VerificarUsuario');
});

$app->post('/login/', function (Request $request, Response $response) {
  $ArrayDeParametros = $request->getParsedBody();
  return Usuario::login($ArrayDeParametros); 
});

$app->group('/compra', function(){
  $this->post('/', \Compra::class . ':realizarCompra');
  $this->get('/', \Compra::class . ':MostrarCompras')->add(\MWusuarios::class . ':VerificarTipoUsuario');
})->add(\MWusuarios::class . ':VerificarUsuarioRegistrado');

$app->add(\MWdb::class . ':GuardarLog');

$app->run();