<?php
require_once 'CrudDAO.php';
require_once 'Editorial.php';

class EditorialDAO extends CrudDAO {

    public function __construct() {
        parent::__construct();
        $this->nombreTabla = "Editoriales";
        $this->nombreId = "idEditorial";
    }

    protected function mapear($fila) {
        return new Editorial(
            (int)$fila['idEditorial'],
            $fila['nombreEditorial'],
            $fila['numPuestoEditorial'],
            $fila['ubicacionPuestoEditorial']
        );
    }

    public function agregar($editorial) {
        $sql = "INSERT INTO Editoriales (nombreEditorial, numPuestoEditorial, ubicacionPuestoEditorial) VALUES (?, ?, ?)";
        
        $statement = $this->conexion->prepare($sql);
        
        $nombre = $editorial->getNombreEditorial();
        $num = $editorial->getNumPuestoEditorial();
        $ubic = $editorial->getUbicacionPuestoEditorial();
                
        $statement->bind_param("sis", $nombre, $num, $ubic);
        
        $exito = $statement->execute();
        $statement->close();
        return $exito;
    }
    
    public function actualizar($editorial) {
        $sql = "UPDATE Editoriales SET nombreEditorial = ?, numPuestoEditorial = ?, ubicacionPuestoEditorial = ? WHERE idEditorial = ?";
        
        $statement = $this->conexion->prepare($sql);

        $nombre = $editorial->getNombreEditorial();
        $num = $editorial->getNumPuestoEditorial();
        $ubic = $editorial->getUbicacionPuestoEditorial();
        $id = $editorial->getIdEditorial();
        
        $statement->bind_param("sisi", $nombre, $num, $ubic, $id);
        
        $exito = $statement->execute();
        $statement->close();
        return $exito;
    }

}
