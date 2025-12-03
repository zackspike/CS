<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/CrudDAO.php';

class CrudDAOTest extends TestCase
{
    private $dao;
    private $mockConexion;
    private $mockStatement;
    private $mockResult;
    private const QUERY_TABLE = 'SELECT * FROM test_table';
    
    protected function setUp(): void
    {
        // Crear mocks
        $this->mockConexion = $this->createMock(mysqli::class);
        $this->mockStatement = $this->createMock(mysqli_stmt::class);
        $this->mockResult = $this->createMock(mysqli_result::class);
        
        // Crear instancia concreta de CrudDAO para testing
        $this->dao = new CrudDAOTestable();
        
        // Inyectar la conexión mock usando reflexión
        $reflection = new ReflectionClass($this->dao);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        $property->setValue($this->dao, $this->mockConexion);
    }
    
    /**
     * @test
     */
    public function testConstructorLlamaPadre()
    {
        $dao = new CrudDAOTestable();
        
        // Verificar que las propiedades se inicializan
        $this->assertNotNull($dao);
    }
    
    /**
     * @test
     */
    public function testObtenerTodosRetornaListaVacia()
    {
        $this->mockConexion->expects($this->once())
            ->method('query')
            ->with(self::QUERY_TABLE)
            ->willReturn(false); // Simular que no hay resultados
        
        $resultado = $this->dao->obtenerTodos();
        
        $this->assertIsArray($resultado);
        $this->assertEmpty($resultado);
    }
    
    /**
     * @test
     */
    public function testObtenerTodosRetornaListaConElementos()
    {
        $filas = [
            ['id' => 1, 'nombre' => 'Item 1'],
            ['id' => 2, 'nombre' => 'Item 2'],
            ['id' => 3, 'nombre' => 'Item 3']
        ];
        
        // Configurar mock de result para retornar filas
        $this->mockResult->expects($this->exactly(4)) // 3 filas + 1 null final
            ->method('fetch_assoc')
            ->willReturnOnConsecutiveCalls(
                $filas[0],
                $filas[1],
                $filas[2],
                null // Indica fin de resultados
            );
        
        $this->mockConexion->expects($this->once())
            ->method('query')
            ->with(self::QUERY_TABLE)
            ->willReturn($this->mockResult);
        
        $resultado = $this->dao->obtenerTodos();
        
        $this->assertIsArray($resultado);
        $this->assertCount(3, $resultado);
        $this->assertEquals('mapped_Item 1', $resultado[0]);
        $this->assertEquals('mapped_Item 2', $resultado[1]);
        $this->assertEquals('mapped_Item 3', $resultado[2]);
    }
    
    /**
     * @test
     */
    public function testObtenerTodosUsaNombreTablaCorrectamente()
    {
        $this->mockConexion->expects($this->once())
            ->method('query')
            ->with($this->equalTo(self::QUERY_TABLE))
            ->willReturn(false);
        
        $this->dao->obtenerTodos();
    }
    
    /**
     * @test
     */
    public function testObtenerTodosLlamaMapeoCadaFila()
    {
        $filas = [
            ['id' => 1, 'nombre' => 'A'],
            ['id' => 2, 'nombre' => 'B']
        ];
        
        $this->mockResult->expects($this->exactly(3))
            ->method('fetch_assoc')
            ->willReturnOnConsecutiveCalls($filas[0], $filas[1], null);
        
        $this->mockConexion->method('query')->willReturn($this->mockResult);
        
        $resultado = $this->dao->obtenerTodos();
        
        // Verificar que cada fila fue mapeada (prefijo "mapped_")
        $this->assertEquals('mapped_A', $resultado[0]);
        $this->assertEquals('mapped_B', $resultado[1]);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdExitoso()
    {
        $fila = ['id' => 5, 'nombre' => 'Test Item'];
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM test_table WHERE test_id = ?')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', 5)
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('get_result')
            ->willReturn($this->mockResult);
        
        $this->mockResult->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn($fila);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->obtenerPorId(5);
        
        $this->assertEquals('mapped_Test Item', $resultado);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdNoEncontrado()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        
        $this->mockResult->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(null); // No se encontró
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->obtenerPorId(999);
        
        $this->assertNull($resultado);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdConEjecucionFallida()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->method('bind_param')->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(false); // Fallo en ejecución
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->obtenerPorId(10);
        
        $this->assertNull($resultado);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdUsaNombreTablaYIdCorrectos()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM test_table WHERE test_id = ?'))
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(false);
        $this->mockStatement->method('close');
        
        $this->dao->obtenerPorId(1);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdCierraStatementSiempre()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        $this->mockResult->method('fetch_assoc')->willReturn(null);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $this->dao->obtenerPorId(1);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdCierraStatementInclusoEnFallo()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(false);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $this->dao->obtenerPorId(1);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdConIdCero()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', 0)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        $this->mockResult->method('fetch_assoc')->willReturn(null);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->obtenerPorId(0);
        
        $this->assertNull($resultado);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdConIdNegativo()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', -1)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        $this->mockResult->method('fetch_assoc')->willReturn(null);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->obtenerPorId(-1);
        
        $this->assertNull($resultado);
    }
    
