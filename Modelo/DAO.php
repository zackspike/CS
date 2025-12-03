<?php
require_once 'conexionBD.php';

class DAO {
    protected $conexion;

    public function __construct() {
        $bd = new ConexionBD();
        $this->conexion = $bd->conectar();
    }
}
