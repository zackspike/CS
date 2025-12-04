<?php
session_start();
require_once '../Modelo/EventoDAO.php';
require_once '../Modelo/EditorialDAO.php';

$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'eventos';
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

$datosParaMostrar = [];
$tituloSeccion = "";

if ($seccion == 'editoriales') {
    $editorialDAO = new EditorialDAO();
    $datosParaMostrar = $editorialDAO->obtenerTodos();
    $tituloSeccion = "Editoriales Participantes";

} else {
    //Obtener los eventos y filtrarlos por tipo
    $eventoDAO = new EventoDAO();
    $todosLosEventos = $eventoDAO->obtenerEventos();
    $tituloSeccion = "Próximos Eventos";
    if ($filtro == 'todos') {
        $datosParaMostrar = $todosLosEventos;
    } else {
        foreach ($todosLosEventos as $ev) {
            if ($ev['tipoEvento'] == $filtro) {
                $datosParaMostrar[] = $ev;
            }
        }
    }
    if($filtro == 'conferencia'){ $tituloSeccion = "Conferencias";}
    if($filtro == 'taller'){ $tituloSeccion = "Talleres";}
    if($filtro == 'premiacion'){ $tituloSeccion = "Premiaciones";}
}
$estaLogueado = isset($_SESSION['idUsuario']);
$nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>FILEY - <?php echo $tituloSeccion; ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../StyleSheets/Inicio.css">
    </head>
    <body>
        <div class="header">
            <div class="header-container">
                <div class="logo-container">
                    <img src="../Assets/logoFILEY.png" alt="Logo FILEY" class="logo">
                </div>
                <div class="nav-menu">
                    <a href="index.php?seccion=eventos&filtro=premiacion" class="nav-link <?php echo $filtro=='premiacion'?'active-link':''; ?>">Premiaciones</a>
                    <a href="index.php?seccion=eventos&filtro=taller" class="nav-link <?php echo $filtro=='taller'?'active-link':''; ?>">Talleres</a>
                    <a href="index.php?seccion=eventos&filtro=conferencia" class="nav-link <?php echo $filtro=='conferencia'?'active-link':''; ?>">Conferencias</a>
                    
                    <!-- Sección editoriales -->
                    <a href="index.php?seccion=editoriales" class="nav-link <?php echo $seccion=='editoriales'?'active-link':''; ?>">Editoriales</a>

                    <?php if ($estaLogueado): ?>
                        <?php if ($rol == 'usuario'): ?>
                            <a href="misRegistros.php" class="nav-link">Mis Registros</a>
                        <?php elseif ($rol == 'admin'): ?>
                            <a href="adminFeed.php" class="nav-link">Panel Admin</a>
                        <?php endif; ?>
                        
                        <span class="usuario-info">Hola, <?php echo $nombreUsuario; ?></span>
                        <a href="../Controlador/UsuarioController.php?accion=logout" class="nav-link btn-logout">Salir</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-border-reveal">Iniciar sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Contenido -->
        <div class="main-container">
            <div class="welcome-section">
                <h1>Bienvenido a la FILEY 2025</h1>
                <p>Tiempo de leer</p>
            </div>
            <h2 class="section-title"><?php echo $tituloSeccion; ?></h2>

            <!-- Eventos -->
            <?php if ($seccion == 'eventos'): ?>
               <div class="filter-bar">
                    <a href="index.php?seccion=eventos&filtro=todos" class="filter-btn <?php echo $filtro=='todos'?'active':''; ?>">Todos</a>
                    <a href="index.php?seccion=eventos&filtro=conferencia" class="filter-btn <?php echo $filtro=='conferencia'?'active':''; ?>">Conferencias</a>
                    <a href="index.php?seccion=eventos&filtro=taller" class="filter-btn <?php echo $filtro=='taller'?'active':''; ?>">Talleres</a>
                    <a href="index.php?seccion=eventos&filtro=premiacion" class="filter-btn <?php echo $filtro=='premiacion'?'active':''; ?>">Premiaciones</a>
                </div>

                <div class="events-grid">
                    <?php if (empty($datosParaMostrar)): ?>
                        <p class="empty-message">No hay eventos disponibles en esta categoría.</p>
                    <?php else: ?>
                        <?php foreach ($datosParaMostrar as $ev): ?>
                            <div class="event-card">
                                <div class="card-img-container">
                                    <?php if (!empty($ev['imagen'])): ?>
                                        <img src="../Assets/<?php echo $ev['imagen']; ?>" class="card-img" alt="Logo">
                                    <?php else: ?>
                                        <div class="card-no-img">Sin Imagen</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-content">
                                    <?php
                                        $tipo = $ev['tipoEvento'];
                                        if ($tipo == 'taller') {
                                            $claseTag = 'tag-taller';
                                        } elseif ($tipo == 'premiacion') {
                                            $claseTag = 'tag-premiacion';
                                        } else {
                                            $claseTag = 'tag-conferencia';
                                        }
                                    ?>
                                    <span class="card-tag <?php echo $claseTag; ?>"><?php echo ucfirst($tipo); ?></span>

                                    <h3 class="card-title"><?php echo $ev['titulo']; ?></h3>
                                    <div class="card-info"><?php echo date('d/m/Y', strtotime($ev['fecha'])); ?></div>
                                    <div class="card-info"> <?php echo $ev['nombreSalon']; ?></div>
                                    <p class="card-desc"><?php echo substr($ev['descripcion'], 0, 90) . '...'; ?></p>

                                    <!-- Botón -->
                                    <?php if ($estaLogueado && $rol == 'usuario'): ?>
                                        <a href="confirmarRegistro.php?id=<?php echo $ev['idEvento']; ?>" class="card-btn">Inscribirme</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            <!-- Editoriales-->
            <?php elseif ($seccion == 'editoriales'): ?>
                <div class="events-grid">
                    <?php if (empty($datosParaMostrar)): ?>
                        <p class="empty-message">Aún no hay editoriales confirmadas.</p>
                    <?php else: ?>
                        <?php foreach ($datosParaMostrar as $ed): ?>
                            <div class="event-card editorial-style">
                                <div class="card-content" style="text-align: center;">
                                    <h3 class="card-title" style="font-size:1.5rem;"><?php echo $ed->getNombreEditorial(); ?></h3>
                                    <div class="card-info" style="justify-content: center; font-size:1.1rem;">
                                        <?php echo $ed->getUbicacionPuestoEditorial(); ?>
                                    </div>
                                    <span class="card-tag tag-conferencia" style="margin-top: 15px; align-self: center; background-color:#005288; padding: 6px 15px; font-size: 0.9rem;">
                                        Puesto #<?php echo $ed->getNumPuestoEditorial(); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            <?php endif; ?>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-content">
                <div class="logo-container">
                        <img src="../Assets/uadyLOGO.png" alt="Logo UADY" class="logo">
                </div>
                <div>
                    <h3>Universidad Autónoma de Yucatán</h3>
                </div>
            </div>
        </div>
    </body>
</html>
