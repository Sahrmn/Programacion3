<?php 
include_once "clases/usuario.php";

//var_dump($_POST);

$usuario = new Usuario($_POST["nombre"], $_POST["clave"]);
var_dump($usuario);
$usuario->ToJSON();



/*
GITHUB - > OCTAVIOVILLEGAS

EJERCICIO

alta de usuario con 5 elementos como minimo
login con por lo menos 3 entradas de datos

verificar

mostrar los 5 elementos ingresados en la alta

PARCIAL 1 - PROGRAMACION III
01 DE OCTUBRE

PARCIAL 2 - PROGRAMACION III
12 DE NOVIEMBRE


*/

?>