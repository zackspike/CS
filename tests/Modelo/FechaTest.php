<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Fecha.php';

class FechaTest extends TestCase {

    public function testConstructorAndGetters() {
        $fecha = new Fecha(15, 11, 2024);

        $this->assertEquals(15, $fecha->getDia());
        $this->assertEquals(11, $fecha->getMes());
        $this->assertEquals(2024, $fecha->getAño());
    }

    public function testSetDiaValido() {
        $fecha = new Fecha(10, 5, 2020);
        $fecha->setDia(25);

        $this->assertEquals(25, $fecha->getDia());
    }

    public function testSetDiaInvalido() {
        $this->expectException(InvalidArgumentException::class);

        $fecha = new Fecha(10, 5, 2020);
        $fecha->setDia(0);
    }

    public function testSetAñoValido() {
        $fecha = new Fecha(10, 5, 2020);
        $fecha->setAño(2025);

        $this->assertEquals(2025, $fecha->getAño());
    }

    public function testSetAñoInvalido() {
        $this->expectException(InvalidArgumentException::class);

        $fecha = new Fecha(10, 5, 2020);
        $fecha->setAño(1500);
    }

    public function testSetMesValido() {
        $fecha = new Fecha(10, 1, 2024);
        $fecha->setMes(3);

        $this->assertEquals(3, $fecha->getMes());
    }

    public function testSetMesInvalidoPorDiaExcedido() {
        $this->expectException(InvalidArgumentException::class);

        $fecha = new Fecha(31, 4, 2024); 
        // Debe lanzar expeción
        $fecha->setMes(4);
    }

    public function testSetMesInvalidoFueraDeRango() {
        $this->expectException(InvalidArgumentException::class);

        $fecha = new Fecha(10, 5, 2024);
        $fecha->setMes(15); // Mes no válido
    }

    public function testSetMesFebreroBisiestoValido() {
        $fecha = new Fecha(29, 2, 2024); // 2024 es bisiesto → 29 válido
        $fecha->setMes(2);

        $this->assertEquals(2, $fecha->getMes());
    }

    public function testSetMesFebreroNoBisiestoInvalido() {
        $this->expectException(InvalidArgumentException::class);

        $fecha = new Fecha(29, 2, 2023); // No bisiesto → 29 NO debe ser válido
        $fecha->setMes(2);
    }

    public function testEsBisiesto() {
        $fecha = new Fecha(1, 1, 2024);

        $this->assertTrue($fecha->esBisiesto(2024)); // divisible entre 4
        $this->assertFalse($fecha->esBisiesto(2023)); // no bisiesto
        $this->assertTrue($fecha->esBisiesto(2000)); // divisible entre 400
        $this->assertFalse($fecha->esBisiesto(1900)); // divisible entre 100 pero no entre 400
    }

    public function testToString() {
        $fecha = new Fecha(7, 9, 2025);

        $this->assertEquals("7/9/2025", (string)$fecha);
    }
}
