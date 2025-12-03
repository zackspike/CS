<?php

/**
 * Description of Conferencia
 *
 * @author Isaac Herrera
 */
class Conferencia extends Evento {
    private string $tipoConferencia;
    
    #[\Override]
    public function __construct(int $idEvento,string $titulo,string $descripcion,string $ponente,int $numParticipantes,string $fecha,string $horaInicio,
        string $horaFinal,string $tipoCupo,Categoria $categoria,Salon $ubicacion,string $tipoConferencia) {
        
        parent::__construct($idEvento, $titulo,$descripcion,$ponente,$numParticipantes,$fecha,$horaInicio,$horaFinal,$tipoCupo,$categoria,$ubicacion);
        $this->tipoConferencia = $tipoConferencia;
    }

    public function getTipoConferencia(): string {
        return $this->tipoConferencia;
    }

}
