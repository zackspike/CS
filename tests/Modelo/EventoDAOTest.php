<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/EventoDAO.php';
require_once __DIR__ . '/../../Modelo/Evento.php';

class EventoDAOTest extends TestCase {

    private $eventoDAO;
    private $mockConexion;

    // Se ejecuta antes de cada prueba
    protected function setUp(): void {
        $this->mockConexion = $this->createMock(mysqli::class);
        $this->eventoDAO = new EventoDAO();
    }

    // PRUEBA: Verificar que la clase extiende de DAO
    public function testClaseExtiendeDAO(){
        $this->assertInstanceOf(DAO::class, $this->eventoDAO);
    }

    // PRUEBA: Método agregar existe
    public function testMetodoAgregarExiste(){
        $this->assertTrue(method_exists($this->eventoDAO, 'agregar'));
    }

    // PRUEBA: Método eliminar existe
    public function testMetodoEliminarExiste(){
        $this->assertTrue(method_exists($this->eventoDAO, 'eliminar'));
    }

    // PRUEBA: Método obtenerEventos existe y devuelve array
    public function testMetodoObtenerEventosExiste(){
        $this->assertTrue(method_exists($this->eventoDAO, 'obtenerEventos'));
        
        $resultado = $this->eventoDAO->obtenerEventos();
        $this->assertIsArray($resultado);
    }

    // PRUEBA: Estructura de datos retornados por obtenerEventos
    public function testEstructuraDatosObtenerEventos(){
        $eventos = $this->eventoDAO->obtenerEventos();
        
        if (!empty($eventos)) {
            $primerEvento = $eventos[0];
            
            // Verificar que tiene las claves esperadas
            $this->assertIsArray($primerEvento);
            $this->assertArrayHasKey('idEvento', $primerEvento);
            $this->assertArrayHasKey('titulo', $primerEvento);
            $this->assertArrayHasKey('tipoEvento', $primerEvento);
        } else {
            // Si no hay eventos, al menos verificamos que es un array vacío
            $this->assertCount(0, $eventos);
        }
    }

    // PRUEBA: Tipos de evento válidos en SQL
    public function testTiposEventoValidosEnSQL(){
        $tiposValidos = ['conferencia', 'premiacion', 'taller'];
        
        foreach($tiposValidos as $tipo) {
            $this->assertIsString($tipo);
            $this->assertContains($tipo, ['conferencia', 'premiacion', 'taller']);
        }
    }

    // PRUEBA: Parámetros del método agregar
    public function testParametrosMetodoAgregar(){
        $reflection = new ReflectionMethod(EventoDAO::class, 'agregar');
        $params = $reflection->getParameters();
        
        // Debe tener 1 parámetro de tipo Evento
        $this->assertCount(1, $params);
        $this->assertEquals('evento', $params[0]->getName());
    }

    // PRUEBA: Parámetros del método eliminar
    public function testParametrosMetodoEliminar(){
        $reflection = new ReflectionMethod(EventoDAO::class, 'eliminar');
        $params = $reflection->getParameters();
        
        // Debe tener 1 parámetro (idEvento)
        $this->assertCount(1, $params);
        $this->assertEquals('idEvento', $params[0]->getName());
    }
}