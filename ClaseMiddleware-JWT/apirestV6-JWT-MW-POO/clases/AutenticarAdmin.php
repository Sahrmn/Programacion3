<?php 

class AutenticarAdmin
{
    public static function VerificarAdmin($request, $response, $next) 
    {
        $respuesta = new stdclass();
        $respuesta->respuesta = "";
        $arrayHeader = $request->getHeaders();
        $token = $arrayHeader["HTTP_TOKEN"][0];
        //var_dump($arrayHeader["HTTP_TOKEN"][0]);
        
        if($request->isPost())
        {
            $payload = AutentificadorJWT::ObtenerData($token);
            var_dump($payload);
            if($payload->perfil == 'administrador')
            {
                $response = $next($request, $response);
            }
            else
            {
                $respuesta->respuesta = "Solo administradores";
            }
        }
        if ($respuesta->respuesta != "") {
            $resp = $response->withJSON($respuesta, 401);
            return $resp;
        }

        return $response;
    }
}

?>