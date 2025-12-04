<?php
session_start();
require_once '../Modelo/CategoriaDAO.php';
require_once '../Modelo/SalonDAO.php';
require_once '../Modelo/EventoDAO.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

$catDAO = new CategoriaDAO();
$listaCategorias = $catDAO->obtenerTodos();

$salonDAO = new SalonDAO();
$listaSalones = $salonDAO->obtenerTodos();

$eventoDAO = new EventoDAO();
$listaEventos = $eventoDAO->obtenerEventos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestionar Eventos</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../StyleSheets/Inicio.css">
    <link rel="stylesheet" href="../StyleSheets/eventos.css">
</head>
<body>
    <div class="header">
        <div class="header-container">
             <div class="logo-container"><img src="../Assets/logoFILEY.png" class="logo" alt="logo"></div>
             <div class="nav-menu">
                <a href="adminFeed.php" class="nav-link">Regresar</a>
                <span class="usuario-info">Administrador: <?php echo $_SESSION['nombre']; ?></span>
            </div>
        </div>
    </div>

    <div class="form-container">
        <h2 class="section-title">Registrar Nuevo Evento</h2>
        
        <?php if(isset($_GET['msg']) && $_GET['msg']=='creado'): ?>
            <div class="alert success">¡Evento creado exitosamente!</div>
        <?php endif; ?>
        
        <?php if(isset($_GET['msg']) && $_GET['msg']=='eliminado'): ?>
            <div class="alert success">¡Evento eliminado correctamente!</div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="alert error">Ocurrió un error en la operación.</div>
        <?php endif; ?>
        
        <form action="../Controlador/EventoController.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="accion" value="crear_evento">
            
            <div class="form-row">
                <div class="form-col">
                    <label for="titulo">Título del Evento:</label>
                    <input type="text" id="titulo" name="titulo" required placeholder="Titulo">
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="3" required placeholder="Acerca del evento"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="ponente">Ponente:</label>
                    <input type="text" id="ponente" name="ponente" required placeholder="Nombre del conferencista">
                </div>
                <div class="form-col">
                    <label for="idCategoria">Categoría:</label>
                    <select id="idCategoria" name="idCategoria" required>
                        <option value="">Selecciona una Categoría</option>
                        <?php foreach($listaCategorias as $categoria): ?>
                            <option value="<?php echo $categoria->getIdCategoria(); ?>">
                                <?php echo $categoria->getNombre(); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>
                <div class="form-col">
                    <label for="horaInicio">Hora Inicio:</label>
                    <input type="time" id="horaInicio" name="horaInicio" required>
                </div>
                <div class="form-col">
                    <label for="horaFinal">Hora Final:</label>
                    <input type="time" id="horaFinal" name="horaFinal" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="idSalon">Salón Asignado:</label>
                    <select id="idSalon" name="idSalon" required>
                        <option value="">Selecciona un Salón</option>
                        <?php foreach($listaSalones as $salon): ?>
                            <option value="<?php echo $salon->getIdSalon(); ?>">
                                <?php echo $salon->getNombreSalon() . " (Capacidad: " . $salon->getMaxCapacidad() . ")"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color:#666; font-size:0.8rem; margin-top:5px; display:block;">
                        * El cupo del evento se asignará automáticamente según la capacidad del salón.
                    </small>
                </div>
                
                <div class="form-col">
                    <label for="tipoCupo">Tipo de Cupo:</label>
                    <select id="tipoCupo" name="tipoCupo">
                        <option value="Limitado">Requiere registro</option>
                        <option value="Abierto">Entrada libre</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="imagen">Imagen:</label>
                    <input type="file" id="imagen" name="imagen" accept="image/*">
                </div>
            </div>

            <h3 class="section-title" style="margin-top: 30px;">Detalles Específicos</h3>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="selectorTipo">¿Qué tipo de evento es?</label>
                    <select id="selectorTipo" name="tipoEvento" onchange="mostrarCamposEspecificos()" required>
                        <option value="">Selecciona el Tipo</option>
                        <option value="conferencia">Conferencia</option>
                        <option value="taller">Taller</option>
                        <option value="premiacion">Premiación</option>
                    </select>
                </div>
            </div>
            
            <div id="campos-conferencia" class="dynamic-section">
                <h4>Detalles de Conferencia</h4>
                <label for="tipoConferencia">Tipo de Conferencia:</label>
                <input type="text" id="tipoConferencia" name="tipoConferencia" placeholder="Presentación de libro, divulgación, etc...">
            </div>

            <div id="campos-premiacion" class="dynamic-section">
                <h4>Detalles de Premiación</h4>
                <label for="ganadorPremiacion">Nombre del Ganador (Opcional):</label>
                <input type="text" id="ganadorPremiacion" name="ganadorPremiacion" placeholder="Nombre del ganador (N/A si no se conoce)">
            </div>

            <div id="campos-taller" class="dynamic-section">
                <h4>Detalles del Taller</h4>
                <p style="color: #555;">Los talleres requieren confirmación de asistencia por el instructor.</p>
            </div>

            <button type="submit" class="btn" style="background-color: #005288; color: white; width: 100%; margin-top: 20px; padding: 15px; font-size: 1.1rem;">
                Crear Evento
            </button>
        </form>
    </div>

    <div class="card">
        <h2 style="color:#005288; margin-bottom:20px;">Lista de Eventos</h2>
        <div style="overflow-x: auto;">
            <table class="tabla-bonita">
                <thead>
                    <tr>
                        <th style="width: 10%;">Imagen</th>
                        <th style="width: 20%;">Título</th>
                        <th style="width: 10%;">Tipo</th>
                        <th style="width: 15%;">Fecha / Hora</th>
                        <th style="width: 15%;">Lugar</th>
                        <th style="width: 15%;">Aforo / Cupo</th>
                        <th style="width: 15%; text-align: center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listaEventos)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #888;">
                                No hay eventos registrados todavía.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($listaEventos as $ev):
                            $capacidadTotal = isset($ev['maxCapacidad']) ? (int)$ev['maxCapacidad'] : 0;
                            $inscritos = isset($ev['totalInscritos']) ? (int)$ev['totalInscritos'] : 0;
                            
                            $disponibles = $capacidadTotal - $inscritos;
                            
                            $colorEstado = "#28a745";
                            $textoEstado = $disponibles . " disponibles";

                            if ($capacidadTotal > 0) {
                                if ($disponibles <= 0) {
                                    $colorEstado = "#dc3545";
                                    $textoEstado = "¡LLENO!";
                                    $disponibles = 0;
                                } elseif ($disponibles < ($capacidadTotal * 0.2)) {
                                    $colorEstado = "#ffc107";
                                }
                            }
                        ?>
                    <tr>
                        <td>
                            <?php if(!empty($ev['imagen'])): ?>
                            <img src="../Assets/<?php echo $ev['imagen']; ?>" class="img-mini" alt="img del evento">
                            <?php else: ?>
                                <span style="color:#ccc; font-size:0.8rem;">Sin img</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong style="font-size:1.05rem;"><?php echo $ev['titulo']; ?></strong><br>
                            <span style="color:#666; font-size:0.9rem;"><?php echo $ev['ponente']; ?></span>
                        </td>
                        <td>
                            <span style="font-weight:bold; color:#555; background:#eee; padding:4px 8px; border-radius:4px; font-size:0.85rem;">
                            <?php echo ucfirst($ev['tipoEvento']); ?>
                        </span>
                        </td>
                        <td>
                            <?php echo date('d/m/Y', strtotime($ev['fecha'])); ?><br>
                            <small style="color:#666;">
                            <?php echo substr($ev['horaInicio'],0,5) . ' - ' . substr($ev['horaFinal'],0,5); ?>
                            </small>
                        </td>
                        <td><?php echo $ev['nombreSalon']; ?></td>
                        
                        <td style="font-size: 0.9rem;">
                            <div>
                                <strong><?php echo $inscritos; ?> / <?php echo $capacidadTotal; ?></strong> ocupados
                            </div>
                            <div style="margin-top:4px;">
                                <span style="color: <?php echo $colorEstado; ?>; font-weight:bold;">
                                    <?php echo $textoEstado; ?>
                                </span>
                            </div>
                            <div style="background:#eee; height:6px; width:100%; border-radius:3px; margin-top:5px; overflow:hidden;">
                                <?php
                                        $porcentaje = ($capacidadTotal > 0) ? ($inscritos / $capacidadTotal) * 100 : 0;
                                        if($porcentaje > 100) $porcentaje = 100;
                                ?>
                                <div style="background:<?php echo $colorEstado; ?>; height:100%; width:<?php echo $porcentaje; ?>%;"></div>
                            </div>
                        </td>

                        <td style="text-align: center;">
                            <a href="verInscritos.php?idEvento=<?php echo $ev['idEvento']; ?>" class="btn-constancia">Inscritos</a>
                            <a href="../Controlador/EventoController.php?accion=eliminar&id=<?php echo $ev['idEvento']; ?>"
                                class="btn-rojo"
                                onclick="return confirm('¿Estás seguro de eliminar este evento?');">
                                Borrar
                            </a>
                        </td>
                    </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function mostrarCamposEspecificos() {
            var tipo = document.getElementById("selectorTipo").value;
            document.getElementById("campos-conferencia").style.display = "none";
            document.getElementById("campos-premiacion").style.display = "none";
            document.getElementById("campos-taller").style.display = "none";

            if (tipo === "conferencia") {
                document.getElementById("campos-conferencia").style.display = "block";
            } else if (tipo === "premiacion") {
                document.getElementById("campos-premiacion").style.display = "block";
            } else if (tipo === "taller") {
                document.getElementById("campos-taller").style.display = "block";
            }
        }
    </script>
</body>
</html>
