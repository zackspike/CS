<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Evento.php';

class EventoTest extends TestCase {

    // PRUEBA DE CONSTRUCTOR Y GETTERS BÁSICOS
    public function testConstructorYGetters(){
        $idEvento = 1;
        $titulo = "Conferencia de Literatura";
        $descripcion = "Una conferencia sobre literatura contemporánea";
        $ponente = "Juan Pérez";
        $numParticipantes = 50;
        $fecha = "2025-03-15";
        $horaInicio = "10:00:00";
        $horaFinal = "12:00:00";
        $tipoCupo = "Limitado";
        $tipoEvento = "conferencia";
        $idCategoria = 1;
        $idSalon = 1;
        $imagen = "conferencia.jpg";

        $evento = new Evento(
            $idEvento, $titulo, $descripcion, $ponente, $numParticipantes,
            $fecha, $horaInicio, $horaFinal, $tipoCupo, $tipoEvento, 
            $idCategoria, $idSalon, $imagen
        );

        $this->assertEquals($idEvento, $evento->getIdEvento());
        $this->assertEquals($titulo, $evento->getTitulo());
        $this->assertEquals($ponente, $evento->getPonente());
        $this->assertEquals($tipoEvento, $evento->getTipoEvento());
        $this->assertEquals($tipoCupo, $evento->getTipoCupo());
    }

    // PRUEBA SETTERS BÁSICOS
    public function testSetters(){
        $evento = new Evento(
            1, "Título Anterior", "Descripción", "Ponente", 50,
            "2025-03-15", "10:00:00", "12:00:00", "Limitado", 
            "conferencia", 1, 1, "imagen.jpg"
        );

        $evento->setTitulo("Título Actualizado");
        $evento->setPonente("Nuevo Ponente");
        $evento->setTipoEvento("taller");

        $this->assertEquals("Título Actualizado", $evento->getTitulo());
        $this->assertEquals("Nuevo Ponente", $evento->getPonente());
        $this->assertEquals("taller", $evento->getTipoEvento());
    }

    // PRUEBA PARA CAMPOS OPCIONALES (nullable)
    public function testCamposOpcionales(){
        $evento = new Evento(
            1, "Título", "Descripción", "Ponente", 50,
            "2025-03-15", "10:00:00", "12:00:00", "Limitado", 
            "conferencia", 1, 1, "imagen.jpg"
        );

        // Al inicio deben ser null
        $this->assertNull($evento->getTipoConferencia());
        $this->assertNull($evento->getGanadorPremiacion());

        // Establecer valores
        $evento->setTipoConferencia("Presentación de libro");
        $evento->setGanadorPremiacion("María García");

        $this->assertEquals("Presentación de libro", $evento->getTipoConferencia());
        $this->assertEquals("María García", $evento->getGanadorPremiacion());
    }

    // PRUEBA PARA VALIDAR TIPOS DE EVENTO
    public function testTiposEventoValidos(){
        $evento = new Evento(
            1, "Título", "Descripción", "Ponente", 50,
            "2025-03-15", "10:00:00", "12:00:00", "Limitado", 
            "conferencia", 1, 1, "imagen.jpg"
        );
        
        $this->assertEquals("conferencia", $evento->getTipoEvento());
        
        $evento->setTipoEvento("taller");
        $this->assertEquals("taller", $evento->getTipoEvento());
        
        $evento->setTipoEvento("premiacion");
        $this->assertEquals("premiacion", $evento->getTipoEvento());
    }

    // PRUEBA PARA NÚMERO DE PARTICIPANTES
    public function testNumeroParticipantes(){
        $evento = new Evento(
            1, "Título", "Descripción", "Ponente", 100,
            "2025-03-15", "10:00:00", "12:00:00", "Limitado", 
            "conferencia", 1, 1, "imagen.jpg"
        );
        
        $this->assertEquals(100, $evento->getNumParticipantes());
        
        $evento->setNumParticipantes(150);
        $this->assertEquals(150, $evento->getNumParticipantes());
    }
}