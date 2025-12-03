<?php
require_once 'CrudDAO.php';
require_once 'Salon.php';

class SalonDAO extends CrudDAO {

    public function __construct() {
        parent::__construct();
        $this->nombreTabla = "Salones";
        $this->nombreId = "idSalon";
    }

    protected function mapear($fila) {
        return new Salon(
            (int)$fila['idSalon'],
            $fila['nombreSalon'],
            $fila['maxCapacidad']
        );
    }

    public function agregar($salon) {
        $sql = "INSERT INTO Salones (nombreSalon, maxCapacidad) VALUES (?, ?)";
        $statement = $this->conexion->prepare($sql);
        
        $nombre = $salon->getNombreSalon();
        $capacidad = $salon->getMaxCapacidad();
        
        $statement->bind_param("si", $nombre, $capacidad);
        
        $exito = $statement->execute();
        $statement->close();
        return $exito;
    }

    public function actualizar($salon) {
        $sql = "UPDATE Salones SET nombreSalon = ?, maxCapacidad = ? WHERE idSalon = ?";
        $statement = $this->conexion->prepare($sql);
        
        $nombre = $salon->getNombreSalon();
        $capacidad = $salon->getMaxCapacidad();
        $id = $salon->getIdSalon();
        
        $statement->bind_param("sii", $nombre, $capacidad, $id);
        
        $exito = $statement->execute();
        $statement->close();
        return $exito;
    }
}
