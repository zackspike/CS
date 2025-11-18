<?php
class Registro {
    private int $idRegistro;
    private string $idUsuario;
    private Fecha $fechaRegistro;
    private bool $asistio;
    
    public function __construct(int $idRegistro, string $idUsuario, Fecha $fechaRegistro, bool $asistio) {
        $this->idRegistro = $idRegistro;
        $this->idUsuario = $idUsuario;
        $this->fechaRegistro = $fechaRegistro;
        $this->asistio = $asistio;
    }

    public function getIdRegistro(): int {
        return $this->idRegistro;
    }

    public function getIdUsuario(): string {
        return $this->idUsuario;
    }

    public function getFechaRegistro(): Fecha {
        return $this->fechaRegistro;
    }

    public function getAsistio(): bool {
        return $this->asistio;
    }

    public function setIdRegistro(int $idRegistro): void {
        $this->idRegistro = $idRegistro;
    }

    public function setIdUsuario(string $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function setFechaRegistro(Fecha $fechaRegistro): void {
        $this->fechaRegistro = $fechaRegistro;
    }

    public function setAsistio(bool $asistio): void {
        $this->asistio = $asistio;
    }



}
