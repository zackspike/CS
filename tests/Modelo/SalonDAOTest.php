<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/SalonDAO.php';
require_once __DIR__ . '/../../Modelo/Salon.php';

class SalonDAOTest extends TestCase
{
    private SalonDAO $dao;
    private $mockConexion;
    private $mockStatement;
    
    protected function setUp(): void
    {
        $this->mockConexion = $this->createMock(mysqli::class);
        $this->mockStatement = $this->createMock(mysqli_stmt::class);
        
        $this->dao = new SalonDAO();
        
        // inyectar mock
        $reflection = new ReflectionClass($this->dao);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        $property->setValue($this->dao, $this->mockConexion);
    }
    
    /**
     * @test
     */
    public function testConstructor()
    {
        $dao = new SalonDAO();
        
        $reflection = new ReflectionClass($dao);
        
        $propTabla = $reflection->getProperty('nombreTabla');
        $propTabla->setAccessible(true);
        $this->assertEquals('Salones', $propTabla->getValue($dao));
        
        $propId = $reflection->getProperty('nombreId');
        $propId->setAccessible(true);
        $this->assertEquals('idSalon', $propId->getValue($dao));
    }
    
    /**
     * @test
     */
    public function testMapear()
    {
        $fila = [
            'idSalon' => '5',
            'nombreSalon' => 'Auditorio',
            'maxCapacidad' => 300
        ];
        
        $reflection = new ReflectionClass($this->dao);
        $method = $reflection->getMethod('mapear');
        $method->setAccessible(true);
        
        $salon = $method->invoke($this->dao, $fila);
        
        $this->assertInstanceOf(Salon::class, $salon);
        $this->assertEquals(5, $salon->getIdSalon());
        $this->assertEquals('Auditorio', $salon->getNombreSalon());
        $this->assertEquals(300, $salon->getMaxCapacidad());
    }
    
    /**
     * @test
     */
    public function testAgregarSalon()
    {
        $salon = new Salon(0, 'Sala Principal', 150);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('si', 'Sala Principal', 150)
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->agregar($salon);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarSalon()
    {
        $salon = new Salon(10, 'Sala Renovada', 200);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('sii', 'Sala Renovada', 200, 10)
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->actualizar($salon);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testAgregarFalla()
    {
        $salon = new Salon(0, 'Test', 50);
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(false);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->agregar($salon);
        
        $this->assertFalse($resultado);
    }
}