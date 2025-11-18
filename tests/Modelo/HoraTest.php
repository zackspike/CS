<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Hora.php';

class HoraTest extends TestCase {
    public function testConstructorYGetters(){
        $hora = new Hora(hora: 16, minutos:30 );

        $this->assertEquals(16, $hora->getHora());
        $this->assertEquals(30, $hora->getMinutos());

    }

    public function testSetHoraValida() {
        $hora = new Hora(10, 20);

        $hora->setHora(23);
        $this->assertEquals(23, $hora->getHora());
    }

    public function testSetHoraInvalidaMayor() {
        $this->expectException(InvalidArgumentException::class);

        $hora = new Hora(10, 20);
        $hora->setHora(26); //Hora inválida
    }

    public function testSetHoraInvalidaMenor() {
        $this->expectException(InvalidArgumentException::class);

        $hora = new Hora(10, 20);
        $hora->setHora(-1); // Hora inválida
    }

    public function testSetMinutosValido() {
        $hora = new Hora(10, 20);

        $hora->setMinutos(59); // Minutos válidos
        $this->assertEquals(59, $hora->getMinutos());
    }

    public function testSetMinutosInvalidosMayores() {
        $this->expectException(InvalidArgumentException::class);

        $hora = new Hora(10, 20);
        $hora->setMinutos(60); // Minutos inválidos
    }

    public function testSetMinutosInvalidosMenores() {
        $this->expectException(InvalidArgumentException::class);

        $hora = new Hora(10, 20);
        $hora->setMinutos(-5); // Minutos inválidos
    }

    public function testToString() {
        $hora = new Hora(9, 5); // Prueba con números de un dígito
        
        // Comprobar que se pasa el formato a "09:05"
        $this->assertEquals("09:05", (string)$hora);

        $hora2 = new Hora(23, 45);
        $this->assertEquals("23:45", (string)$hora2);
    }
}
