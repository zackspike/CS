<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/RegistroDAO.php';
require_once __DIR__ . '/../../Modelo/Registro.php';

class RegistroDAOTest extends TestCase {
    private $registroDAO;
    private $mockConexion;
    private $mockStatement;
    
    protected function setUp(): void {
        $this->mockConexion = $this->createMock(mysqli::class);
        $this->mockStatement = $this->createMock(mysqli_stmt::class);
        
        $this->registroDAO = new RegistroDAO();
        $reflection = new ReflectionClass($this->registroDAO);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        $property->setValue($this->registroDAO, $this->mockConexion);
    }
    
    // Verificar si un usuario está inscrito
    public function testInscribirFallo() {
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ii', 20, 5);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(false);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $resultado = $this->registroDAO->inscribir(20, 5);
        $this->assertFalse($resultado);
    }
    
    // Inscribir usuario
    public function testInscribir() {
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ii', 10, 3);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $resultado = $this->registroDAO->inscribir(10, 3);
        $this->assertTrue($resultado);
    }
    
    // Validar asistencia
    public function testValidarAsistencia() {
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', 7);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $resultado = $this->registroDAO->validarAsistencia(7);
        $this->assertTrue($resultado);
    }
    
    // Cancelar registro
    public function testCancelar() {
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', 15);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $resultado = $this->registroDAO->cancelar(15);
        $this->assertTrue($resultado);
    }
    
    // obtener por Id que no existe
    public function testObtenerPorIdNoExistente() {
        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(null);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', 999);
        
        $this->mockStatement->expects($this->once())
            ->method('execute');
        
        $this->mockStatement->expects($this->once())
            ->method('get_result')
            ->willReturn($mockResult);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $resultado = $this->registroDAO->obtenerPorId(999);
        $this->assertNull($resultado);
    }
}