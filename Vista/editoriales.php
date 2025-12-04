<?php
session_start();

require_once '../Modelo/EditorialDAO.php';

use Modelo\EditorialDAO;

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}
$daoEditoriales = new EditorialDAO();
$editorialEditar = null;
if (isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($_GET['id'])) {
    $editorialEditar = $daoEditoriales->obtenerPorId($_GET['id']);
}

$lista = $daoEditoriales->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Gestionar Editoriales</title>
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
                <?php echo $editorialEditar ? '️ Editar Editorial' : 'Nueva Editorial'; ?>
            </h3>

            <?php if(isset($_GET['message'])): ?>
                <div class="alert success">
                    <?php
                        if($_GET['message']=='agregado'){ echo "¡Editorial agregada con éxito!";}
                        if($_GET['message']=='actualizado'){ echo "¡Cambios guardados correctamente!";}
                        if($_GET['message']=='eliminado'){ echo "Editorial eliminada.";}
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="../Controlador/EditorialController.php" method="POST">
                <input type="hidden" name="accion" value="<?php echo $editorialEditar ? 'actualizar' : 'agregar'; ?>">
                
                <!-- Solo visible cuando se edita una editorial -->
                <?php if ($editorialEditar): ?>
                <input type="hidden" name="idEditorial" value="<?php echo $editorialEditar->getIdEditorial(); ?>">
                <?php endif; ?>
                
                <label for="nombreEditorial">Nombre de la editorial:</label>
                <!-- El value se rellena solo si estamos editando -->
                <input type="text" name="nombreEditorial" required placeholder=" "
                       value="<?php echo $editorialEditar ? $editorialEditar->getNombreEditorial() : ''; ?>">
                
                <label for="numPuestoEditorial">Número del puesto:</label>
                <input type="number" id="numPuestoEditorial" name="numPuestoEditorial" required min="1"
                       value="<?php echo $editorialEditar ? $editorialEditar->getNumPuestoEditorial() : ''; ?>">
                
                <label for="ubicacionPuestoEditorial">Ubicación del puesto:</label>
                <input type="text" id="ubicacionPuestoEditorial" name="ubicacionPuestoEditorial" required placeholder=" "
                       value="<?php echo $editorialEditar ? $editorialEditar->getUbicacionPuestoEditorial() : ''; ?>">
                
                <button type="submit" class="btn" style="background-color:#005288; color:white; width:100%;">
                    <?php echo $editorialEditar ? 'Guardar Cambios' : 'Crear Editorial'; ?>
                </button>

                <?php if ($editorialEditar): ?>
                    <a href="editoriales.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">
                        Cancelar Edición
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabla con las editoriales registradas -->
        <div class="list-card">
            <h3 style="color:#005288; margin-bottom: 20px;">Editoriales Existentes</h3>
            
            <?php if (empty($lista)): ?>
                <p style="color:#666; text-align:center; padding:20px;">No hay editoriales registradas</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30%;">Nombre de la editorial</th>
                            <th style="width: 25%;">Número de puesto</th>
                            <th style="width: 20%;">Ubicación</th>
                            <th style="width: 25%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista as $editorial): ?>
                        <tr>
                            <td><strong><?php echo $editorial->getNombreEditorial(); ?></strong></td>
                            <td><?php echo $editorial->getNumPuestoEditorial(); ?></td>
                            <td><?php echo $editorial->getUbicacionPuestoEditorial(); ?></td>
                            <td>
                                <a href="editoriales.php?accion=editar&id=<?php echo $editorial->getIdEditorial(); ?>" class="btn-action btn-edit">
                                    Editar
                                </a>
                                
                                <a href="../Controlador/EditorialController.php?accion=eliminar&id=<?php echo $editorial->getIdEditorial(); ?>"
                                   class="btn-action btn-delete"
                                   onclick="return confirm('¿Eliminar esta editorial?');">
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


