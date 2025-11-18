<?php

class Constancia {
    private int $idConstancia;
    private int $registro;
    private Fecha $fechaEmision;
    private string $codigoVerificacion;
    
    public function __construct(int $idConstancia, int $registro, Fecha $fechaEmision, string $codigoVerificacion) {
        $this->idConstancia = $idConstancia;
        $this->registro = $registro;
        $this->fechaEmision = $fechaEmision;
        $this->codigoVerificacion = $codigoVerificacion;
    }

    public function getIdConstancia(): int {
        return $this->idConstancia;
    }

    public function getRegistro(): int {
        return $this->registro;
    }

    public function getFechaEmision(): Fecha {
        return $this->fechaEmision;
    }

    public function getCodigoVerificacion(): string {
        return $this->codigoVerificacion;
    }

    public function setIdConstancia(int $idConstancia): void {
        $this->idConstancia = $idConstancia;
    }

    public function setRegistro(int $registro): void {
        $this->registro = $registro;
    }

    public function setFechaEmision(Fecha $fechaEmision): void {
        $this->fechaEmision = $fechaEmision;
    }

    public function setCodigoVerificacion(string $codigoVerificacion): void {
        $this->codigoVerificacion = $codigoVerificacion;
    }


}
