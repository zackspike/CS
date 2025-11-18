<?php

$hostname="localhost";
$username="root";
$password="";
$database="filey_cs";


$mysqli= new mysqli($hostname, $username, $password, $database);
if(!$mysqli){
    echo "No se pudo realizar la conexión PHP -MySQL";
}
else{
    echo "La conexión se ha realizado";
    $mysqli->close();
}
