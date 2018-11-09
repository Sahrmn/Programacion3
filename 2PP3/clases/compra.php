<?php 

class Compra
{
	public $articulo;
	public $fecha;
	public $precio;
	public $usuario;

	public function __construct($art = null, $fecha = null, $p = null, $user = null)
	{
		if(func_num_args() != 0)
		{
			$this->articulo = $art;
			$this->fecha = $fecha;
			$this->precio = $p;
			$this->usuario = $user;
		}
	}

	public static function realizarCompra($request, $response)
	{
		$param = $request->getParsedBody();
		if($param['articulo'] != null && $param['fecha'] != null && $param['precio'] != null)
		{
			//busco en el token el nombre del usuario
			$arrayConToken = $request->getHeader('token');
			$token = $arrayConToken[0];
			$payload = AutentificadorJWT::ObtenerData($token);
			$nombre_usuario = $payload->nombre;

			//creo la compra
			$compra = new Compra($param['articulo'], $param['fecha'], $param['precio'], $nombre_usuario);
			$obj = $compra->insertarCompra();

			//guardo imagen
			$archivo = $request->getUploadedFiles();
			$destino = "./IMGCompras/";
			//var_dump($archivo);
			if(file_exists($destino) == false)
			{
				mkdir($destino, 0777);
			}
			if(isset($archivo['imagen']))
			{
				$nombre_imagen = $obj . $param['articulo'];
				$extension = explode(".", $archivo['imagen']->getClientFilename());
				$archivo['imagen']->moveTo($destino . $nombre_imagen . "." . $extension[1]);
				//var_dump($destino . $nombre_imagen . "." . $extension[1]);
			}

			if($obj != null)
			{
				//$respuesta = $response->withJson($obj, 200);
				$respuesta = $response->withJson("Carga realizada");
			}
			else
			{
				throw new Exception("Ocurrio un error", 500);
				
			}
		}
		else
		{
			throw new Exception("Faltan datos");
		}
		return $respuesta;
	}

	public function insertarCompra()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into compras (articulo, fecha, precio, usuario)values('$this->articulo','$this->fecha','$this->precio', '$this->usuario')");
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public static function TraerCompras()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM compras");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "compra");
	}

	public static function MostrarCompras($request, $response)
	{
		$arrayCompras = Compra::TraerCompras();
		$arrayCompras = json_encode($arrayCompras);
		$retorno = $response->getBody()->write($arrayCompras);
		return $retorno;
	}

}


?>