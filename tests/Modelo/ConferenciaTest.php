<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Conferencia.php';

class ConferenciaTest extends TestCase{

    //PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters(){


        $id = 1;
        $titulo = "Conferencia de Huracanes";
        $ponente = "Karina Puch";
        $tipo = "Taller";
        $participantes = 50;
        $horario = "10:00 AM";

        $conferencia = new Conferencia($id,$titulo,$ponente,$tipo,$participantes,$horario);

        $this->assertEquals($id, $conferencia->getIdConferencia());
        $this->assertEquals($titulo, $conferencia->getTituloConferencia());
        $this->assertEquals($ponente, $conferencia->getPonenteConferencia());
        $this->assertEquals($tipo, $conferencia->getTipo());
        $this->assertEquals($participantes, $conferencia->getNumParticipantes());
        $this->assertEquals($horario, $conferencia->getHorario());
    }

    //PRUEBA SETTERS
    public function testSetters(){
        $conferencia = new Conferencia(1, "Conferencia anterior", "Ponente anterior", "Tipo anterior", 10, "08:00");

        $nuevoTitulo = "Titulo actualizado";
        $nuevoPonente = "Ponente actualizado";

        $conferencia->setTituloConferencia($nuevoTitulo);
        $conferencia->setPonenteConferencia($nuevoPonente);

        //REVISAR QUE HUBIERON CAMBIOS CORRECTAMENTE
        $this->assertEquals($nuevoTitulo, $conferencia->getTituloConferencia());
        $this->assertEquals($nuevoPonente, $conferencia->getPonenteConferencia());

        //REVISAR QUE LOS DEMAS NO CAMBIEN
        $this->assertEquals(1, $conferencia->getIdConferencia());
        $this->assertEquals(10, $conferencia->getNumParticipantes());

    }
}