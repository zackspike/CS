<?php

class Salon {
    private int $idSalon;
    private string $nombreSalon;
    private int $maxCapacidad;
    
    public function __construct(int $idSalon, string $nombreSalon, int $maxCapacidad) {
        $this->idSalon = $idSalon;
        $this->nombreSalon = $nombreSalon;
        $this->maxCapacidad = $maxCapacidad;
    }

    public function getIdSalon(): int {
        return $this->idSalon;
    }

    public function getNombreSalon(): string {
        return $this->nombreSalon;
    }

    public function getMaxCapacidad(): int {
        return $this->maxCapacidad;
    }

    public function setIdSalon(int $idSalon): void {
        $this->idSalon = $idSalon;
    }

    public function setNombreSalon(string $nombreSalon): void {
        $this->nombreSalon = $nombreSalon;
    }

    public function setMaxCapacidad(int $maxCapacidad): void {
        $this->maxCapacidad = $maxCapacidad;
    }

}
