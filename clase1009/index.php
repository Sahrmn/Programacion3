<?php 

include_once "producto.php";

$producto = new Producto($_POST["nombre"], $_POST["codigo"]);

$producto->ToString();
echo "<br>";

    //recibir imagen por parametro
    //cambiar nombre de imagen con nombre-codBarras
    //crear carpeta fotos
    //guardar imagen en esa carpeta

    //2do
    //subir imagen
    //si la imagen existe
    //guardar la imagen existente en una carpeta "backups" con el mismo nombre mas la fecha actual del backup
    //y guardar la imagen subida 

    //funciones para parcial: 
    //mover archivo

    //cargar los datos en un archivo json (nombre, codigo, archivo)
    //crear una tabla en html con los archivos guardados en el json

$archivo = $_FILES["file"];
Producto::MoverArchivo($producto, $archivo);
$producto->ToJson();

?>