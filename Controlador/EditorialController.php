<?php
require_once '../Modelo/Editorial.php';
require_once '../Modelo/EditorialDAO.php';

$daoEditoriales = new EditorialDAO();

//Añadir editorial
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    
    $nombreEditorial = $_POST['nombreEditorial'];
    $numPuestoE= $_POST['numPuestoEditorial'];
    $ubicacionPuestoE=$_POST['ubicacionPuestoEditorial'];

    $editorial = new Editorial(0, $nombreEditorial, $numPuestoE, $ubicacionPuestoE);
    
    if ($daoEditoriales->agregar($editorial)) {
        header("Location: ../Vista/editoriales.php?msg=agregado");
    } else {
        header("Location: ../Vista/editoriales.php?error=fallo_agregar");
    }
} 
//Modificar editorial
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    
    $id = $_POST['idEditorial'];
    $nombreEditorialNuevo = $_POST['nombreEditorial'];
    $numPuestoENuevo= $_POST['numPuestoEditorial'];
    $ubicacionPuestoENuevo=$_POST['ubicacionPuestoEditorial'];

    $editorialActualizada = new Editorial($id, $nombreEditorialNuevo, $numPuestoENuevo, $ubicacionPuestoENuevo);
    
    if ($daoEditoriales->actualizar($editorialActualizada)) {
        header("Location: ../Vista/editoriales.php?msg=actualizado");
    } else {
        header("Location: ../Vista/editoriales.php?error=fallo_actualizar");
    }
}
//Eliminar editoriales
else if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    
    $idEditorial = $_GET['id'];
    
    if ($daoEditorial->eliminar($idEditorial)) {
        header("Location: ../Vista/editoriales.php?msg=eliminado");
    } else {
        header("Location: ../Vista/editoriales.php?error=no_borrado");
    }
}