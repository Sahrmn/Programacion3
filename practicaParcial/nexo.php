<?php
ini_set('error_reporting', E_ALL);

include_once './clases/alumno.php';
include_once './clases/materia.php';
include_once './clases/inscripcion.php';
/*
var_dump($_POST);
var_dump($_FILES);
$caso = $_POST;
var_dump($caso);
$foto = $_FILES;
var_dump($foto);

/*$alumno = new Alumno($caso['nombre'], $caso['apellido'], $caso['email'], $foto);
var_dump($alumno);*/

//1)
//Alumno::cargarAlumno($caso, $foto);

//2)
/*$caso = $_GET['caso'];
var_dump($caso);
var_dump(Alumno::consultarAlumno($caso));*/

//3)
/*$caso = $_POST;
var_dump($_POST);
Materia::cargarMateria($caso);*/

//4)
/*$caso = $_GET;
//var_dump($caso);
Inscripcion::inscribirAlumno($caso);*/

//5)
/*
$caso = $_GET;
echo Inscripcion::inscripciones();
*/

//6)
/*
$caso = $_GET['caso'];
echo Inscripcion::inscripciones($caso);
*/

//7)
/*
$caso = $_POST;
$file = $_FILES;
Alumno::modificarAlumno($caso, $file);
*/

//8)
Alumno::alumnos();

?>