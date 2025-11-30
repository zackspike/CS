<?php

require_once 'conexionBD.php';
require_once 'Editorial.php';


class EditorialDAO {
    private $conexion;

    public function __construct() {
        $bd = new ConexionBD();
        $this->conexion = $bd->conectar();
    }

    // Añadir una editorial
    public function agregar(Editorial $editorial) {
        $sql = "INSERT INTO Editoriales (nombreEditorial, numPuestoEditorial, ubicacionPuestoEditoral) VALUES (?, ?, ?)";
        
        $statement = $this->conexion->prepare($sql);
        
        // Obtenemos los valores del objeto
        $nombreEditorial = $editorial->getNombreEditorial();
        $numPuestoEditorial= $editorial->getNumPuestoEditorial();
        $ubicacionPuestoEditoral= $editorial->getUbicacionPuestoEditorial();
                
        $statement ->bind_param("sis", $nombreEditorial, $numPuestoEditorial, $ubicacionPuestoEditoral);
        
        if($statement->execute()) {
            $statement ->close();
            return true;
        } else {
            $statement ->close();
            return false;
        }
    }

     public function obtenerPorId($id) {
        $sql = "SELECT * FROM Editoriales WHERE idEditorial = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        
        if (($fila = $result->fetch_assoc())) {
            $editorial = new Editorial(
                (int)$fila['idEditorial'], 
                $fila['nombreEditorial'], 
                $fila['numPuestoEditorial'],
                $fila['ubicacionPuestoEditorial'],
            );
            $statement->close();
            return $editorial;
        }
        $statement->close();
        return null;
    }
    
    //Obtener la tabla de las editoriales de la BD
    public function obtenerEditoriales() {
        $sql = "SELECT * FROM Editoriales";
        $result = $this->conexion->query($sql);
        
        $lista = [];
        
        while ($fila = $result->fetch_assoc()) {
                $editorial = new Editorial(
                $fila['idEditorial'], 
                $fila['nombreEditorial'], 
                $fila['numPuestoEditorial'],
                $fila['ubicacionPuestoEditorial'],
            );
            $lista[] = $editorial;
        }
        return $lista;
    }
    
    //Modificar una editorial
    public function actualizar(Editorial $editorial) {
        $sql = "UPDATE Salones SET nombreSalon = ?, maxCapacidad = ? WHERE idSalon = ?";
        $statement = $this->conexion->prepare($sql);
        
        $nombreEditorial = $editorial->getNombreEditorial();
        $numPuestoEditorial= $editorial->getNumPuestoEditorial();
        $ubicacionPuestoEditoral= $editorial->getUbicacionPuestoEditorial();
        $idEditorial = $editorial->getIdEditorial();
        
        $statement->bind_param("sis", $nombreEditorial, $numPuestoEditorial, $ubicacionPuestoEditoral, $idEditorial);
        
        if($statement->execute()) {
            $statement->close();
            return true;
        }
        $statement->close();
        return false;
    }
    
    //Eliminar una editorial
    public function eliminar($idEditorial) {
        $sql = "DELETE FROM Editoriales WHERE idEditorial = ?";
        $statement  = $this->conexion->prepare($sql);
        $statement ->bind_param("i", $idEditorial);
        
        if ($statement ->execute()) {
            $statement ->close();
            return true;
        } else {
            $statement ->close();
            return false;
        }
    }
}

