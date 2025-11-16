<?php

require_once __DIR__ . '/Categoria.php';

/**
 * Description of Editorial
 *
 * @author Gabriela Puch
 */
class Editorial implements Categoria {
    private $idEditorial;
    private $nombreEditorial;
    private $numPuestoEditorial;
    private $ubicacionPuestoEditorial;
    
    public function __construct($idEditorial, $nombreEditorial, $numPuestoEditorial, $ubicacionPuestoEditorial) {
        $this->idEditorial = $idEditorial;
        $this->nombreEditorial = $nombreEditorial;
        $this->numPuestoEditorial = $numPuestoEditorial;
        $this->ubicacionPuestoEditorial = $ubicacionPuestoEditorial;
    }

    public function getIdEditorial() {
        return $this->idEditorial;
    }

    public function getNombreEditorial() {
        return $this->nombreEditorial;
    }

    public function getNumPuestoEditorial() {
        return $this->numPuestoEditorial;
    }

    public function getUbicacionPuestoEditorial() {
        return $this->ubicacionPuestoEditorial;
    }

    public function setIdEditorial($idEditorial): void {
        $this->idEditorial = $idEditorial;
    }

    public function setNombreEditorial($nombreEditorial): void {
        $this->nombreEditorial = $nombreEditorial;
    }

    public function setNumPuestoEditorial($numPuestoEditorial): void {
        $this->numPuestoEditorial = $numPuestoEditorial;
    }

    public function setUbicacionPuestoEditorial($ubicacionPuestoEditorial): void {
        $this->ubicacionPuestoEditorial = $ubicacionPuestoEditorial;
    }

    public function tipoCategoria(): string {
        return "Editorial";
    }
}
