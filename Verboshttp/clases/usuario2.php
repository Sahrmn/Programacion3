<?php 
class Usuario
{
    public $nombre;
    public $apellido;
    public $sexo;
    public $nombreUsuario;
    public $clave;

    public function __construct($nombre, $apellido, $sexo, $nombreUsuario, $clave)
    {
        $this->nombre = utf8_encode($nombre);
        $this->apellido = utf8_encode($apellido);
        $this->sexo = utf8_encode($sexo);
        $this->nombreUsuario = utf8_encode($nombreUsuario);
        $this->clave = utf8_encode($clave);
        //$this->ToJSON();
    }

    public function ToJSON()
    {
        $arrayObj = array();
        if(file_exists("d://datos//usuarios.json")== false) //NOTA: usar rutas relativas
        {
            if(file_exists("d://datos//")== false)
            {
                mkdir("d://datos", 0777);
            }
            $file = fopen("d://datos//usuarios.json","w");
            $json = json_encode($this);
            fputs($file, $json);
            fclose($file);
        }
        else
        {
            $file = fopen("d://datos//usuarios.json","r");
            while(!feof($file))
            {
                $string = fgets($file);
                //echo $string;
                if($string != NULL)
                {
                    $obj = json_decode($string);
                    var_dump($obj);
                    array_push($arrayObj, $obj);
                    //var_dump($arrayObj);
                    
                }
            }
            var_dump($arrayObj);
            fclose($file);
            $file = fopen("d://datos//usuarios.json","w");
            array_push($arrayObj, $this);
            foreach ($arrayObj as $key => $value) {
                $json = json_encode($arrayObj[$key]);
                fputs($file, $json);
                fputs($file, "\n");
            }
            fclose($file);
        }
    }

    public static function Mostrar($usuario)
    {
        $retorno = "Nombre: " . $usuario->nombre . "<br>Apellido: " . $usuario->apellido . "<br>Sexo: " . $usuario->sexo;
        $retorno = $retorno . "<br>Nombre de Usuario:" . $usuario->nombreUsuario . "<br>Clave: " . $usuario->clave;
        return $retorno;
    }

    public static function Login($nombreUsuario, $clave, $sexo)
    {
        $arrayObj = array();
        $file = fopen("d://datos//usuarios.json","r");
            while(!feof($file))
            {
                $string = fgets($file);
                if($string != NULL)
                {
                    $obj = json_decode($string);
                    array_push($arrayObj, $obj);
                }
            }
            //var_dump($arrayObj);
            fclose($file);
            $existe = false;
            //var_dump($arrayObj);
            foreach ($arrayObj as $key => $value) {
                //echo "mostrar<br>";
                //var_dump($arrayObj[$key]);
                $user = new Usuario($arrayObj[$key]->nombre, $arrayObj[$key]->apellido, $arrayObj[$key]->sexo, $arrayObj[$key]->nombreUsuario, $arrayObj[$key]->clave);
                if ($user->nombreUsuario == $nombreUsuario && $user->clave == $clave && $user->sexo == $sexo) {
                    echo "Datos correctos!<br>";
                    echo "Muestro usuario:<br>";
                    echo Usuario::Mostrar($user);
                    $existe = true;
                }
            }
            if ($existe == false) {
                echo "No existe el usuario";
            }
    }
}

?>