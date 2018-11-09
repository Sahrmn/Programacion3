<?php 
require_once "./clases/AutentificadorJWT.php";

class MWdb
{
	public static function InsertarLog($user, $method, $rute, $hour)
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into log (usuario, metodo, ruta, hora)values(:user, :method, :rute, :hour)");
		$consulta->bindValue(':user', $user, PDO::PARAM_STR);
		$consulta->bindValue(':method', $method, PDO::PARAM_STR);
		$consulta->bindValue(':rute', $rute, PDO::PARAM_STR);
		$consulta->bindValue(':hour', $hour, PDO::PARAM_STR);
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public static function GuardarLog($request, $response, $next)
	{
		$arrayConToken = $request->getHeader('token');
		if($arrayConToken != null)
		{
			$token = $arrayConToken[0];		
			$payload = AutentificadorJWT::ObtenerData($token);
			$user = $payload->nombre;
		}
		else
		{
			//guardar log con usuario desconocido
			$user = "Desconocido";
		}
		$method = $request->getMethod();
		$rute = $request->getUri()->getPath();
		$hour = date('Y-m-d-H:i:s');
		if(MWdb::InsertarLog($user, $method, $rute, $hour) != null)
		{
			//$resp->log = "log creado";
			//$respuesta = $response->withJson($resp, 200);
			$respuesta = $next($request, $response);
		}
		else
		{
			throw new Exception("Ocurrio un error", 500);
			
		}
		return $respuesta;
	}



}


?>