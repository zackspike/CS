<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Salon.php';

class SalonTest extends TestCase {

    public function testConstructorAndGetters() {
        $salon = new Salon(1, "Auditorio Central", 200);

        $this->assertEquals(1, $salon->getIdSalon());
        $this->assertEquals("Auditorio Central", $salon->getNombreSalon());
        $this->assertEquals(200, $salon->getMaxCapacidad());
    }

    public function testSetIdSalon() {
        $salon = new Salon(1, "Aula 1", 30);

        $salon->setIdSalon(10);
        $this->assertEquals(10, $salon->getIdSalon());
    }

    public function testSetNombreSalon() {
        $salon = new Salon(1, "Aula 1", 30);

        $salon->setNombreSalon("Laboratorio de Computo");
        $this->assertEquals("Laboratorio de Computo", $salon->getNombreSalon());
    }

    public function testSetMaxCapacidad() {
        $salon = new Salon(1, "Aula 1", 30);

        $salon->setMaxCapacidad(50);
        $this->assertEquals(50, $salon->getMaxCapacidad());
    }
}
