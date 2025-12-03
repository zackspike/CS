<?php
session_start();
require_once '../Modelo/RegistroDAO.php';

if (!isset($_SESSION['idUsuario'])) { header("Location: login.php"); exit(); }

$idRegistro = $_GET['idRegistro'];
$daoRegistro = new RegistroDAO();
$datos = $daoRegistro->obtenerPorId($idRegistro);
if (!$datos) {die("Error: Constancia no encontrada."); }
if ($datos['asistio'] != 1){ die("Error: Asistencia no validada por el administrador.");}
if ($datos['tipoEvento'] != 'taller') {die("Error: Este evento no emite constancia.");}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Constancia - <?php echo $datos['titulo']; ?></title>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../StyleSheets/constancias.css">
</head>
<body>
    <button onclick="window.print()" class="btn-imprimir">️ Descargar PDF / Imprimir</button>
    <div class="constancia-hoja">
        <img src="../Assets/logoFILEY.png" alt="Logo FILEY" class="logo-top">
        <p class="texto-otorga">La Feria Internacional de la Lectura Yucatán (FILEY) otorga la presente</p>
        <h1>CONSTANCIA</h1>
        <p class="texto-otorga">a:</p>
        <div class="nombre-participante">
            <?php echo $datos['nombreUsuario']; ?>
        </div>
        <p class="cuerpo-texto">
            Por su valiosa participación y asistencia al taller: <br><br>
            <span class="evento-nombre">"<?php echo $datos['titulo']; ?>"</span>
        </p>
        
        <div class="fecha-emision">
            Mérida, Yucatán a <?php echo date("d", strtotime($datos['fecha'])); ?> de
            <?php
                $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                echo $meses[date("n", strtotime($datos['fecha'])) - 1];
            ?>
            del <?php echo date("Y", strtotime($datos['fecha'])); ?>.
        </div>
        
        <div class="firmas-container">
            <div class="firma-box">
                <b>Firma del Rector</b><br>UADY
            </div>
            <div class="firma-box">
                <b>Firma del Director</b><br>FILEY
            </div>
        </div>
    </div>

</body>
</html>
