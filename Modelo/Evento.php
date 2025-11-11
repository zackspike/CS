<?php
/**
 * Description of Evento
 *
 * @author Gabriela Puch
 */
class Evento implements Categoria{
    private $tituloEvento;
    private $horaInicio;
    private $horaFinal;
    private $fecha;
    
    public function __construct($tituloEvento, $horaInicio, $horaFinal, $fecha) {
        $this->tituloEvento = $tituloEvento;
        $this->horaInicio = $horaInicio;
        $this->horaFinal = $horaFinal;
        $this->fecha = $fecha;
    }

    public function getTituloEvento() {
        return $this->tituloEvento;
    }

    public function getHoraInicio() {
        return $this->horaInicio;
    }

    public function getHoraFinal() {
        return $this->horaFinal;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function setTituloEvento($tituloEvento): void {
        $this->tituloEvento = $tituloEvento;
    }

    public function setHoraInicio($horaInicio): void {
        $this->horaInicio = $horaInicio;
    }

    public function setHoraFinal($horaFinal): void {
        $this->horaFinal = $horaFinal;
    }

    public function setFecha($fecha): void {
        $this->fecha = $fecha;
    }
    
    public function tipoCategoria(): string {
        return "Evento";
    }

}
