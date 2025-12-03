<?php
require_once 'CrudDAO.php';
require_once 'Categoria.php';

class CategoriaDAO extends CrudDAO {

    public function __construct() {
        parent::__construct();
        $this->nombreTabla = "Categorias";
        $this->nombreId = "idCategoria";
    }

    protected function mapear($fila) {
        return new Categoria(
            (int)$fila['idCategoria'],
            $fila['nombre'],
            $fila['descripcion']
        );
    }

    public function agregar($categoria) {
        $sql = "INSERT INTO Categorias (nombre, descripcion) VALUES (?, ?)";
        $statement = $this->conexion->prepare($sql);
        
        $nombre = $categoria->getNombre();
        $desc = $categoria->getDescripcion();
        
        $statement->bind_param("ss", $nombre, $desc);
        
        $exito = $statement->execute();
        $statement->close();
        return $exito;
    }

    public function actualizar($categoria) {
        $sql = "UPDATE Categorias SET nombre = ?, descripcion = ? WHERE idCategoria = ?";
        $statement = $this->conexion->prepare($sql);
        
        $nombre = $categoria->getNombre();
        $desc = $categoria->getDescripcion();
        $id = $categoria->getIdCategoria();
        
        $statement->bind_param("ssi", $nombre, $desc, $id);
        
        $exito = $statement->execute();
        $statement->close();
        return $exito;
    }
}
