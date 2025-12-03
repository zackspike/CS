<?php
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: login.php");
    exit();
}

$nombreAdmin = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es>
    <head>
        <title>Admin FILEY</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../StyleSheets/Inicio.css">
        <link rel="stylesheet" href="../StyleSheets/adminView.css">
    </head>
    <body>
        <div class="header">
            <div class="header-container">
                <div class="logo-container">
                    <img src="../Assets/logoFILEY.png" alt="Logo FILEY" class="logo">
                </div>
                <div class="nav-menu">
                    <a href="index.php" class="nav-link">Ver Web Pública</a>
                    <span class="usuario-info">Administrador: <?php echo $nombreAdmin; ?></span>
                    <a href="../Controlador/UsuarioController.php?accion=logout" class="nav-link btn-logout">Salir</a>
                </div>
            </div>
        </div>
        
        <div class="admin-content">
            <div class="admin-welcome">
                <h1>Panel de Control</h1>
                <p>¿Qué deseas administrar hoy?</p>
            </div>

            <div class="admin-grid">
                <!--EVENTOS -->
                <div class="admin-option">
                    <h3>Gestionar Eventos</h3>
                    <p>Crea conferencias, talleres y premiaciones. Edita la información o elimina eventos cancelados.</p>
                    <a href="gestionEventos.php" class="btn-admin">Administrar</a>
                </div>

                <!--CATEGORÍAS -->
                <div class="admin-option">
                    <h3>️ Categorías</h3>
                    <p>Agrega nuevas temáticas para los eventos.</p>
                    <a href="categorias.php" class="btn-admin">Administrar</a>
                </div>

                <!--SALONES -->
                <div class="admin-option">
                    <h3>Salones</h3>
                    <p>Gestiona los auditorios y aulas disponibles.</p>
                    <a href="salones.php" class="btn-admin">Administrar</a>
                </div>

                <!--EDITORIALES -->
                <div class="admin-option">
                    <h3>Editoriales</h3>
                    <p>Registro de las empresas y puestos editoriales que participan en la FILEY.</p>
                    <a href="editoriales.php" class="btn-admin">Administrar</a>
                </div>

            </div>
        </div>
        
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
