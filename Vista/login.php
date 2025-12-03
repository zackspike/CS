<?php
$mensaje = "";
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'credenciales') {
        $mensaje = "Correo o contraseña incorrectos.";
    } elseif ($_GET['error'] == 'fallo_registro') {
        $mensaje = "Error al registrar.";
    }
}
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'registrado') {
    $mensaje = "El registro fue exitoso, por favor inicia sesión.";
}
?>
<!DOCTYPE>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Acceso FILEY</title>
        <link rel="stylesheet" href="../StyleSheets/login.css">
    </head>
    <script>
        function mostrarRegistro() {
            document.getElementById('loginUsuario').style.display = 'none';
            document.getElementById('registroUsuario').style.display = 'block';
        }
        
        function mostrarLogin() {
            document.getElementById('registroUsuario').style.display = 'none';
            document.getElementById('loginUsuario').style.display = 'block';
        }
    </script>
    <body>
        <div class="login-container">
            
            <?php if($mensaje): ?>
                <div class="alert <?php echo (isset($_GET['mensaje'])) ? 'success' : ''; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <div class="login-box" id="loginUsuario">
                <h3 class="login-title">Iniciar sesión</h3>
                
                <form action="../Controlador/UsuarioController.php" method="post">
                    
                    <input type="hidden" name="accion" value="login">
                    <div class="form-group">
                        <label for="email">Correo:</label>
                        <input type="email" name="email" id="email" required/>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" id="password" required/>
                    </div>
                    <button type="submit" class="btn-submit">Ingresar</button>
                </form>

                <div class="links">
                    <p>¿No tienes una cuenta?</p>
                    <a href="#" onclick="mostrarRegistro(); return false;" class="link">Regístrate aquí</a>
                </div>
                                
            </div>
            <br>
            <a href="index.php"class="btn-submit">Regresar</a>
            
            <div class="login-box" id="registroUsuario" style="display: none;">
                <h3 class="login-title">Crear Cuenta</h3>
                
                <form action="../Controlador/UsuarioController.php" method="post">
                    <input type="hidden" name="accion" value="registrar">

                    <div class="form-group">
                        <label for="nombreRegistro">Nombre Completo:</label>
                        <input type="text" name="nombre" id="nombreRegistro" required />
                    </div>
                    <div class="form-group">
                        <label for="emailRegistro">Correo:</label>
                        <input type="email" name="email" id="emailRegistro" required />
                    </div>
                    <div class="form-group">
                        <label for="passwordRegistro">Contraseña:</label>
                        <input type="password" name="password" id="passwordRegistro" required />
                    </div>
                    <button type="submit" class="btn-submit">Registrarme</button>
                </form>

                <div class="links">
                    <p>¿Ya tienes cuenta?</p>
                    <a href="#" onclick="mostrarLogin(); return false;" class="link">Volver al Login</a>
                </div>
            </div>

        </div>
    </body>
</html>
