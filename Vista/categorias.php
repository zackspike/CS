<?php
session_start();
require_once '../Modelo/CategoriaDAO.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}
$daoCategorias = new CategoriaDAO();
$categoriaEditar = null;
if (isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($_GET['id'])) {
    $categoriaEditar = $daoCategorias->obtenerPorId($_GET['id']);
}

$lista = $daoCategorias->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Gestionar Categorías</title>
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
                <?php echo $categoriaEditar ? '️ Editar Categoría' : 'Nueva Categoría'; ?>
            </h3>

            <?php if(isset($_GET['message'])): ?>
                <div class="alert success">
                    <?php
                        if($_GET['message']=='agregado') echo "¡Categoría agregada con éxito!";
                        if($_GET['message']=='actualizado') echo "¡Cambios guardados correctamente!";
                        if($_GET['message']=='eliminado') echo "Categoría eliminada.";
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="../Controlador/CategoriaController.php" method="POST">
                <input type="hidden" name="accion" value="<?php echo $categoriaEditar ? 'actualizar' : 'agregar'; ?>">
                
                <!-- Solo visible cuando se edita una categoría -->
                <?php if ($categoriaEditar): ?>
                    <input type="hidden" name="idCategoria" value="<?php echo $categoriaEditar->getIdCategoria(); ?>">
                <?php endif; ?>
                
                <label>Nombre de la Categoría:</label>
                <!-- El value se rellena solo si estamos editando -->
                <input type="text" name="nombre" required placeholder=" "
                       value="<?php echo $categoriaEditar ? $categoriaEditar->getNombre() : ''; ?>">
                
                <label>Descripción:</label>
                <textarea name="descripcion" rows="4" placeholder="Descripción de la categoría"><?php echo $categoriaEditar ? $categoriaEditar->getDescripcion() : ''; ?></textarea>
                
                <button type="submit" class="btn" style="background-color:#005288; color:white; width:100%;">
                    <?php echo $categoriaEditar ? 'Guardar Cambios' : 'Crear Categoría'; ?>
                </button>

                <?php if ($categoriaEditar): ?>
                    <a href="categorias.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">
                        Cancelar Edición
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabla con las categorías registradas -->
        <div class="list-card">
            <h3 style="color:#005288; margin-bottom: 20px;">Categorías Existentes</h3>
            
            <?php if (empty($lista)): ?>
                <p style="color:#666; text-align:center; padding:20px;">No hay categorías registradas</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30%;">Nombre</th>
                            <th style="width: 40%;">Descripción</th>
                            <th style="width: 30%;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($lista as $categoria): ?>
                        <tr>
                            <td><strong><?php echo $categoria->getNombre(); ?></strong></td>
                            <td><?php echo $categoria->getDescripcion(); ?></td>
                            <td>
                                <a href="categorias.php?accion=editar&id=<?php echo $categoria->getIdCategoria(); ?>" class="btn-action btn-edit">
                                    Editar
                                </a>
                                
                                <a href="../Controlador/CategoriaController.php?accion=eliminar&id=<?php echo $categoria->getIdCategoria(); ?>"
                                   class="btn-action btn-delete"
                                   onclick="return confirm('¿Eliminar esta categoría?');">
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
