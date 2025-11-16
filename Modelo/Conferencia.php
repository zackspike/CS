<?php

/**
 * Description of Conferencia
 *
 * @author Gabriela Puch
 */
class Conferencia {
    private $idConferencia;
    private $tituloConferencia;
    private $ponenteConferencia;
    private $tipo;
    private $numParticipantes;
    private $horarioConferencia;
    
    public function __construct($idConferencia, $tituloConferencia, $ponenteConferencia, $tipo, $numParticipantes, $horario) {
        $this->idConferencia = $idConferencia;
        $this->tituloConferencia = $tituloConferencia;
        $this->ponenteConferencia = $ponenteConferencia;
        $this->tipo = $tipo;
        $this->numParticipantes = $numParticipantes;
        $this->horarioConferencia = $horario;
    }
    
    public function getIdConferencia() {
        return $this->idConferencia;
    }

    public function getTituloConferencia() {
        return $this->tituloConferencia;
    }

    public function getPonenteConferencia() {
        return $this->ponenteConferencia;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getNumParticipantes() {
        return $this->numParticipantes;
    }

    public function getHorario() {
        return $this->horarioConferencia;
    }

    public function setIdConferencia($idConferencia): void {
        $this->idConferencia = $idConferencia;
    }

    public function setTituloConferencia($tituloConferencia): void {
        $this->tituloConferencia = $tituloConferencia;
    }

    public function setPonenteConferencia($ponenteConferencia): void {
        $this->ponenteConferencia = $ponenteConferencia;
    }

    public function setTipo($tipo): void {
        $this->tipo = $tipo;
    }

    public function setNumParticipantes($numParticipantes): void {
        $this->numParticipantes = $numParticipantes;
    }

    public function setHorario($horarioConferencia): void {
        $this->horarioConferencia = $horarioConferencia;
    }
}
