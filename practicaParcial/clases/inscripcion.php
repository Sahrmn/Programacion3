<?php 
include_once('materia.php');
include_once('alumno.php');

class Inscripcion
{
    public $nombre_al;
    public $apellido_al;
    public $mail_al;
    public $materia;
    public $codigo_materia;

    public function __construct($al, $ap, $email, $mat, $cod)
    {
        $this->nombre_al = utf8_encode($al);
        $this->apellido_al = utf8_encode($ap);
        $this->mail_al = utf8_encode($email);
        $this->materia = utf8_encode($mat);
        $this->codigo_materia = utf8_encode($cod);
    }

    public static function inscribirAlumno($get)
    {
        //var_dump($get);
        if (Materia::verificarIdentificador($get['codigo'])) 
        {
            if (Alumno::verificarIdentificador($get['email']))
            {
                if(Inscripcion::RestarCupo($get['codigo'])== true)
                {
                    $inscripcion = new Inscripcion($get['alumno'], $get['apellido'], $get['email'], $get['materia'], $get['codigo']);
                    Inscripcion::ToJSON($inscripcion);
                }
            }
            else
            {
                echo "El alumno no existe";
            }
        }
        else
        {
            echo "La materia no existe";
        }
    }

    public static function RestarCupo($codigo)
    {
        $archivo = "materias.json";
        $arrayObj = inscripcion::LeerArchivo($archivo);
        if(file_exists($archivo))
        {
            $flag = false;
            //var_dump($arrayObj);
            for ($i=0; $i < count($arrayObj); $i++) { 
                if ($arrayObj[$i]['codigo'] == $codigo) {
                    if($arrayObj[$i]['cupo'] >= 1)
                    {
                        $flag = true;
                        $arrayObj[$i]['cupo'] = $arrayObj[$i]['cupo'] - 1;
                        break;
                    }
                    else
                    {
                        echo "No hay cupo disponible";
                        return false;
                    }
                }
            }

            if($flag)
            {
                //reescribo el archivo materias.json con el cambio de cupo 
                $file = fopen($archivo,"w");
                foreach ($arrayObj as $key => $value) {
                    $json = json_encode($arrayObj[$key]);
                    //var_dump($json);
                    fputs($file, $json);
                    fputs($file, "\n");
                }
                fclose($file);
                echo "JSON modificado con exito!";
                return true;
            }
            else{
                echo 'La materia no existe';
                return false;
            }
        }
        else
        {
            echo 'La materia no existe';
            return false;
        }
    }

    public static function ToJSON($objeto)
    {
        $archivo = "inscripciones.json";
        $arrayObj = array();
        if(file_exists($archivo)== false)
        {
            $file = fopen($archivo,"w");
            $json = json_encode($objeto);
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
            array_push($arrayObj, $objeto);
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


    public static function inscripciones()
    {
        $archivo = "inscripciones.json";
        $inscripciones = Inscripcion::LeerArchivo($archivo);

        //verifico cantidad de argumentos
        if(func_num_args() == 1)
        {
            $arg = func_get_arg(0);//puede ser materia o apellido
            $tipoArg = "";
            foreach ($inscripciones as $key => $value) {
                if($inscripciones[$key]['materia'] == $arg)
                {
                    $tipoArg = "materia";
                    break;
                }
            }
            if($tipoArg == "")
            {
                foreach ($inscripciones as $key => $value) {
                    if($inscripciones[$key]['apellido_al'] == $arg)
                    {
                        $tipoArg = "apellido";
                        break;
                    }
                }
            }

            $arrayResultante = array();
            if ($tipoArg == "apellido") 
            {
                for ($i=0; $i < count($inscripciones); $i++) { 
                    if($inscripciones[$i]['apellido_al'] == $arg)
                    {
                        $al = Alumno::consultarAlumno("apellido", $arg);
                        if($al != NULL)
                        $arrayResultante = array_merge($arrayResultante, $al);
                    }
                }
            }
            else if($tipoArg == "materia")
            {
                for ($i=0; $i < count($inscripciones); $i++) { 
                    if($inscripciones[$i]['materia'] == $arg)
                    {
                        $al = Alumno::consultarAlumno("apellido", $inscripciones[$i]['apellido_al']);
                        if($al != NULL)
                            $arrayResultante = array_merge($arrayResultante, $al);
                    }
                }
            }
            else
            {
                echo "El argumento " . $arg . " no existe";
                return false;
            }
            //var_dump($arrayResultante);
            return Alumno::devolverTabla($arrayResultante);
        }
        else
        {
            //muestro todos los alumnos inscriptos

            $alu = Inscripcion::alumnosInscriptos();
            if($alu != NULL)
                return Alumno::devolverTabla($alu);
        }
    }

    public static function alumnosInscriptos()
    {
        $archivo = "inscripciones.json";
        $inscripciones = Inscripcion::LeerArchivo($archivo);

        //recupero id alumnos
        $arrayId = array();
        for ($i=0; $i < count($inscripciones); $i++) { 
            array_push($arrayId, $inscripciones[$i]['mail_al']);
        }

        //busco alumnos con esas ids
        $archivo = "alumnos.json";
        $alumnos = array();
        if(file_exists($archivo))
        {
            $file = fopen($archivo,"r");
            while(!feof($file))
            {
                $string = fgets($file);
                if($string != NULL)
                {
                    $obj = json_decode($string, true);//true para que devuelva un array
                    
                    foreach ($arrayId as $key => $value) {
                        if($arrayId[$key] == $obj['email'])
                            array_push($alumnos, $obj);
                    }                        
                }
            }
            fclose($file);

            return $alumnos;
        }
        else
        {
            echo "No hay alumnos";
        }
    }

    public static function LeerArchivo($archivo)
    {
        $retorno = array();
        if(file_exists($archivo))
        {
            $file = fopen($archivo,"r");
            while(!feof($file))
            {
                $string = fgets($file);
                if($string != NULL)
                {
                    $obj = json_decode($string, true);//true para que devuelva un array
                    array_push($retorno, $obj);
                }
            }
            fclose($file);
            return $retorno;
        }
        else
        {
            echo "El archivo no existe";
        }
    }


}



?>