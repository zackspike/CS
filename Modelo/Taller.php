<?php

class Taller extends Evento {
    #[\Override]
    public function __construct(int $idEvento,string $titulo,string $descripcion,string $ponente,int $numParticipantes,Fecha $fecha,Hora $horaInicio,
        Hora $horaFinal,string $tipoCupo,Categoria $categoria,Salon $ubicacion) {
        
        parent::__construct($idEvento, $titulo,$descripcion,$ponente,$numParticipantes,$fecha,$horaInicio,$horaFinal,$tipoCupo,$categoria,$ubicacion);
        
    }
}
