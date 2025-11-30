<?php
/**
 *
 * @author Isaac Herrera
 */

require_once '../Modelo/RegistroDAO.php';
session_start()

if (!isset($_SESSION['idUsuario'])) {
    header("Location: ../Vista/login.php");
    exit();
}

$daoRegistro = new RegistroDAO();
$idUsuario = $_SESSION['idUsuario'];

if (isset($_GET['accion']) && $_GET['accion'] == 'inscribir') {
    $idEvento = $_GET['idEvento'];
    
    if ($daoRegistro->yaEstaInscrito($idUsuario, $idEvento)) {
        header("Location: ../Vista/misRegistros.php?error=duplicado");
        exit();
    }

    if ($daoRegistro->inscribir($idUsuario, $idEvento)) {
        header("Location: ../Vista/misRegistros.php?msg=exito");
    } else {
        header("Location: ../Vista/index.php?error=fallo");
    }

    if (isset($_GET['accion']) && $_GET['accion'] == 'cancelar') {
    $idRegistro = $_GET['idRegistro'];
    
    if ($daoRegistro->cancelar($idRegistro)) {
        header("Location: ../Vista/misRegistros.php?msg=cancelado");
    } else {
        header("Location: ../Vista/misRegistros.php?error=fallo");
    }
}

if (isset($_GET['accion']) && $_GET['accion'] == 'validar') {
    $idRegistro = $_GET['idRegistro'];
    $idEvento = $_GET['idEvento'];
    
    if ($daoRegistro->validarAsistencia($idRegistro)) {
        header("Location: ../Vista/verInscritos.php?idEvento=$idEvento&msg=validado");
    } else {
        header("Location: ../Vista/verInscritos.php?idEvento=$idEvento&error=fallo");
    }

}



?>