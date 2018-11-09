<?php 

class Usuario
{
    public $perfil;
    public $nombreUsuario;
    public $clave;

    public function __construct($perfil = null, $nombreUsuario = null, $clave = null)
    {
        if(func_num_args() != 0)
        {
            $this->perfil = utf8_encode($perfil);
            $this->nombreUsuario = utf8_encode($nombreUsuario);
            $this->clave = utf8_encode($clave);
        }
    }

    public function verificarUsuario()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("select usuario, perfil, pass from usuarios where usuario = :nombreUsuario AND pass = :clave");
        $consulta->bindValue(':nombreUsuario', $this->nombreUsuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->execute();			
        if($consulta->fetchObject('usuario') != NULL)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function verificarCrearToken($ArrayDeParametros) 
    {
        $datos = array('usuario' => $ArrayDeParametros['usuario'], 'perfil' => $ArrayDeParametros['perfil'], 'clave' => $ArrayDeParametros['clave']);
        //verificar en bd
        $user = new Usuario($datos['perfil'], $datos['usuario'], $datos['clave']);
        $datos = array('perfil' => $datos['perfil'], 'usuario' => $datos['usuario']); 
        if($user->VerificarUsuario() == false)
        {
          throw new Exception("El usuario no existe.");
        }
        else
        {  
          $token= AutentificadorJWT::CrearToken($datos); 
          $newResponse = json_encode($token, 200); 
          return $newResponse;
        }
    }
}


?>