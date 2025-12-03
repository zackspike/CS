<?php
session_start();
require_once '../Modelo/RegistroDAO.php';

if (!isset($_SESSION['idUsuario'])) { header("Location: login.php"); exit(); }

$daoRegistro = new RegistroDAO();
$misRegistros = $daoRegistro->obtenerPorUsuario($_SESSION['idUsuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Mis Registros</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../StyleSheets/Inicio.css">
    <link rel="stylesheet" href="../StyleSheets/misRegistros.css">
</head>
<body>

    <div class="header">
        <div class="header-container">
            <div class="logo-container"><a href="inicio.php"><img src="../Assets/logoFILEY.png" class="logo" alt="logo"></a></div>
            <div class="nav-menu">
                <a href="index.php" class="nav-link">Volver a Inicio</a>
                <span class="usuario-info"><?php echo htmlspecialchars($_SESSION['nombre']); ?></span>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="table-container">
            <h2 style="color:#005288; margin-bottom:20px; border-bottom: 2px solid #eee; padding-bottom:10px;">Mis Eventos Inscritos</h2>
            
            <?php if (empty($misRegistros)): ?>
                <div style="text-align:center; padding:40px;">
                    <p style="color:#666; font-size:1.1rem;">No te has inscrito a ningún evento todavía.</p>
                    <a href="index.php" class="btn-constancia">Ver eventos</a>
                </div>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="tabla-bonita">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Fecha</th>
                                <th>Salón</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($misRegistros as $registro): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($registro->getTituloEvento()); ?></strong></td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($registro->getFechaEvento())); ?><br>
                                    <small><?php echo substr($registro->getHoraInicio(), 0, 5); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($registro->getNombreSalon()); ?></td>
                                <td>
                                    <?php if ($registro->getAsistio() == 1): ?>
                                        <span class="badge-asistio">Asistió</span>
                                    <?php else: ?>
                                        <span class="badge-pendiente">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($registro->getAsistio() == 1): ?>
                                        <?php if ($registro->getTipoEvento() == 'taller'): ?>
                                    <a href="verConstancia.php?idRegistro=<?php echo $registro->getIdRegistro(); ?>" target="_blank" class="btn-constancia">
                                                Constancia
                                            </a>
                                        <?php else: ?>
                                            <span style="color:#999; font-size:0.8rem;">Completado</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <button type="button" class="btn-qr btn-qr-accion"
                                            data-id="<?php echo $registro->getIdUsuario(); ?>"
                                            data-titulo="<?php echo htmlspecialchars($registro->getTituloEvento()); ?>"
                                            data-nombre="<?php echo htmlspecialchars($_SESSION['nombre']); ?>">
                                            Ver Pase
                                        </button>

                                        <a href="../Controlador/RegistroController.php?accion=cancelar&idRegistro=<?php echo $registro->getIdRegistro(); ?>" class="btn-cancelar" onclick="return confirm('¿Seguro que deseas cancelar tu asistencia?');">
                                            Cancelar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="qrModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" onclick="cerrarModal()">&times;</span>
            <h3 style="color:#005288; margin-top:0;">Pase de Acceso</h3>
            <p id="modalEvento" style="font-weight:bold; color:#333; margin:10px 0; font-size:1.1rem;"></p>
            
            <div style="display:flex; justify-content:center; margin:20px 0;">
                <img id="imgQR" src="" alt="Cargando QR..." style="width:180px; height:180px; border:1px solid #ddd; padding:10px; background:white;">
            </div>
            
            <div class="qr-info">
                Participante: <strong><span id="modalNombre"></span></strong><br>
                <small style="display:block; margin-top:10px; color:#888;">Muestra este código al entrar</small>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const botones = document.querySelectorAll('.btn-qr-accion');
            botones.forEach(boton => {
                boton.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const titulo = this.getAttribute('data-titulo');
                    const nombre = this.getAttribute('data-nombre');
                    mostrarQR(id, titulo, nombre);
                });
            });
        });

        function mostrarQR(idUsuario, eventoTitulo, nombreUsuario) {
            var modal = document.getElementById("qrModal");
            var imagenQR = document.getElementById("imgQR");
            
            if(!modal || !imagenQR) return;
            var qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" + encodeURIComponent(idUsuario);
            
            imagenQR.src = qrUrl;

            document.getElementById("modalEvento").innerText = eventoTitulo;
            document.getElementById("modalNombre").innerText = nombreUsuario;
            modal.style.display = "flex";
        }

        function cerrarModal() {
            document.getElementById("qrModal").style.display = "none";
        }

        window.onclick = function(event) {
            var modal = document.getElementById("qrModal");
            if (event.target == modal) {
                cerrarModal();
            }
        };
    </script>
</body>
</html>
