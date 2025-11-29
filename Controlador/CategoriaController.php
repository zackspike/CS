<?php
require_once '../Modelo/Categoria.php';
require_once '../Modelo/CategoriaDAO.php';

$daoSalon = new CategoriaDAO();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $categoria = new Categoria(0, $nombre, $descripcion);
    
    if ($daoSalon->agregar($categoria)) {
        header("Location: ../Vista/categorias.php?msg=agregado");
    } else {
        header("Location: ../Vista/categorias.php?error=fallo_agregar");
    }
} 

//Modificar categoría
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'actualizar') {
    
    $id = $_POST['idCategoria'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $categoriaActualizada = new Categoria($id, $nombre, $descripcion);
    
    if ($daoSalon->actualizar($categoriaActualizada)) {
        header("Location: ../Vista/categorias.php?msg=actualizado");
    } else {
        header("Location: ../Vista/categorias.php?error=fallo_actualizar");
    }
}
?>