<?php
class Registro {
    private int $idRegistro;
    private string $fechaRegistro;
    private bool $asistio;
    private int $idUsuario;
    private int $idEvento;
    
    //Detalles del evento en el que se registra el usuario
    private ?string $tituloEvento = null;
    private ?string $fechaEvento = null;
    private ?string $nombreSalon = null;
    private ?string $horaInicio = null;
    private ?string $tipoEvento = null;
    
    public function __construct(?int $idRegistro, string $fechaRegistro, bool $asistio, int $idUsuario, int $idEvento) {
        $this->idRegistro = $idRegistro;
        $this->fechaRegistro = $fechaRegistro;
        $this->asistio = $asistio;
        $this->idUsuario = $idUsuario;
        $this->idEvento = $idEvento;
    }

    public function getIdRegistro(): int {
        return $this->idRegistro;
    }

    public function getIdUsuario(): int{
        return $this->idUsuario;
    }

    public function getFechaRegistro(): string {
        return $this->fechaRegistro;
    }

    public function getAsistio(): bool {
        return $this->asistio;
    }

    public function setIdRegistro(int $idRegistro): void {
        $this->idRegistro = $idRegistro;
    }

    public function setIdUsuario(int $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function setFechaRegistro(string $fechaRegistro): void {
        $this->fechaRegistro = $fechaRegistro;
    }

    public function setAsistio(bool $asistio): void {
        $this->asistio = $asistio;
    }

    public function getIdEvento(): int {
        return $this->idEvento;
    }

    public function setIdEvento(int $idEvento): void {
        $this->idEvento = $idEvento;
    }

    public function setDetallesEvento(string $titulo, string $fecha, string $horaInicio, string $salon, string $tipo): void {
        $this->tituloEvento = $titulo;
        $this->fechaEvento = $fecha;
        $this->horaInicio = $horaInicio;
        $this->nombreSalon = $salon;
        $this->tipoEvento = $tipo;
    }
    
    public function getTituloEvento(): ?string {
        return $this->tituloEvento;
    }

    public function getFechaEvento(): ?string {
        return $this->fechaEvento;
    }

    public function getNombreSalon(): ?string {
        return $this->nombreSalon;
    }

    public function getHoraInicio(): ?string {
        return $this->horaInicio;
    }

    public function getTipoEvento(): ?string {
        return $this->tipoEvento;
    }

}
