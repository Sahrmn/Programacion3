<?php 
ini_set('error_reporting', E_ALL);

class Alumno
{
    public $nombre;
    public $apellido;
    public $email;
    public $foto;

    public function __construct($nom, $ap, $mail, $photo)
    {
        $this->nombre = utf8_encode($nom);
        $this->apellido = utf8_encode($ap);
        $this->email = utf8_encode($mail);
        $nom = $this->nombre . $this->apellido;
        $this->foto = utf8_encode(Alumno::MoverArchivo($nom, $photo));
    }

    public static function cargarAlumno($post, $foto)
    {
        $archivo = "alumnos.json";
        //verificar que no se repita identificador (email) 
        if (Alumno::verificarIdentificador($post["email"])== false) 
        {    
            $alumno = new Alumno($post['nombre'], $post['apellido'], $post['email'], $foto);
            $arrayObj = array();
            if(file_exists($archivo)== false)
            {
                $file = fopen($archivo,"w");
                $json = json_encode($alumno);
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
                var_dump($arrayObj);
                fclose($file);
                $file = fopen($archivo,"w");
                array_push($arrayObj, $alumno);
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
        else
        {
            echo 'El identificador ya existe!';
        }
    }

    public static function verificarIdentificador($id)
    {
        //echo "DENTRO DE INDENTIFICADOR <BR>";
        $archivo = "alumnos.json";
        $arrayObj = Alumno::LeerArchivo($archivo);
        if(file_exists($archivo) == true)
        {
            $flag = false;
            //var_dump($arrayObj);
            for ($i=0; $i < count($arrayObj); $i++) { 
                if ($arrayObj[$i]['email'] == $id) {
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

    //Devuelve la extension del archivo sin el punto
    public static function get_file_extension($file_name) {
        return substr(strrchr($file_name,'.'),1);
    }
    
    //nom: nombre nuevo de archivo
    //archivo: $_FILES
    public static function MoverArchivo($nom, $archivo)
    {
        //var_dump($archivo);
        //var_dump($nom);
        $extension = Alumno::get_file_extension($archivo["foto"]["name"]);
        $file_name = preg_replace('[\s+]','',$nom); //quito espacios en blanco
        $destino = "fotos/" . $file_name . "." . $extension;
    
        if (file_exists("fotos/") == false) {
            mkdir("fotos/", 0777);
        }
        
        if(file_exists($destino))
        {
            echo "La imagen ya existe!";
            if (file_exists("fotos/backUpFotos") == false) {
                mkdir("fotos/backUpFotos", 0777);
            }
            $nuevoNombre = $file_name . "-" . date("dmy") . "." . $extension;
            //muevo archivo antiguo a backup
            rename($destino, "fotos/backUpFotos/" . $nuevoNombre);
        }
        
        if($archivo["foto"]["tmp_name"] == NULL)
        {
            echo "Ocurrio un problema con el archivo temporal.";
        }
        if (move_uploaded_file($archivo["foto"]["tmp_name"], $destino)) {
            echo "<br/>El archivo ". basename($archivo["foto"]["name"]). " ha sido subido exitosamente.<br/>";
        } else {
            echo "<br/>Lamentablemente ocurri&oacute; un error y no se pudo subir el archivo.<br/>";
        }

        return $file_name . "." . $extension;
    }
    

    //nombre parametro: id, nombre, apellido
    //parametro: valor buscado
    public static function consultarAlumno($nombreParametro, $parametro)
    {
        $archivo = "alumnos.json";
        $arrayObj = Alumno::LeerArchivo($archivo);
        $arrayResultante = array(); 
        if(file_exists($archivo) == true)
        {
            $flag = false;
            for ($i=0; $i < count($arrayObj); $i++) { 
                //strcasecmp es case INsensitive
                if (strcasecmp($arrayObj[$i][$nombreParametro], $parametro) == 0) {
                    $obj = $arrayObj[$i];
                    //var_dump($obj);
                    array_push($arrayResultante, $obj);
                    //var_dump($arrayResultante);
                    $flag = true;
                    if($nombreParametro == "email")
                        break;
                }
            }
            if ($flag) {
                //var_dump($arrayResultante);
                return $arrayResultante;
            }
            else
            {
                echo "No existe alumno con " . $nombreParametro . " " . $parametro;
            }
        }
        else
        {
            echo "No hay alumnos cargados";
        }
    }

    
    public static function devolverTabla($alumnos)
    {
        $tabla = "<table><tr><th>Nombre</th><th>Apellido</th><th>Email</th></tr>";
        //var_dump($alumnos);
        foreach ($alumnos as $key => $value) {
            $tabla = $tabla . "<tr><td>" . $alumnos[$key]['nombre'] . "</td><td>" . $alumnos[$key]['apellido'] . "</td><td>" . $alumnos[$key]['email'] . "</td></tr>";
        }
        $tabla = $tabla . "</table>";
        return $tabla;
    }

    //devuelve todo el contenido del archivo
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

    public static function modificarAlumno($post, $foto)
    {
        var_dump($foto);
        $archivo = "alumnos.json";
        $arrayAlumnos = Alumno::LeerArchivo($archivo);
        
        for ($i=0; $i < count($arrayAlumnos); $i++) { 
            if($arrayAlumnos[$i]['email'] == $post['email'])
            {
                $arrayAlumnos[$i]['nombre'] = $post['nombre'];
                $arrayAlumnos[$i]['apellido'] = $post['apellido'];
                $arrayAlumnos[$i]['foto'] = Alumno::MoverArchivo($arrayAlumnos[$i]['apellido'], $foto);
                break;
            }
        }

        $file = fopen($archivo,"w");
        foreach ($arrayAlumnos as $key => $value) {
            $json = json_encode($arrayAlumnos[$key]);
            //var_dump($json);
            fputs($file, $json);
            fputs($file, "\n");
        }
        fclose($file);
    }

    public static function devolverTablaCompleta($alumnos)
    {
        $tabla = "<table><tr><th>Nombre</th><th>Apellido</th><th>Email</th><th>Foto</th></tr>";
        //var_dump($alumnos);
        foreach ($alumnos as $key => $value) {
            $tabla = $tabla . "<tr><td>" . $alumnos[$key]['nombre'] . "</td><td>" . $alumnos[$key]['apellido'] . "</td><td>" . $alumnos[$key]['email'] . "</td><td><img src='fotos/" . $alumnos[$key]['foto'] . "' width='100px'></td></tr>";
        }
        $tabla = $tabla . "</table>";
        return $tabla;
    }

    public static function alumnos()
    {
        $alumnos = Alumno::LeerArchivo("alumnos.json");
        echo Alumno::devolverTablaCompleta($alumnos);
    }


}




?>