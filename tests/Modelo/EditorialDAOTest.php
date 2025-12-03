<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/EditorialDAO.php';
require_once __DIR__ . '/../../Modelo/Editorial.php';

class EditorialDAOTest extends TestCase
{
    private EditorialDAO $dao;
    private $mockConexion;
    private $mockStatement;
    
    protected function setUp(): void
    {
        $this->mockConexion = $this->createMock(mysqli::class);
        $this->mockStatement = $this->createMock(mysqli_stmt::class);
        
        $this->dao = new EditorialDAO();
        
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
        $dao = new EditorialDAO();
        
        $reflection = new ReflectionClass($dao);
        
        $propTabla = $reflection->getProperty('nombreTabla');
        $propTabla->setAccessible(true);
        $this->assertEquals('Editoriales', $propTabla->getValue($dao));
        
        $propId = $reflection->getProperty('nombreId');
        $propId->setAccessible(true);
        $this->assertEquals('idEditorial', $propId->getValue($dao));
    }
    
    /**
     * @test
     */
    public function testMapear()
    {
        $fila = [
            'idEditorial' => '10',
            'nombreEditorial' => 'Penguin',
            'numPuestoEditorial' => 5,
            'ubicacionPuestoEditorial' => 'A-5'
        ];
        
        $reflection = new ReflectionClass($this->dao);
        $method = $reflection->getMethod('mapear');
        $method->setAccessible(true);
        
        $editorial = $method->invoke($this->dao, $fila);
        
        $this->assertInstanceOf(Editorial::class, $editorial);
        $this->assertEquals(10, $editorial->getIdEditorial());
        $this->assertEquals('Penguin', $editorial->getNombreEditorial());
    }
    
    /**
     * @test
     */
    public function testAgregarEditorial()
    {
        $editorial = new Editorial(0, 'Planeta', 15, 'B-15');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('sis', 'Planeta', 15, 'B-15')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->agregar($editorial);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarEditorial()
    {
        $editorial = new Editorial(5, 'Santillana', 20, 'C-20');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('sisi', 'Santillana', 20, 'C-20', 5)
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->actualizar($editorial);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testAgregarFalla()
    {
        $editorial = new Editorial(0, 'Test', 1, 'A-1');
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(false);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->agregar($editorial);
        
        $this->assertFalse($resultado);
    }
}
