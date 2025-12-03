<?php

/**
 * @author Isaac Herrera
 */

require_once '../Modelo/Evento.php';
require_once '../Modelo/EventoDAO.php';
require_once '../Modelo/SalonDAO.php';

$eventoDAO = new EventoDAO();
$salonDAO = new SalonDAO();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $accion = $_POST['accion'];

    if ($accion == 'crear_evento') {
        
        $idSalon = (int)$_POST['idSalon'];
        $numParticipantes = 0;
        
        $salonSeleccionado = $salonDAO->obtenerPorId($idSalon);
        
        if ($salonSeleccionado) {
            $numParticipantes = (int)$salonSeleccionado->getMaxCapacidad();
            
        } else {
            header("Location: ../Vista/gestionEventos.php?error=salon_invalido");
            exit();
        }

        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $ponente = $_POST['ponente'];
        $fecha = $_POST['fecha'];
        $horaInicial = $_POST['horaInicio'];
        $horaFin = $_POST['horaFinal'];
        $cupo = $_POST['tipoCupo'];
        $idCat = (int)$_POST['idCategoria'];
        
        $rutaImagen = null;
        $tipoEvento = $_POST['tipoEvento'];

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreOriginal = $_FILES['imagen']['name'];
            $tempPath = $_FILES['imagen']['tmp_name'];
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreUnico = "evento_" . uniqid() . "." . $extension;
            $carpetaDestino = "../Assets/";
            
            if (!is_dir($carpetaDestino)) { mkdir($carpetaDestino, 0777, true); }
            if (move_uploaded_file($tempPath, $carpetaDestino . $nombreUnico)) {
                $rutaImagen = $nombreUnico;
            }
        }

        $evento = new Evento(0, $titulo, $descripcion, $ponente, $numParticipantes, $fecha,
                $horaInicial, $horaFin, $cupo, $tipoEvento, $idCat, $idSalon, $rutaImagen);
                
        if ($tipoEvento == 'conferencia') {
            $tipoConf = $_POST['tipoConferencia'];
            $evento->setTipoConferencia($tipoConf);
        } elseif ($tipoEvento == 'premiacion') {
            $ganador = $_POST['ganadorPremiacion'];
            $evento->setGanadorPremiacion($ganador);
        }
        
        if ($eventoDAO->agregar($evento)) {
            header("Location: ../Vista/gestionEventos.php?msg=creado");
        } else {
            header("Location: ../Vista/gestionEventos.php?error=fallo_creacion");
        }
    }
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
        $idEvento = (int)$_GET['id'];
        if ($eventoDAO->eliminar($idEvento)) {
            header("Location: ../Vista/gestionEventos.php?msg=eliminado");
            exit();
        } else {
            header("Location: ../Vista/gestionEventos.php?error=no_borrado");
            exit();
        }
    }
}
