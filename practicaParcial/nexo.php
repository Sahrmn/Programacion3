<?php
include_once './clases/alumno.php';
include_once './clases/materia.php';
include_once './clases/inscripcion.php';
/*
var_dump($_POST);
var_dump($_FILES);

var_dump($caso);

var_dump($foto);

/*$alumno = new Alumno($caso['nombre'], $caso['apellido'], $caso['email'], $foto);
var_dump($alumno);*/

//1)
/* Se deben guardar los siguientes datos: nombre, apellido, email y foto. Los
datos se guardan en el archivo de texto alumnos.txt, tomando el email como identificador.
$caso = $_POST;
$foto = $_FILES;
Alumno::cargarAlumno($caso, $foto);*/

//2)
/* Se ingresa apellido, si coincide con algún registro del archivo alumno.txt se
retorna todos los alumnos con dicho apellido, si no coincide se debe retornar “No existe alumno con apellido
xxx” (xxx es el apellido que se busco) La búsqueda tiene que ser case insensitive.
$caso = $_GET['caso'];
//var_dump($caso);
var_dump(Alumno::consultarAlumno("apellido", $caso));*/

//3)
/* Se recibe el nombre de la materia, código de materia, el cupo de alumnos y
el aula donde se dicta y se guardan los datos en el archivo materias.txt, tomando como identificador el código de
la materia.
$caso = $_POST;
//var_dump($_POST);
Materia::cargarMateria($caso);*/

//4)
/* Se recibe nombre, apellido, mail del alumno, materia y código de la materia
y se guarda en el archivo inscripciones.txt restando un cupo a la materia en el archivo materias.txt. Si no hay
cupo o la materia no existe informar cada caso particular.
$caso = $_GET;
//var_dump($caso);
Inscripcion::inscribirAlumno($caso);*/

//5)
/* Se devuelve un tabla con todos los alumnos inscriptos a todas las materias.
$caso = $_GET;
echo Inscripcion::inscripciones();
*/

//6)
/* Puede recibir el parámetro materia o apellido y filtra la tabla de acuerdo al
parámetro pasado.
$caso = $_GET['caso'];
echo Inscripcion::inscripciones($caso);
*/  

//7)
/* Debe poder modificar todos los datos del alumno menos el email y
guardar la foto antigua en la carpeta /backUpFotos , el nombre será el apellido y la fecha.
$caso = $_POST;
$file = $_FILES;
Alumno::modificarAlumno($caso, $file);
*/

//8)
//Mostrar una tabla con todos los datos de los alumnos, incluida la foto
Alumno::alumnos();

?>