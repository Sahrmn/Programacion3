<?php 
class Materia
{
    public $nombre;
    public $codigo;
    public $cupo;
    public $aula;

    public function __construct($nom, $cod, $capacidad, $au)
    {
        $this->nombre = utf8_encode($nom);
        $this->codigo = utf8_encode($cod);
        $this->cupo = utf8_encode($capacidad);
        $this->aula = utf8_encode($au);
    }

    public static function cargarMateria($post)
    {
        if(Materia::verificarIdentificador($post['codigo'])== false)
        {
            $materia = new Materia($post["nombre"], $post["codigo"], $post["cupo"], $post["aula"]);
            echo "CREO OBJETO <BR>";
            //var_dump($materia);
            //echo $post["nombre"];
            Materia::ToJSON($materia);
        }
        else
        {
            echo "<br>El identificador ya existe";
        }
    }

    public static function ToJSON($materia)
    {
        $archivo = "materias.json";
        $arrayObj = array();
        if(file_exists($archivo)== false)
        {
            $file = fopen($archivo,"w");
            $json = json_encode($materia);
            fputs($file, $json);
            fclose($file);
        }
        else
        {
            $file = fopen($archivo,"r");
            while(!feof($file))
            {
                $string = fgets($file);
                //echo $string;
                if($string != NULL)
                {
                    $obj = json_decode($string);
                        //var_dump($obj);
                    array_push($arrayObj, $obj);
                        //var_dump($json);
                }
            }
            //var_dump($arrayObj);
            fclose($file);
            $file = fopen($archivo,"w");
            array_push($arrayObj, $materia);
            foreach ($arrayObj as $key => $value) {
                $json = json_encode($arrayObj[$key]);
                //var_dump($json);
                fputs($file, $json);
                fputs($file, "\n");
            }
            fclose($file);
            echo "JSON creado con exito!";
        }
    }

    public static function verificarIdentificador($id)
    {
        $archivo = "materias.json";
        $arrayObj = array();
        if(file_exists($archivo) == true)
        {
            $flag = false;
            $file = fopen($archivo,"r");
            while(!feof($file))
            {
                $string = fgets($file);
                //echo $string;
                $obj = json_decode($string, true);//true para que devuelva un array
                array_push($arrayObj, $obj);
            }
            fclose($file);
            //var_dump($arrayObj);
            for ($i=0; $i < count($arrayObj); $i++) { 
                if ($arrayObj[$i]['codigo'] == $id) {
                    $flag = true;
                    break;
                }
            }

            if ($flag == false) {
                //echo 'El identificador no existe.';
                return false;
            }
            else{
                //echo 'El identificador ya existe!';
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public static function RetornoMateria()
    {
        
    }


}



?>