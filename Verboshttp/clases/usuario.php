<?php 

class Usuario
{
    public $nombre;
    public $clave;

    public function __construct($nom, $pass)
    {
        $this->nombre = utf8_encode($nom);
        $this->clave = utf8_encode($pass);
    }

    public function ToString()
    {
        return $this->nombre;
    }

    public function ToJSON()
    {
        $arrayObj = array();
        if(file_exists("d://datos//datos.json")== false)
        {
            if(file_exists("d://datos//")== false)
            {
                mkdir("d://datos", 0777);
            }
            $file = fopen("d://datos//datos.json","w");
            $json = json_encode($this);
            fputs($file, $json);
            fclose($file);
        }
        else
        {
            //verificar renglones vacios y si esta vacio el archivo
            $file = fopen("d://datos//datos.json","r");
            //$file = trim($file); // quito los espacios en blanco
            //$string = fread($file);
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
            $file = fopen("d://datos//datos.json","w");
            array_push($arrayObj, $this);
            foreach ($arrayObj as $key => $value) {
                $json = json_encode($arrayObj[$key]);
                //var_dump($json);
                fputs($file, $json);
                fputs($file, "\n");
            }
            fclose($file);
        }
    }

}

?>