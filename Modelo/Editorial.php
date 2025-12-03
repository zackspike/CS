<?php

/**
 * Description of Editorial
 *
 * @author Isaac Herrera
 */
class Editorial  {
    private int $idEditorial;
    private string $nombreEditorial;
    private int $numPuestoEditorial;
    private string $ubicacionPuestoEditorial;
    
    public function __construct(int $idEditorial, string $nombreEditorial, int $numPuestoEditorial, string $ubicacionPuestoEditorial) {
        $this->idEditorial = $idEditorial;
        $this->nombreEditorial = $nombreEditorial;
        $this->numPuestoEditorial = $numPuestoEditorial;
        $this->ubicacionPuestoEditorial = $ubicacionPuestoEditorial;
    }

    public function getIdEditorial(): int {
        return $this->idEditorial;
    }

    public function getNombreEditorial(): string {
        return $this->nombreEditorial;
    }

    public function getNumPuestoEditorial(): int {
        return $this->numPuestoEditorial;
    }

    public function getUbicacionPuestoEditorial(): string {
        return $this->ubicacionPuestoEditorial;
    }

    public function setIdEditorial(int $idEditorial): void {
        $this->idEditorial = $idEditorial;
    }

    public function setNombreEditorial(string $nombreEditorial): void {
        $this->nombreEditorial = $nombreEditorial;
    }

    public function setNumPuestoEditorial(int $numPuestoEditorial): void {
        $this->numPuestoEditorial = $numPuestoEditorial;
    }

    public function setUbicacionPuestoEditorial(string $ubicacionPuestoEditorial): void {
        $this->ubicacionPuestoEditorial = $ubicacionPuestoEditorial;
    }

}
