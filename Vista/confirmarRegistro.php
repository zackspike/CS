<?php
session_start();
if (!isset($_SESSION['idUsuario'])) { header("Location: login.php"); exit(); }
$idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmar Inscripción</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../StyleSheets/Inicio.css">
    <style>
        .confirm-card {
            max-width: 500px; margin: 100px auto; background: white; padding: 40px;
            text-align: center; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-top: 5px solid #005288;
        }
        .btn-confirm { background-color: #28a745; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 20px;}
        .btn-cancel { color: #666; margin-left: 20px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="confirm-card">
            <h2 style="color:#005288; margin-bottom: 15px;">¿Confirmar asistencia?</h2>
            <p>Se reservará tu lugar en este evento.</p>
            
            <a href="../Controlador/RegistroController.php?accion=inscribir&idEvento=<?php echo $idEvento; ?>" class="btn-confirm">
                Sí, Confirmar
            </a>
            
            <a href="index.php" class="btn-cancel">Cancelar</a>
        </div>
    </div>
</body>
</html>