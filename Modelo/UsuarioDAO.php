<?php

require_once 'conexionBD.php';
require_once 'Usuario.php'; 

class UsuarioDAO {
    private $conexion;

    public function __construct() {
        $bd = new ConexionBD();
        $this->conexion = $bd->conectar();
    }

    public function registrar(Usuario $usuario) {
        $sql = "INSERT INTO Usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $statement = $this->conexion->prepare($sql);
        
        if (!$statement) {
            die("Error al preparar la consulta");
        }

        $nombre = $usuario->getNombre();
        $email = $usuario->getEmail();
        $password = $usuario->getPassword(); 

        $statement->bind_param("sss", $nombre, $email, $password);

        if ($statement->execute()) {
            $statement->close();
            return true;
        } else {
            $statement->close();
            return false; 
        }
    }

    public function login($email, $password) {
        $sql = "SELECT idUsuario, nombre, password, rolUsuario FROM Usuarios WHERE email = ?";
        
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("s", $email);
        $statement->execute();
        
        $resultado = $statement->get_result();

        if (($fila = $resultado->fetch_assoc())) {
            if (password_verify($password, $fila['password'])) {
                unset($fila['password']); 
                return $fila;
            }
        }
        
        return false;
    }
}

