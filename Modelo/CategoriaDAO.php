<?php
require_once 'ConexionBD.php';
require_once 'Categoria.php';

class CategoriaDAO {
    private $conexion;

        public function __construct() {
        $bd = new ConexionBD();
        $this->conexion = $bd->conectar();
    }

    public function agregar(Categoria $categoria) {
        $sql = "INSERT INTO Categorias (nombre, descripcion) VALUES (?, ?)";
        
        $statement = $this->conexion->prepare($sql);
        
        // Obtenemos los valores del objeto
        $nombreCat = $categoria->getNombre();
        $descripCat = $categoria->getDescripcion();
        
        $statement ->bind_param("ss", $nombreCat, $descripCat);
        
        if($statement->execute()) {
            $statement ->close();
            return true;
        } else {
            $statement ->close();
            return false;
        }
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM Categorias WHERE idCategoria = ?";
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        
        if (($fila = $result->fetch_assoc())) {
            $categoria = new Categoria(
                (int)$fila['idCategoria'], 
                $fila['nombre'], 
                $fila['descripcion']
            );
            $statement->close();
            return $categoria;
        }
        $statement->close();
        return null;
    }

    //Obtener la tabla de categorías de la BD
    public function obtenerCategorias() {
        $sql = "SELECT * FROM Categorias";
        $result = $this->conexion->query($sql);
        
        $lista = [];
        
        while ($fila = $result->fetch_assoc()) {
                $categoria = new Categoria(
                $fila['idCategoria'], 
                $fila['nombre'], 
                $fila['descripcion']
            );
            $lista[] = $categoria;
        }
        return $lista;
    }

    //Modificar una categoría
    public function actualizar(Categoria $categoria) {
        $sql = "UPDATE Categorias SET nombre = ?, descripcion = ? WHERE idCategoria = ?";
        $statement = $this->conexion->prepare($sql);
        
        $nombreCat = $categoria->getNombre();
        $descripCat = $categoria->getDescripcion();
        $idCat = $categoria->getIdCategoria();
        
        // "ssi" = String, String, Integer
        $statement->bind_param("ssi", $nombreCat, $descripCat, $idCat);
        
        if($statement->execute()) {
            $statement->close();
            return true;
        }
        $statement->close();
        return false;
    }
}
?>