<?php

require_once "./clases/AutentificadorJWT.php";

class MWusuarios
{
	public function VerificarUsuario($request, $response, $next) {
         
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta = "";
		
		//tomo el token del header
		$arrayConToken = $request->getHeader('token');
		$token = $arrayConToken[0];		
		
		$objDelaRespuesta->esValido = true; 
		try 
		{
			AutentificadorJWT::verificarToken($token);
			$objDelaRespuesta->esValido=true;      
		}
		catch (Exception $e) {  

			$objDelaRespuesta->excepcion = $e->getMessage();
			$objDelaRespuesta->esValido = false;     
		}

		if($objDelaRespuesta->esValido)
		{						
				$payload = AutentificadorJWT::ObtenerData($token);
				if($payload->perfil == 'admin')
				{
					$nueva = $next($request, $response);
				}
				else
				{
					/*$arrayRecibido = json_decode($response->getBody());
					if ($arrayRecibido != null) 
					{
						echo "array recibido";
						var_dump($arrayRecibido);	
						die();	
					}
					else
					{*/
						$objDelaRespuesta->respuesta = "Hola";
						$nueva = $response->withJson($objDelaRespuesta, 200);
					//}
				}
		}    
		else
		{
			$objDelaRespuesta->respuesta = "Solo usuarios registrados";
			$objDelaRespuesta->elToken = $token;
			$nueva = $response->withJson($objDelaRespuesta, 201);
			//return $objDelaRespuesta;
		}  	
		return $nueva;	  
	}

	public static function VerificarUsuarioRegistrado($request, $response, $next)
	{
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta = "";
		
		//tomo el token del header
		$arrayConToken = $request->getHeader('token');
		$token = $arrayConToken[0];		
		
		$objDelaRespuesta->esValido = true; 
		try 
		{
			AutentificadorJWT::verificarToken($token);
			$objDelaRespuesta->esValido=true;      
		}
		catch (Exception $e) {  

			$objDelaRespuesta->excepcion = $e->getMessage();
			$objDelaRespuesta->esValido = false;     
		}

		if($objDelaRespuesta->esValido)
		{						
			$nueva = $next($request, $response);
		}    
		else
		{
			$objDelaRespuesta->respuesta = "Solo usuarios registrados";
			$objDelaRespuesta->elToken = $token;
			$nueva = $response->withJson($objDelaRespuesta, 201);
		}  	
		return $nueva;
	}

	public static function VerificarTipoUsuario($request, $response, $next)
	{
		//primera, dejo pasar
		$nueva = $next($request, $response);
		
		//vuelve

		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta = "";
		$arrayConToken = $request->getHeader('token');
		$token = $arrayConToken[0];	
		
		$payload = AutentificadorJWT::ObtenerData($token);
		
		//tomo lo contenido en el body
		$arrayRecibido = json_decode($response->getBody());

		if($payload->perfil == 'admin')
		{
			//$nueva = $next($request, $response);
			$nueva = $response->withJson($arrayRecibido, 200);
		}
		else
		{
			$resultado = array();
			for ($i=0; $i < count($arrayRecibido); $i++) { 
				//selecciono solo las compras que hizo el usuario actual
				if($arrayRecibido[$i]->usuario == $payload->nombre)
				{
					array_push($resultado, $arrayRecibido[$i]);
				}
			}
			$nueva = $response->withJson($resultado, 200);
		}
		
		return $nueva;	  
	}
}