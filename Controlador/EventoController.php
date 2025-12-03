<?php

/**
 *
 * @author Isaac Herrera
 */

require_once '../Modelo/Evento.php';
require_once '../Modelo/EventoDAO.php';

$eventoDAO = new EventoDAO();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $accion = $_POST['accion'];

    //Crear evento
    if ($accion == 'crear_evento') {
        
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $ponente = $_POST['ponente'];
        $numParticipantes = (int)$_POST['numParticipantes'];
        $fecha = $_POST['fecha'];
        $horaInicial = $_POST['horaInicio'];
        $horaFin = $_POST['horaFinal'];
        $cupo = $_POST['tipoCupo'];
        $idCat = (int)$_POST['idCategoria'];
        $idSalon = (int)$_POST['idSalon'];
        $rutaImagen = null;
        $tipoEvento = $_POST['tipoEvento']; 

        //Validacion de la carga correcta de la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            
            $nombreOriginal = $_FILES['imagen']['name'];
            $tempPath = $_FILES['imagen']['tmp_name'];
            
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreUnico = "evento_" . uniqid() . "." . $extension;
            
            $carpetaDestino = "../Assets/";
            
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }
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
            exit();
        } else {
            header("Location: ../Vista/gestionEventos.php?error=fallo_creacion");  
            exit();
        }
    }
}

else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
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

?>
