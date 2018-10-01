<?php 
class Producto
{
    public $nombre;
    public $codBarra;

    public function __construct($nom, $cod)
    {
        $this->nombre = $nom;
        $this->codBarra = $cod;
    }

    public function ToString()
    {
        echo $this->nombre . "-" . $this->codBarra;
    }

    public static function get_file_extension($file_name) {
        return substr(strrchr($file_name,'.'),1);
    }
    
    public static function MoverArchivo($producto, $archivo)
    {
        var_dump($archivo);
        $extension = Producto::get_file_extension($archivo["name"]);
        //echo $extension;
        $file_name = $producto->nombre . $producto->codBarra;
        $destino = "fotos/" . $file_name . "." . $extension;
        //var_dump(pathinfo($archivo, PATHINFO_EXTENSION));
    
        if (file_exists("fotos/") == false) {
            mkdir("Fotos/", 0777);
        }
    
        if(file_exists($destino))
        {
            echo "La imagen ya existe!";
            if (file_exists("Fotos/Backup") == false) {
                mkdir("Fotos/Backup", 0777);
            }
            $nuevoNombre = $file_name . "-" . date("dmy") . "." . $extension;
            //muevo archivo antiguo a backup
            rename($destino, "fotos/backup/" . $nuevoNombre);
        }
    
        if (move_uploaded_file($archivo["tmp_name"], $destino)) {
            echo "<br/>El archivo ". basename($archivo["name"]). " ha sido subido exitosamente.";
        } else {
            echo "<br/>Lamentablemente ocurri&oacute; un error y no se pudo subir el archivo.";
        }
    }

    public function ToJSON()
    {
        $arrayObj = array();
        if(file_exists("./productos.json")== false)
        {
            /*if(file_exists("d://datos//")== false)
            {
                mkdir("d://datos", 0777);
            }*/
            $file = fopen("./productos.json","w");
            $json = json_encode($this);
            fputs($file, $json);
            fclose($file);
        }
        else
        {
            $file = fopen("./productos.json","r");
            while(!feof($file))
            {
                $string = fgets($file, 200); //ERROR COMUN: poner pocas letras para leer (200)
                //echo $string;
                if($string != NULL)
                {
                    $obj = json_decode($string);
                    //var_dump($obj);
                    array_push($arrayObj, $obj);
                    //var_dump($arrayObj);
                    
                }
            }
            //var_dump($arrayObj);
            fclose($file);
            $file = fopen("./productos.json","w");
            array_push($arrayObj, $this);
            foreach ($arrayObj as $key => $value) {
                $json = json_encode($arrayObj[$key]);
                fputs($file, $json);
                fputs($file, "\n");
            }
            fclose($file);
        }
    }

    public static function CrearTabla()
    {
        $arrayObj = array();
        if (file_exists("./productos.json")) {
            $file = fopen("./productos.json","r");
            while(!feof($file))
            {
                $string = fgets($file, 200); 
                //echo $string;
                if($string != NULL)
                {
                    $obj = json_decode($string);
                    //var_dump($obj);
                    array_push($arrayObj, $obj);
                }
            }
            //var_dump($arrayObj);
            fclose($file);
            $tabla = "<table>";
            for ($i=0; $i < count($arrayObj); $i++) { 
                $tabla = $tabla . "<tr><th></th></tr>"
            }
        }
        else
        {
            echo "No existe el archivo";
        }
    }
}

?>