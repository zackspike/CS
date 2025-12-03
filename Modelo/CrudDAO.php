<?php
require_once 'DAO.php';

/**
 * Clase enfocada en gestionar el CRUD
 * @template T
 */
abstract class CrudDAO extends DAO {

    protected $nombreTabla;
    protected $nombreId;

    abstract protected function mapear($fila);
    abstract public function agregar($entidad);
    abstract public function actualizar($entidad);

    public function obtenerTodos() {
        $sql = "SELECT * FROM " . $this->nombreTabla;
        $result = $this->conexion->query($sql);
        
        $lista = [];
        
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $lista[] = $this->mapear($fila);
            }
        }
        return $lista;
    }

    public function obtenerPorId($id) {
        $sql = "SELECT * FROM " . $this->nombreTabla . " WHERE " . $this->nombreId . " = ?";
        
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $id);
        
        if ($statement->execute()) {
            $result = $statement->get_result();
            if ($fila = $result->fetch_assoc()) {
                $objeto = $this->mapear($fila);
                $statement->close();
                return $objeto;
            }
        }
        $statement->close();
        return null;
    }

    public function eliminar($id) {
        $sql = "DELETE FROM " . $this->nombreTabla . " WHERE " . $this->nombreId . " = ?";
        
        $statement = $this->conexion->prepare($sql);
        $statement->bind_param("i", $id);
        
        $resultado = $statement->execute();
        $statement->close();
        
        return $resultado;
    }
}
