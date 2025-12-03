<?php
require_once 'DAO.php';
require_once 'Registro.php';

class RegistroDAO extends DAO {

    public function yaEstaInscrito($idUsuario, $idEvento) {
        $sql = "SELECT idRegistro FROM Registros WHERE idUsuario = ? AND idEvento = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("ii", $idUsuario, $idEvento);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows > 0;
    }

    public function inscribir($idUsuario, $idEvento) {
        // Por defecto, asistio = 0
        $sql = "INSERT INTO Registros (idUsuario, idEvento, asistio) VALUES (?, ?, 0)";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("ii", $idUsuario, $idEvento);
        return $statement->execute();
    }

    public function obtenerPorUsuario($idUsuario) {
        $sql = "SELECT registro.*, evento.titulo, evento.fecha, evento.horaInicio, evento.tipoEvento, salon.nombreSalon
                FROM Registros registro
                JOIN Eventos evento ON registro.idEvento = evento.idEvento
                JOIN Salones salon ON evento.idSalon = salon.idSalon
                WHERE registro.idUsuario = ?
                ORDER BY registro.fechaRegistro DESC";
        
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $idUsuario);
        $statement->execute();
        $result = $statement->get_result();
        
        $lista = [];
        while ($fila = $result->fetch_assoc()) {
            $registro = new Registro(
                $fila['idRegistro'],
                $fila['fechaRegistro'],
                $fila['asistio'],
                $fila['idUsuario'],
                $fila['idEvento']
            );
            $registro->setDetallesEvento($fila['titulo'], $fila['fecha'],$fila['horaInicio'], $fila['nombreSalon'], $fila['tipoEvento']);
            $lista[] = $registro;
        }
        return $lista;
    }
    
    public function obtenerPorEvento($idEvento) {
        $sql = "SELECT registro.idRegistro, registro.fechaRegistro, registro.asistio, usuario.nombre, usuario.email
                FROM Registros registro
                JOIN Usuarios usuario ON registro.idUsuario = usuario.idUsuario
                WHERE registro.idEvento = ?
                ORDER BY usuario.nombre ASC";
        
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $idEvento);
        $statement->execute();
        $result = $statement->get_result();
        
        $lista = [];
        while ($fila = $result->fetch_assoc()) {
            $lista[] = $fila;
        }
        return $lista;
    }
    
    public function validarAsistencia($idRegistro) {
        $sql = "UPDATE Registros SET asistio = 1 WHERE idRegistro = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $idRegistro);
        return $statement->execute();
    }

    public function cancelar($idRegistro) {
        $sql = "DELETE FROM Registros WHERE idRegistro = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $idRegistro);
        return $statement->execute();
    }
    
    public function obtenerPorId($idRegistro) {
        $sql = "SELECT registro.*, usuario.nombre as nombreUsuario, evento.titulo, evento.fecha, evento.tipoEvento
                FROM Registros registro
                JOIN Usuarios usuario ON registro.idUsuario = usuario.idUsuario
                JOIN Eventos evento ON registro.idEvento = evento.idEvento
                WHERE registro.idRegistro = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $idRegistro);
        $statement->execute();
        $result = $statement->get_result();
        
        if ($fila = $result->fetch_assoc()) {
            return $fila;
        }
        return null;
    }
}
