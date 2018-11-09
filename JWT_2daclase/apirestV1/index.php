<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../composer/vendor/autoload.php';
require_once './clases/AccesoDatos.php';
require_once './clases/cd.php';

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

//require_once "saludo.php";


$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("GET => Bienvenido!!! ,a SlimFramework");
    return $response;

});

$app->post('[/]', function (Request $request, Response $response) {   
    $response->getBody()->write("POST => Bienvenido!!! ,a SlimFramework");
    return $response;

});

$app->put('[/]', function (Request $request, Response $response) {  
    $response->getBody()->write("PUT => Bienvenido!!! ,a SlimFramework");
    return $response;

});

$app->delete('[/]', function (Request $request, Response $response) {  
    $response->getBody()->write(" DELETE => Bienvenido!!! ,a SlimFramework");
    return $response;

});



$app->get('/datos/', function (Request $request, Response $response) {     
    $datos = array('nombre' => 'rogelio','apellido' => 'agua', 'edad' => 40);
    $newResponse = $response->withJson($datos, 200);  
    return $newResponse;
});

$app->post('/datos/', function (Request $request, Response $response) {    
    $ArrayDeParametros = $request->getParsedBody();
   // var_dump($ArrayDeParametros);
    $objeto= new stdclass();
    $objeto->nombre=$ArrayDeParametros['nombre'];
    $objeto->apellido=$ArrayDeParametros['apellido'];
    $objeto->edad=$ArrayDeParametros['edad'];
    $newResponse = $response->withJson($objeto, 200);  
    return $newResponse;

});

/* atender todos los verbos de HTTP*/
$app->any('/cualquiera/[{id}]', function ($request, $response, $args) {
    
    var_dump($request->getMethod());
    $id=$args['id'];
    $response->getBody()->write("cualquier verbo de ajax parametro: $id ");
    return $response;
});



/* atender algunos los verbos de HTTP*/
$app->map(['GET', 'POST'], '/mapeado', function ($request, $response, $args) {

      var_dump($request->getMethod());
     $response->getBody()->write("Solo POST y GET");
});


/* agrupacion de ruta*/
$app->group('/saludo', function () {

    $this->get('/{nombre}', function ($request, $response, $args) {
        $nombre=$args['nombre'];
        $response->getBody()->write("HOLA, Bienvenido <h1>$nombre</h1> a la apirest de 'CDs'");
    });

     $this->get('/', function ($request, $response, $args) {
        $response->getBody()->write("HOLA, Bienvenido a la apirest de 'CDs'... ingresá tu nombre");
    });
 
     $this->post('/', function ($request, $response, $args) {      
        $response->getBody()->write("HOLA, Bienvenido a la apirest por post");
    });
     
});


/* agrupacion de ruta y mapeado*/
$app->group('/usuario/{id:[0-9]+}', function () {

    $this->map(['POST', 'DELETE'], '', function ($request, $response, $args) {
        $response->getBody()->write("Borro el usuario por p");
    });

    $this->get('/nombre', function ($request, $response, $args) {
        $response->getBody()->write("Retorno el nombre del usuario del id ");
    });
});



/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/cd', function () {   

$this->get('/', \cd::class . ':traerTodos');
$this->get('/{id}', \cd::class . ':traerUno');
$this->delete('/', \cd::class . ':BorrarUno');
$this->put('/', \cd::class . ':ModificarUno');
//se puede tener funciones definidas
/*SUBIDA DE ARCHIVO*/
$this->post('/', function (Request $request, Response $response) {
  

    $ArrayDeParametros = $request->getParsedBody();
    //var_dump($ArrayDeParametros);

    $archivos = $request->getUploadedFiles();
    
    //VERIFICO SI ES UNA IMAGEN
    $esImagen = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($esImagen == false) {
        echo "El archivo no es una imagen. Verifique!";
        die();
    }
    //VERIFICO EL TAMAÑO MAXIMO QUE PERMITO SUBIR
    if ($_FILES["foto"]["size"] > 700000) 
    {
        echo "El archivo es demasiado grande. Verifique!!!";
        die();
    }


    $extension = substr(strrchr($_FILES["foto"]["name"],'.'),1);
    //$extension  = substr(strrchr($archivos['name'],'.'),1);
    
    $titulo= $ArrayDeParametros['titulo'];    
    $cantante= $ArrayDeParametros['cantante'];
    $año= $ArrayDeParametros['anio'];
    //$foto = $titulo . ".jpg";
    $foto = $titulo . "." . $extension;

    //$fileName = $titulo . $cantante . $año;
    //$archivos['foto']->moveTo($fileName.".jpg");
    
    $micd = new cd();
    $micd->titulo=$titulo;
    $micd->cantante=$cantante;
    $micd->año=$año;
    //$micd->foto = $foto;
    $micd->InsertarElCdParametros();

    //$archivos = $request->getUploadedFiles();
    $destino="./fotos/";

    if (file_exists($destino) == false) {
        mkdir("./fotos/", 0777);
    }

    //$extension= explode(".", $nombreAnterior)  ;
    //$archivos['foto']->moveTo($destino.$titulo.".".$extension[0]);
    
    $foto = str_replace(' ', "", $foto); //quito espacios en blanco

    $archivos['foto']->moveTo($destino . $foto);

    $cds = cd::TraerTodoLosCds();
    $newResponse = $response->withJson($cds, 200);
    //$response->getBody()->write("Alta realizada<br>");

    return $newResponse;

});

     
});











$app->run();