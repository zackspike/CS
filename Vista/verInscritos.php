<?php
session_start();
require_once '../Modelo/RegistroDAO.php';
require_once '../Modelo/EventoDAO.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

$idEvento = isset($_GET['idEvento']) ? $_GET['idEvento'] : null;

if (!is_numeric($idEvento)) {
    echo "ID de evento inválido.";
    exit();
}

$registroDAO = new RegistroDAO();
$inscritos = $registroDAO->obtenerPorEvento($idEvento);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Lista de Asistencia</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../StyleSheets/Inicio.css">
    <link rel="stylesheet" href="../StyleSheets/Iinscripciones.css">
</head>
<body>

    <div class="header">
        <div class="header-container">
             <div class="logo-container"><img src="../Assets/logoFILEY.png" class="logo" alt="Logo"></div>
             <div class="nav-menu">
                <a href="gestionEventos.php" class="nav-link">Volver a Eventos</a>
                <span class="usuario-info">Administrador: <?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
                
            </div>
        </div>
    </div>

    <div class="container">
        <div class="table-container">
            <h2 style="color:#005288;">Lista de Asistencia</h2>
            <p style="color:#666; margin-bottom:20px;">Validación de participantes para el evento ID: #<?php echo htmlspecialchars($idEvento); ?></p>

            <?php if (empty($inscritos)): ?>
                <p style="text-align:center; padding:30px; color:#888;">Nadie se ha inscrito a este evento aún.</p>
            <?php else: ?>
                <table class="tabla-bonita">
                    <thead>
                        <tr>
                            <th>Participante</th>
                            <th>Correo</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($inscritos as $p): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($p['nombre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($p['email']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($p['fechaRegistro'])); ?></td>
                            <td>
                                <?php if ($p['asistio'] == 1): ?>
                                    <span class="badge-si">Asistió</span>
                                <?php else: ?>
                                    <span class="badge-no">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['asistio'] == 0): ?>
                                    <a href="../Controlador/RegistroController.php?accion=validar&idRegistro=<?php echo $p['idRegistro']; ?>&idEvento=<?php echo htmlspecialchars($idEvento); ?>"
                                       class="btn-validar">
                                       Validar
                                    </a>
                                <?php else: ?>
                                    <span style="color:#aaa; font-size:0.9rem;">Validado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
