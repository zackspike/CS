<?php

/**
 * Description of Conferencia
 *
 * @author Isaac Herrera
 */
class Conferencia extends Evento {
    private string $tipoConferencia;
    
    #[\Override]
    public function __construct(int $idEvento,string $titulo,string $descripcion,string $ponente,int $numParticipantes,Fecha $fecha,Hora $horaInicio,
        Hora $horaFinal,string $tipoCupo,Categoria $categoria,Salon $ubicacion,sring $tipoConferencia) {
        
        parent::__construct($idEvento, $titulo,$descripcion,$ponente,$numParticipantes,$fecha,$horaInicio,$horaFinal,$tipoCupo,$categoria,$ubicacion);
        $this->tipoConferencia = $tipoConferencia;
    }

    public function getTipoConferencia(): string {
        return $this->tipoConferencia;
    }

    public function setTipoConferencia(string $tipoConferencia): void {
        $this->tipoConferencia = $tipoConferencia;
    }



    


    
}
