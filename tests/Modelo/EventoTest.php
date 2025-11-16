<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Evento.php';

class EventoTest extends TestCase {

    //PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters(){


        $titulo = 'Presentación Jesús Tec: Real Life';
        $horaI = "12:00";
        $horaF = "13:30";
        $fecha = "18/11/2025";

        $evento = new Evento($titulo,$horaI,$horaF,$fecha);

        $this->assertEquals($titulo, $evento->getTituloEvento());
        $this->assertEquals($horaI, $evento->getHoraInicio());
        $this->assertEquals($horaF, $evento->getHoraFinal());
        $this->assertEquals($fecha, $evento->getFecha());
    }

    //PRUEBA SETTERS
    public function testSetters(){
        $evento = new Evento("Cats and More", "13:30", "15:00", "18/11/2025");

        $nuevoNombre = "Ahora es de perros";
        $nuevaHoraFinal = "15:30";

        $evento->setTituloEvento($nuevoNombre);
        $evento->setHoraFinal($nuevaHoraFinal);

        //REVISAR QUE HUBIERON CAMBIOS CORRECTAMENTE
        $this->assertEquals($nuevoNombre, $evento->getTituloEvento());
        $this->assertEquals($nuevaHoraFinal, $evento->getHoraFinal());

        //REVISAR QUE LOS DEMAS NO CAMBIEN
        $this->assertEquals("13:30", $evento->getHoraInicio());
        $this->assertEquals("18/11/2025", $evento->getFecha());

    }
}
