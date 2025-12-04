<?php
require_once '../Modelo/Salon.php';
require_once '../Modelo/SalonDAO.php';

$daoSalon = new SalonDAO();

//Añadir salón
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    
    $nombreSalon = $_POST['nombreSalon'];
    $maxCapacidad = $_POST['maxCapacidad'];

    $salon = new Salon(0, $nombreSalon, $maxCapacidad);
    
    if ($daoSalon->agregar($salon)) {
        header("Location: ../Vista/salones.php?msg=agregado");
    } else {
        header("Location: ../Vista/salones.php?error=fallo_agregar");
    }
}
//Modificar salón
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    
    $id = $_POST['idSalon'];
    $nombreSalon = $_POST['nombreSalon'];
    $maxCapacidad = $_POST['maxCapacidad'];

    $salonActualizado = new Salon($id, $nombreSalon, $maxCapacidad);
    
    if ($daoSalon->actualizar($salonActualizado)) {
        header("Location: ../Vista/salones.php?msg=actualizado");
    } else {
        header("Location: ../Vista/salones.php?error=fallo_actualizar");
    }
}

//Eliminar salón 
elseif (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    
    $idSalon = $_GET['id'];
    
    if ($daoSalon->eliminar($idSalon)) {
        header("Location: ../Vista/salones.php?msg=eliminado");
    } else {
        header("Location: ../Vista/salones.php?error=no_borrado");
    }
}
