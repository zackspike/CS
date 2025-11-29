<?php
/**
 * Description of DAO
 *
 * @author Gabriela Puch
 */
class DAO {
    private $conexion;

    public function __construct() {
        $bd = new ConexionBD();
        $this->conexion = $bd->conectar();
    }
}