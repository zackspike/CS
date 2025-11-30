<?php
session_start();

require_once '../Modelo/Usuario.php';
require_once '../Modelo/UsuarioDAO.php';

//se valida si hubo alguna acción 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuarioDAO = new UsuarioDAO();
    $accion = $_POST['accion']; //leemos la acción que se realizó

    switch ($accion) {
        case 'registrar':
            //obtenemos los datos registrados en el form
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            
            //creamos el objeto del usuario
            $usuario = new Usuario(0, $nombre, $email, 'usuario', $passwordHash);

            // Se llama a UsuarioDAO
            if ($usuarioDAO->registrar($usuario)) {
                header("Location: ../Vista/login.php?mensaje=registrado");
            } else {
                header("Location: ../Vista/registro.php?error=fallo_registro");
            }
            break;
            
        case 'login':
            $email = $_POST['email'];
            $password = $_POST['password'];

            $usuarioEncontrado = $usuarioDAO->login($email, $password);

            if ($usuarioEncontrado) {
                $_SESSION['idUsuario'] = $usuarioEncontrado['idUsuario'];
                $_SESSION['nombre'] = $usuarioEncontrado['nombre'];
                $_SESSION['rol'] = $usuarioEncontrado['rolUsuario']; 

                // Redireccionamos a las vistas según el rol
                if ($usuarioEncontrado['rolUsuario'] == 'admin') {
                    header("Location: ../Vista/adminFeed.php");
                } else {
                    header("Location: ../Vista/index.php"); // Página de inicio normal
                }
            } else {
                header("Location: ../Vista/login.php?error=credenciales");
            }
            break;
    }
}

if (isset($_GET['accion']) && $_GET['accion'] == 'logout') {
    session_destroy(); 
    header("Location: ../Vista/index.php");
    exit();
}