    /**
     * @test
     */
    public function testEliminarExitoso()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM test_table WHERE test_id = ?')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', 10)
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->eliminar(10);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testEliminarFallido()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->method('bind_param')->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(false); // Fallo
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->eliminar(20);
        
        $this->assertFalse($resultado);
    }
    
    /**
     * @test
     */
    public function testEliminarUsaQueryCorrecta()
    {
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM test_table WHERE test_id = ?'))
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $this->dao->eliminar(5);
    }
    
    /**
     * @test
     */
    public function testEliminarCierraStatementSiempre()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $this->dao->eliminar(1);
    }
    
    /**
     * @test
     */
    public function testEliminarCierraStatementInclusoEnFallo()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(false);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $this->dao->eliminar(1);
    }
    
    /**
     * @test
     */
    public function testEliminarConIdCero()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', 0)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->eliminar(0);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testEliminarConIdNegativo()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', -5)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->eliminar(-5);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testEliminarUsaBindParamTipoEntero()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with(
                $this->equalTo('i'), // Tipo entero
                $this->anything()
            )
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $this->dao->eliminar(100);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdUsaBindParamTipoEntero()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with(
                $this->equalTo('i'), // Tipo entero
                $this->anything()
            )
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(false);
        $this->mockStatement->method('close');
        
        $this->dao->obtenerPorId(50);
    }
    
    /**
     * @test
     */
    public function testObtenerTodosConMuchasFilas()
    {
        $filas = [];
        for ($i = 1; $i <= 100; $i++) {
            $filas[] = ['id' => $i, 'nombre' => "Item $i"];
        }
        $filas[] = null; // Fin de resultados
        
        $this->mockResult->expects($this->exactly(101))
            ->method('fetch_assoc')
            ->willReturnOnConsecutiveCalls(...$filas);
        
        $this->mockConexion->method('query')->willReturn($this->mockResult);
        
        $resultado = $this->dao->obtenerTodos();
        
        $this->assertCount(100, $resultado);
        $this->assertEquals('mapped_Item 1', $resultado[0]);
        $this->assertEquals('mapped_Item 100', $resultado[99]);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdMapeaCorrectamente()
    {
        $fila = ['id' => 42, 'nombre' => 'Special Item'];
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        $this->mockResult->method('fetch_assoc')->willReturn($fila);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->obtenerPorId(42);
        
        // Verificar que se aplicó el mapeo
        $this->assertEquals('mapped_Special Item', $resultado);
    }
    
    /**
     * @test
     */
    public function testEliminarIdsGrandes()
    {
        $idGrande = 2147483647; // MAX INT
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', $idGrande)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->eliminar($idGrande);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testObtenerPorIdIdsGrandes()
    {
        $idGrande = 2147483647;
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('i', $idGrande)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        $this->mockResult->method('fetch_assoc')->willReturn(null);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->obtenerPorId($idGrande);
        
        $this->assertNull($resultado);
    }
    
    protected function tearDown(): void
    {
        $this->dao = null;
        $this->mockConexion = null;
        $this->mockStatement = null;
        $this->mockResult = null;
    }
}

class CrudDAOTestable extends CrudDAO
{
    public function __construct()
    {
        parent::__construct();
        $this->nombreTabla = 'test_table';
        $this->nombreId = 'test_id';
    }
    
    protected function mapear($fila)
    {
        // Mapeo simple para testing: prefija "mapped_" al nombre
        return 'mapped_' . ($fila['nombre'] ?? 'unknown');
    }
    
    public function agregar($entidad)
    {
        // Implementación dummy
        return true;
    }
    
    public function actualizar($entidad)
    {
        // Implementación dummy
        return true;
    }
}
