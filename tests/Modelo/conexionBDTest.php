<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/conexionBD.php';

class ConexionBDTest extends TestCase {

    public function testConectarExitosamente() {
        // Creamos un MOCK de mysqli
        $mysqliMock = $this->createMock(mysqli::class);

        $mysqliMock->connect_errno = 0;
        
        $mysqliMock->method('set_charset')->willReturn(true);

        // Creamos un stub de la clase para reemplazar new mysqli(...)
        $conexion = $this->getMockBuilder(ConexionBD::class)
            ->onlyMethods(['crearMysqli'])
            ->getMock();

        $conexion->method('crearMysqli')->willReturn($mysqliMock);

        $resultado = $conexion->conectar();

        $this->assertInstanceOf(mysqli::class, $resultado);
    }

    public function testConectarFalla() {
        $this->expectException(Error::class);

        $mysqliMock = $this->createMock(mysqli::class);

        // Forzar error de conexión
        $mysqliMock->connect_errno = 2002;

        $conexion = $this->getMockBuilder(ConexionBD::class)
            ->onlyMethods(['crearMysqli'])
            ->getMock();

        $conexion->method('crearMysqli')->willReturn($mysqliMock);

        // Esto debería lanzar un error porque la clase usa "die()"
        $conexion->conectar();
    }
}
