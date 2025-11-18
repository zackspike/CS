<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Constancia.php';
require_once __DIR__ . '/../../Modelo/Fecha.php';

class ConstanciaTest extends TestCase {

    private Fecha $fecha;

    protected function setUp(): void {
        // CREAMOS UN OBJETO FECHA PARA LAS PRUEBAS
        $this->fecha = new Fecha(8, 11, 2025);
    }

    public function testConstructorAndGetters() {
        $constancia = new Constancia(1, 12345, $this->fecha, "ABC123");

        $this->assertEquals(1, $constancia->getIdConstancia());
        $this->assertEquals(12345, $constancia->getRegistro());
        $this->assertSame($this->fecha, $constancia->getFechaEmision());
        $this->assertEquals("ABC123", $constancia->getCodigoVerificacion());
    }

    public function testSetters() {
        $constancia = new Constancia(1, 12345, $this->fecha, "ABC123");

        // NUEVOS VALORES PARA LA FECHA
        $newFecha = new Fecha(22, 12, 2024);

        $constancia->setIdConstancia(10);
        $constancia->setRegistro(54321);
        $constancia->setFechaEmision($newFecha);
        $constancia->setCodigoVerificacion("XYZ789");

        $this->assertEquals(10, $constancia->getIdConstancia());
        $this->assertEquals(54321, $constancia->getRegistro());
        $this->assertSame($newFecha, $constancia->getFechaEmision());
        $this->assertEquals("XYZ789", $constancia->getCodigoVerificacion());
    }

}