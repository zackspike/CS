<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/RegistroDAO.php';
require_once __DIR__ . '/../../Modelo/Registro.php';

class RegistroTest extends TestCase {
    
    // constructor y getters
    public function testConstructorYGetters(){
        $idRegistro = 1;
        $idUsuario = 101;
        $fechaRegistro = '2025-11-18';
        $asistio = true;
        $idEvento = 5;
        
        $registro = new Registro($idRegistro, $fechaRegistro, $asistio, $idUsuario, $idEvento);
        
        $this->assertEquals($idRegistro, $registro->getIdRegistro());
        $this->assertEquals($idUsuario, $registro->getIdUsuario());
        $this->assertEquals($fechaRegistro, $registro->getFechaRegistro());
        $this->assertTrue($registro->getAsistio());
        $this->assertEquals($idEvento, $registro->getIdEvento());
    }
    
    // setters
    public function testSetters(){
        $registro = new Registro(1, '2024-10-10', false, 100, 3);
        
        $nuevoIdUsuario = 200;
        $nuevaFecha = '2025-12-12';
        $nuevaAsistencia = true;
        
        $registro->setIdUsuario($nuevoIdUsuario);
        $registro->setFechaRegistro($nuevaFecha);
        $registro->setAsistio($nuevaAsistencia);
        
        $this->assertEquals($nuevoIdUsuario, $registro->getIdUsuario());
        $this->assertEquals($nuevaFecha, $registro->getFechaRegistro());
        $this->assertTrue($registro->getAsistio());
    }
    
    // Detalles de evento
    public function testDetallesEvento(){
        $registro = new Registro(1, '2025-11-18', true, 101, 5);
        
        $registro->setDetallesEvento('Conferencia PHP', '2025-12-01', '10:00', 'Auditorio A', 'Conferencia');
        
        $this->assertEquals('Conferencia PHP', $registro->getTituloEvento());
        $this->assertEquals('2025-12-01', $registro->getFechaEvento());
        $this->assertEquals('10:00', $registro->getHoraInicio());
        $this->assertEquals('Auditorio A', $registro->getNombreSalon());
        $this->assertEquals('Conferencia', $registro->getTipoEvento());
    }
    
    // Valores nulos
    public function testValoresInicialesNulos(){
        $registro = new Registro(1, '2025-11-18', true, 101, 5);
        
        $this->assertNull($registro->getTituloEvento());
        $this->assertNull($registro->getFechaEvento());
        $this->assertNull($registro->getNombreSalon());
    }
    
    // Modificar Id del evento
    public function testModificarIdEvento(){
        $registro = new Registro(1, '2025-11-18', true, 101, 5);
        
        $nuevoIdEvento = 10;
        $registro->setIdEvento($nuevoIdEvento);
        
        $this->assertEquals($nuevoIdEvento, $registro->getIdEvento());
    }
}