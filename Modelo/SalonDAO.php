<?php

require_once 'conexionBD.php';
require_once 'Salon.php';
require_once 'DAO.php';

class SalonDAO{
    private $conexion;

    public function __construct() {
        $bd = new ConexionBD();
        $this->conexion = $bd->conectar();
    }

    // Añadir un salón
    public function agregar(Salon $salon) {
        $sql = "INSERT INTO Salones (nombreSalon, maxCapacidad) VALUES (?, ?)";
        
        $statement = $this->conexion->prepare($sql);
        
        // Obtenemos los valores del objeto
        $nombreSalon = $salon->getNombreSalon();
        $maxCapacidad = $salon->getMaxCapacidad();
        
        $statement ->bind_param("si", $nombreSalon, $maxCapacidad);
        
        if($statement->execute()) {
            $statement ->close();
            return true;
        } else {
            $statement ->close();
            return false;
        }
    }

     public function obtenerPorId($id) {
        $sql = "SELECT * FROM Salones WHERE idSalon = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        
        if (($fila = $result->fetch_assoc())) {
            $salon = new Salon(
                (int)$fila['idSalon'], 
                $fila['nombreSalon'], 
                $fila['maxCapacidad']
            );
            $statement->close();
            return $salon;
        }
        $statement->close();
        return null;
    }
    
    //Obtener la tabla de los salones de la BD
    public function obtenerSalones() {
        $sql = "SELECT * FROM Salones";
        $result = $this->conexion->query($sql);
        
        $lista = [];
        
        while ($fila = $result->fetch_assoc()) {
                $salon = new Salon(
                $fila['idSalon'], 
                $fila['nombreSalon'], 
                $fila['maxCapacidad']
            );
            $lista[] = $salon;
        }
        return $lista;
    }
    
    //Modificar un salón
    public function actualizar(Salon $salon) {
        $sql = "UPDATE Salones SET nombreSalon = ?, maxCapacidad = ? WHERE idSalon = ?";
        $statement = $this->conexion->prepare($sql);
        
        $nombreSalonNuevo = $salon->getNombreSalon();
        $maxCapacidadNueva = $salon->getMaxCapacidad();
        $idSalon = $salon->getIdSalon();
        
        $statement->bind_param("sii", $nombreSalonNuevo, $maxCapacidadNueva, $idSalon);
        
        if($statement->execute()) {
            $statement->close();
            return true;
        }
        $statement->close();
        return false;
    }
    
    //Eliminar una categoría
    public function eliminar($idSalon) {
        $sql = "DELETE FROM Salones WHERE idSalon = ?";
        $statement  = $this->conexion->prepare($sql);
        $statement ->bind_param("i", $idSalon);
        
        if ($statement ->execute()) {
            $statement ->close();
            return true;
        } else {
            $statement ->close();
            return false;
        }
    }
}

