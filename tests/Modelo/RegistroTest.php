<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/Registro.php';
require_once __DIR__ . '/../../Modelo/Fecha.php';

class RegistroTest extends TestCase {

    // PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters(){

        $idRegistro = 1;
        $idUsuario = 'nuevoUsuario';
        $fecha = new Fecha(18, 11, 2025);
        $asistio = true;

        $registro = new Registro($idRegistro, $idUsuario, $fecha, $asistio);

        // PRUEBA DE LOS GETTERS
        $this->assertEquals($idRegistro, $registro->getIdRegistro());
        $this->assertEquals($idUsuario, $registro->getIdUsuario());
        $this->assertSame($fecha, $registro->getFechaRegistro());
        $this->assertTrue($registro->getAsistio());
    }

    // PRUEBA SETTERS
    public function testSetters(){
        $fechaOriginal = new Fecha(10, 10, 2024);
        $registro = new Registro(1, 'usuarioOriginal', $fechaOriginal, false);

        //Declarar los datos a modificar
        $nuevoIdUsuario = "UsuarioModificado";
        $nuevaFecha = new Fecha(12, 12, 2025);
        $nuevaAsistencia = true;
        
        $registro->setIdUsuario($nuevoIdUsuario);
        $registro->setFechaRegistro($nuevaFecha);
        $registro->setAsistio($nuevaAsistencia);

        //VERIFICAR QUE SE HICIERON LOS CAMBIOS
        $this->assertEquals($nuevoIdUsuario, $registro->getIdUsuario());
        $this->assertSame($nuevaFecha, $registro->getFechaRegistro());
        $this->assertTrue($registro->getAsistio());

        //VERIFICAR QUE LOS DATOS NO MODIFICADOS SE CONSERVEN
        $this->assertEquals(1, $registro->getIdRegistro());
    }
}