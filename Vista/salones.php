<?php
session_start();
require_once '../Modelo/SalonDAO.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}
$daoSalones = new SalonDAO();
$salonEditar = null;
if (isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($_GET['id'])) {
    $salonEditar = $daoSalones->obtenerPorId($_GET['id']);
}

$lista = $daoSalones->obtenerTodos();
?>

<html>
    <head>
        <title>Gestionar Salones</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../StyleSheets/Inicio.css">
        <link rel="stylesheet" href="../StyleSheets/categoriasView.css">
    </head>
    
    <body>
        <div class="header">
        <div class="header-container">
             <div class="logo-container">
                 <img src="../Assets/logoFILEY.png" alt="Logo" class="logo">
             </div>
             <div class="nav-menu">
                <a href="adminFeed.php" class="nav-link">Regresar</a>
                <span class="usuario-info">Administrador: <?php echo $_SESSION['nombre']; ?></span>
            </div>
        </div>
    </div>

    <div class="crud-container">
        
        <!-- Formulario de edición -->
        <div class="form-card">
            <h3 style="color:#005288; margin-bottom: 20px;">
                <?php echo $salonEditar ? '️ Editar Salón' : 'Nuevo Salón'; ?>
            </h3>

            <?php if(isset($_GET['message'])): ?>
                <div class="alert success">
                    <?php 
                        if($_GET['message']=='agregado') echo "¡Salón agregado con éxito!";
                        if($_GET['message']=='actualizado') echo "¡Cambios guardados correctamente!";
                        if($_GET['message']=='eliminado') echo "Salón eliminado.";
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="../Controlador/SalonController.php" method="POST">
                <input type="hidden" name="accion" value="<?php echo $salonEditar ? 'actualizar' : 'agregar'; ?>">
                
                <!-- Solo visible cuando se edita un salón -->
                <?php if ($salonEditar): ?>
                <input type="hidden" name="idSalon" value="<?php echo $salonEditar->getIdSalon(); ?>">
                <?php endif; ?>
                
                <label>Nombre del Salón:</label>
                <!-- El value se rellena solo si estamos editando -->
                <input type="text" name="nombreSalon" required placeholder=" "
                       value="<?php echo $salonEditar ? $salonEditar->getNombreSalon() : ''; ?>">
                
                <label>Capacidad máxima:</label>
                <input type="number" name="maxCapacidad" required min="1" placeholder="Solo ingrese el número"
                       value="<?php echo $salonEditar ? $salonEditar->getMaxCapacidad() : ''; ?>">
                
                <button type="submit" class="btn" style="background-color:#005288; color:white; width:100%;">
                    <?php echo $salonEditar ? 'Guardar Cambios' : 'Crear Salón'; ?>
                </button>

                <?php if ($salonEditar): ?>
                    <a href="salones.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">
                        Cancelar Edición
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabla con los salones registrados -->
        <div class="list-card">
            <h3 style="color:#005288; margin-bottom: 20px;">Salones Existentes</h3>
            
            <?php if (empty($lista)): ?>
                <p style="color:#666; text-align:center; padding:20px;">No hay salones registradas</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30%;">Nombre del salón</th>
                            <th style="width: 40%;">Capacidad</th>
                            <th style="width: 30%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista as $salon): ?>
                        <tr>
                            <td><strong><?php echo $salon->getNombreSalon(); ?></strong></td>
                            <td><?php echo $salon->getMaxCapacidad(); ?></td>
                            <td>
                                <a href="salones.php?accion=editar&id=<?php echo $salon->getIdSalon(); ?>" class="btn-action btn-edit">
                                    Editar
                                </a>
                                
                                <a href="../Controlador/SalonController.php?accion=eliminar&id=<?php echo $salon->getIdSalon(); ?>" 
                                   class="btn-action btn-delete" 
                                   onclick="return confirm('¿Eliminar este salón?');">
                                   Borrar
                                </a>
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
