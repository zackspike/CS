<?php

class Evento {
    private int $idEvento;
    private string $titulo;
    private string $descripcion;
    private string $ponente;
    private int $numParticipantes;
    private Fecha $fecha;
    private Hora $horaInicio;
    private Hora $horaFinal;
    private string $tipoCupo;
    private Categoria $categoria;
    private Salon $ubicacion;
    
    public function __construct(int $idEvento, string $titulo, string $descripcion, string $ponente, int $numParticipantes, Fecha $fecha, Hora $horaInicio, Hora $horaFinal, string $tipoCupo, Categoria $categoria, Salon $ubicacion) {
        $this->idEvento = $idEvento;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->ponente = $ponente;
        $this->numParticipantes = $numParticipantes;
        $this->fecha = $fecha;
        $this->horaInicio = $horaInicio;
        $this->horaFinal = $horaFinal;
        $this->tipoCupo = $tipoCupo;
        $this->categoria = $categoria;
        $this->ubicacion = $ubicacion;
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

    public function getFecha(): Fecha {
        return $this->fecha;
    }

    public function getHoraInicio(): Hora {
        return $this->horaInicio;
    }

    public function getHoraFinal(): Hora {
        return $this->horaFinal;
    }

    public function getTipoCupo(): string {
        return $this->tipoCupo;
    }

    public function getCategoria(): Categoria {
        return $this->categoria;
    }

    public function getUbicacion(): Salon {
        return $this->ubicacion;
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

    public function setFecha(Fecha $fecha): void {
        $this->fecha = $fecha;
    }

    public function setHoraInicio(Hora $horaInicio): void {
        $this->horaInicio = $horaInicio;
    }

    public function setHoraFinal(Hora $horaFinal): void {
        $this->horaFinal = $horaFinal;
    }

    public function setTipoCupo(string $tipoCupo): void {
        $this->tipoCupo = $tipoCupo;
    }

    public function setCategoria(Categoria $categoria): void {
        $this->categoria = $categoria;
    }

    public function setUbicacion(Salon $ubicacion): void {
        $this->ubicacion = $ubicacion;
    }

    
}
