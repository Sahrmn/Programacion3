<?php
/* EJERCICIO

alta de usuario con 5 elementos como minimo
login con por lo menos 3 entradas de datos

verificar

mostrar los 5 elementos ingresados en la alta
*/
include_once "clases/usuario2.php";

//$usuario = new usuario($_POST["nombre"], $_POST["apellido"], $_POST["sexo"], $_POST["usuario"], $_POST["clave"]);
//$usuario->ToJson();
usuario::Login($_POST["usuario"], $_POST["clave"], $_POST["sexo"]);
?>