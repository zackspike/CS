<?php

class Evento {
    private int $idEvento;
    private string $titulo;
    private string $descripcion;
    private string $ponente;
    private int $numParticipantes;
    private string $fecha;
    private string $horaInicio;
    private string $horaFinal;
    private string $tipoCupo;
    private string $tipoEvento;
    private int $idCategoria;
    private int $idSalon;
    private string $imagen;
    
    private ?string $tipoConferencia = null;
    private ?string $ganadorPremiacion = null;
    
    public function __construct(int $idEvento, string $titulo, string $descripcion, string $ponente, int $numParticipantes,
            String $fecha, String $horaInicio, String $horaFinal, string $tipoCupo,string $tipoEvento, int $idCategoria,
            int $idSalon, string $imagen) {
        $this->idEvento = $idEvento;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->ponente = $ponente;
        $this->numParticipantes = $numParticipantes;
        $this->fecha = $fecha;
        $this->horaInicio = $horaInicio;
        $this->horaFinal = $horaFinal;
        $this->tipoCupo = $tipoCupo;
        $this->tipoEvento= $tipoEvento;
        $this->idCategoria = $idCategoria;
        $this->idSalon = $idSalon;
        $this->imagen = $imagen;
    }

        public function getIdEvento(): int {
        return $this->idEvento;
    }

    public function getTitulo(): string {
        return $this->titulo;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getPonente(): string {
        return $this->ponente;
    }

    public function getNumParticipantes(): int {
        return $this->numParticipantes;
    }

    public function getFecha(): string {
        return $this->fecha;
    }

    public function getHoraInicio(): string {
        return $this->horaInicio;
    }

    public function getHoraFinal(): string {
        return $this->horaFinal;
    }

    public function getTipoCupo(): string {
        return $this->tipoCupo;
    }

    public function getIdCategoria(): int {
        return $this->idCategoria;
    }

    public function getIdSalon(): int {
        return $this->idSalon;
    }

    public function getImagen(): string {
        return $this->imagen;
    }

    public function setImagen(string $imagen): void {
        $this->imagen = $imagen;
    }

        public function setIdEvento(int $idEvento): void {
        $this->idEvento = $idEvento;
    }

    public function setTitulo(string $titulo): void {
        $this->titulo = $titulo;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setPonente(string $ponente): void {
        $this->ponente = $ponente;
    }

    public function setNumParticipantes(int $numParticipantes): void {
        $this->numParticipantes = $numParticipantes;
    }

    public function setFecha(string $fecha): void {
        $this->fecha = $fecha;
    }

    public function setHoraInicio(string $horaInicio): void {
        $this->horaInicio = $horaInicio;
    }

    public function setHoraFinal(string $horaFinal): void {
        $this->horaFinal = $horaFinal;
    }

    public function setTipoCupo(string $tipoCupo): void {
        $this->tipoCupo = $tipoCupo;
    }

    public function setIdCategoria(int $idCategoria): void {
        $this->idCategoria = $idCategoria;
    }

    public function setIdSalon(int $idSalon): void {
        $this->idSalon = $idSalon;
    }
    public function getTipoEvento(): string {
        return $this->tipoEvento;
    }

    public function setTipoEvento(string $tipoEvento): void {
        $this->tipoEvento = $tipoEvento;
    }

    public function getTipoConferencia(): ?string {
        return $this->tipoConferencia;
    }

    public function getGanadorPremiacion(): ?string {
        return $this->ganadorPremiacion;
    }

    public function setTipoConferencia(?string $tipoConferencia): void {
        $this->tipoConferencia = $tipoConferencia;
    }

    public function setGanadorPremiacion(?string $ganadorPremiacion): void {
        $this->ganadorPremiacion = $ganadorPremiacion;
    }
}
