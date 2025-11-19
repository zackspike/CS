<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/Evento.php';
require_once __DIR__ . '/../../Modelo/Taller.php';
require_once __DIR__ . '/../../Modelo/Fecha.php';
require_once __DIR__ . '/../../Modelo/Hora.php';
require_once __DIR__ . '/../../Modelo/Categoria.php';
require_once __DIR__ . '/../../Modelo/Salon.php';

class TallerTest extends TestCase {

    // PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters() {

        $fecha = new Fecha(20, 10, 2025);
        $horaInicio = new Hora(16, 0);
        $horaFinal = new Hora(18, 0);
        $categoria = new Categoria(2, "Taller", "Tallere");
        $ubicacion = new Salon(3, "Salon A", 30);

        $id = 200;
        $titulo = "Taller de Programación";
        $descripcion = "Taller de PHP";
        $ponente = "Persona Ponente";
        $participantes = 25;
        $tipoCupo = "limitado";

        $taller = new Taller(
            $id, $titulo, $descripcion, $ponente, $participantes, 
            $fecha, $horaInicio, $horaFinal, $tipoCupo, $categoria, $ubicacion
        );

        // No tiene getters propios pero probamos los de Evento
        $this->assertEquals($titulo, $taller->getTitulo());
        $this->assertEquals($ponente, $taller->getPonente());
        $this->assertEquals(25, $taller->getNumParticipantes());
        $this->assertEquals(2025, $taller->getFecha()->getAño());
        $this->assertEquals("Salon A", $taller->getUbicacion()->getNombreSalon());
    }

    // PRUEBA SETTERS
    public function testSetters() {
        $fecha = new Fecha(1, 1, 2024);
        $hora = new Hora(9, 0);
        $cat = new Categoria(2, "Test", "Desc");
        $salon = new Salon(1, "Salon B", 20);

        $taller = new Taller(
            2, "Titulo Original", "Desc", "Ponente Original", 10, 
            $fecha, $hora, $hora, "Libre", $cat, $salon
        );

        // Tampoco tiene setters propios pero usamos los de Evento
        $nuevoTitulo = "Titulo Taller Actualizado";
        $taller->setTitulo($nuevoTitulo);

        // REVISAR QUE HUBIERON CAMBIOS CORRECTAMENTE
        $this->assertEquals($nuevoTitulo, $taller->getTitulo());

        // REVISAR QUE LOS DEMAS NO CAMBIEN
        $this->assertEquals("Ponente Original", $taller->getPonente());
        $this->assertEquals(10, $taller->getNumParticipantes());
    }
}