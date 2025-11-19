<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/Evento.php';
require_once __DIR__ . '/../../Modelo/Premiacion.php';
require_once __DIR__ . '/../../Modelo/Fecha.php';
require_once __DIR__ . '/../../Modelo/Hora.php';
require_once __DIR__ . '/../../Modelo/Categoria.php';
require_once __DIR__ . '/../../Modelo/Salon.php';

class PremiacionTest extends TestCase {

    // PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters() {


        $fecha = new Fecha(15, 6, 2025);
        $horaInicio = new Hora(10, 0);
        $horaFinal = new Hora(12, 0);
        $categoria = new Categoria(1, "Premiacion", "Premiacion");
        $ubicacion = new Salon(5, "Salon A", 200);

        $id = 100;
        $titulo = "Premiacion";
        $descripcion = "Entrega de premios";
        $ponente = "Persona Ponente";
        $participantes = 150;
        $tipoCupo = "limitado";
        $ganador = "Nombre";

        $premiacion = new Premiacion(
            $id, $titulo, $descripcion, $ponente, $participantes, 
            $fecha, $horaInicio, $horaFinal, $tipoCupo, $categoria, $ubicacion, 
            $ganador
        );


        $this->assertEquals($ganador, $premiacion->getGanadorPremiacion());
        $this->assertEquals($titulo, $premiacion->getTitulo());
        $this->assertEquals(150, $premiacion->getNumParticipantes());
        $this->assertEquals(2025, $premiacion->getFecha()->getAño());
        $this->assertEquals("Salon A", $premiacion->getUbicacion()->getNombreSalon());
    }

    // PRUEBA SETTERS
    public function testSetters() {
        $fecha = new Fecha(1, 1, 2024);
        $hora = new Hora(9, 0);
        $cat = new Categoria(2, "Test", "Desc");
        $salon = new Salon(1, "Sala A", 50);

        $premiacion = new Premiacion(
            1, "Titulo Original", "Desc", "Ponente", 10, 
            $fecha, $hora, $hora, "Libre", $cat, $salon, 
            "Ganador Anterior"
        );

        $nuevoGanador = "Ganador Nuevo";
        $premiacion->setGanadorPremiacion($nuevoGanador);

        //REVISAR QUE HUBIERON CAMBIOS CORRECTAMENTE
        $this->assertEquals($nuevoGanador, $premiacion->getGanadorPremiacion());

        //REVISAR QUE LOS DEMAS NO CAMBIEN
        $this->assertEquals("Titulo Original", $premiacion->getTitulo());
    }
}