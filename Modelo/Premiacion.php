<?php

class Premiacion extends Evento{
    private string $ganadorPremiacion;

    #[\Override]
    public function __construct(int $idEvento,string $titulo,string $descripcion,string $ponente,int $numParticipantes,Fecha $fecha,Hora $horaInicio,
        Hora $horaFinal,string $tipoCupo,Categoria $categoria,Salon $ubicacion,string $ganadorPremiacion) {
        
        parent::__construct($idEvento, $titulo,$descripcion,$ponente,$numParticipantes,$fecha,$horaInicio,$horaFinal,$tipoCupo,$categoria,$ubicacion);
        $this->ganadorPremiacion = $ganadorPremiacion;
    }

    public function getGanadorPremiacion() {
        return $this->ganadorPremiacion;
    }

    public function setGanadorPremiacion($ganadorPremiacion): void {
        $this->ganadorPremiacion = $ganadorPremiacion;
    }

}
