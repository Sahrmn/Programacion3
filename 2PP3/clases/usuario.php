<?php 
class Usuario
{
	public $nombre;
	public $clave;
	public $sexo;
	public $perfil;

	public static function altaUsuario($array)
	{
		if($array['nombre'] != null && $array['clave'] != null && $array['sexo'] != null)
		{
			$user = new Usuario();
			$user->nombre = $array['nombre'];
			$user->clave = $array['clave'];
			$user->sexo = $array['sexo'];
			$user->perfil = "usuario";
			if($user->insertarUsuario() != null)
			{
				$response = new stdclass();
				$response->respuesta = "Alta realizada";
				$response = json_encode($response);
			}
			else
			{
				throw new Exception("Error insertando usuario");
				
			}
		}
		else 
		{
			throw new Exception("Parametros incorrectos");
		}
		return $response;
	}

	public function insertarUsuario()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (nombre, clave, sexo, perfil)values('$this->nombre','$this->clave','$this->sexo','$this->perfil')");
		$consulta->execute();
		return $objetoAccesoDato->RetornarUltimoIdInsertado();
	}

	public static function login($array)
	{
		if($array['nombre'] != null && $array['clave'] != null && $array['sexo'] != null)
		{
			$flag = false;
			$arrayUsuarios = Usuario::traerTodoUsuarios();
			for ($i=0; $i < count($arrayUsuarios); $i++) { 
				if($arrayUsuarios[$i]->clave == $array['clave'] && $arrayUsuarios[$i]->nombre == $array['nombre'] && $arrayUsuarios[$i]->sexo == $array['sexo'])
				{
					$flag = true;
					$datos = new Usuario();
					$datos->nombre = $arrayUsuarios[$i]->nombre;
					$datos->sexo = $arrayUsuarios[$i]->sexo;
					$datos->perfil = $arrayUsuarios[$i]->perfil;
					//var_dump($datos);
				}
			}
			if($flag)
			{
				//genero token
				$token = AutentificadorJWT::CrearToken($datos);
			}
			else
			{
				throw new Exception("Datos incorrectos", 401);
				
			}
		}
		else
		{
			throw new Exception("Parametros incorrectos");
			
		}
		return $token;
	}

	public static function traerTodoUsuarios()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");
	}

	public static function traerUsuarios()
	{	
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("SELECT nombre, sexo, perfil FROM usuarios");
		$consulta->execute();
		return $consulta->fetchAll(PDO::FETCH_CLASS, "usuario");
	}

	public static function mostrarUsuarios($request, $response, $args)
	{
		$users = Usuario::traerUsuarios();
		if ($users) 
		{
			$respuesta = $response->withJson($users, 200);
		}
		else
		{
			$resp = new stdclass();
			$resp->error = "Error al traer los usuarios";
			$respuesta = $response->withJson($resp, 500);
		}
		return $respuesta;
	}


}

?>