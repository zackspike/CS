<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/CategoriaDAO.php';
require_once __DIR__ . '/../../Modelo/Categoria.php';

class CategoriaDAOTest extends TestCase
{
    private CategoriaDAO $dao;
    private $mockConexion;
    private $mockStatement;
    
    protected function setUp(): void
    {
        // Crear mock de la conexión MySQLi
        $this->mockConexion = $this->createMock(mysqli::class);
        
        // Crear mock del statement
        $this->mockStatement = $this->createMock(mysqli_stmt::class);
        
        // Crear instancia del DAO
        $this->dao = new CategoriaDAO();
        
        // Inyectar la conexión mock usando reflexión
        $reflection = new ReflectionClass($this->dao);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        $property->setValue($this->dao, $this->mockConexion);
    }
    
    /**
     * @test
     */
    public function testConstructorInicializaAtributos()
    {
        $dao = new CategoriaDAO();
        
        $reflection = new ReflectionClass($dao);
        
        $propTabla = $reflection->getProperty('nombreTabla');
        $propTabla->setAccessible(true);
        $this->assertEquals('Categorias', $propTabla->getValue($dao));
        
        $propId = $reflection->getProperty('nombreId');
        $propId->setAccessible(true);
        $this->assertEquals('idCategoria', $propId->getValue($dao));
    }
    
    /**
     * @test
     */
    public function testMapearCreaObjetoCategoria()
    {
        $fila = [
            'idCategoria' => '5',
            'nombre' => 'Ficción',
            'descripcion' => 'Libros de ficción literaria'
        ];
        
        $reflection = new ReflectionClass($this->dao);
        $method = $reflection->getMethod('mapear');
        $method->setAccessible(true);
        
        $categoria = $method->invoke($this->dao, $fila);
        
        $this->assertInstanceOf(Categoria::class, $categoria);
        $this->assertEquals(5, $categoria->getIdCategoria());
        $this->assertEquals('Ficción', $categoria->getNombre());
        $this->assertEquals('Libros de ficción literaria', $categoria->getDescripcion());
    }
    
    /**
     * @test
     */
    public function testMapearConvierteIdAEntero()
    {
        $fila = [
            'idCategoria' => '123', // String
            'nombre' => 'Test',
            'descripcion' => 'Test desc'
        ];
        
        $reflection = new ReflectionClass($this->dao);
        $method = $reflection->getMethod('mapear');
        $method->setAccessible(true);
        
        $categoria = $method->invoke($this->dao, $fila);
        
        $this->assertIsInt($categoria->getIdCategoria());
        $this->assertEquals(123, $categoria->getIdCategoria());
    }
    
    /**
     * @test
     */
    public function testAgregarCategoriaExitoso()
    {
        $categoria = new Categoria(0, 'Ciencia Ficción', 'Libros de ciencia ficción');
        
        // Configurar el mock
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO Categorias'))
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ss', 'Ciencia Ficción', 'Libros de ciencia ficción')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        // Ejecutar
        $resultado = $this->dao->agregar($categoria);
        
        // Verificar
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testAgregarCategoriaFallido()
    {
        $categoria = new Categoria(0, 'Terror', 'Libros de terror');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(false); // Fallo en la ejecución
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->agregar($categoria);
        
        $this->assertFalse($resultado);
    }
    
    /**
     * @test
     */
    public function testAgregarUsaQueryCorrecta()
    {
        $categoria = new Categoria(0, 'Historia', 'Libros históricos');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO Categorias (nombre, descripcion) VALUES (?, ?)')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $this->dao->agregar($categoria);
    }
    
    /**
     * @test
     */
    public function testAgregarConNombreVacio()
    {
        $categoria = new Categoria(0, '', 'Descripción sin nombre');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ss', '', 'Descripción sin nombre')
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->agregar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testAgregarConDescripcionVacia()
    {
        $categoria = new Categoria(0, 'Biografías', '');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ss', 'Biografías', '')
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->agregar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testAgregarConCaracteresEspeciales()
    {
        $categoria = new Categoria(0, 'Niños & Jóvenes', 'Literatura para niños/jóvenes');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ss', 'Niños & Jóvenes', 'Literatura para niños/jóvenes')
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->agregar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarCategoriaExitoso()
    {
        $categoria = new Categoria(10, 'Fantasía', 'Libros de fantasía épica');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('UPDATE Categorias'))
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ssi', 'Fantasía', 'Libros de fantasía épica', 10)
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->actualizar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarCategoriaFallido()
    {
        $categoria = new Categoria(20, 'Romance', 'Novelas románticas');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(false); // Fallo
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->actualizar($categoria);
        
        $this->assertFalse($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarUsaQueryCorrecta()
    {
        $categoria = new Categoria(5, 'Ensayo', 'Libros de ensayo');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->with('UPDATE Categorias SET nombre = ?, descripcion = ? WHERE idCategoria = ?')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $this->dao->actualizar($categoria);
    }
    
    /**
     * @test
     */
    public function testActualizarConIdCero()
    {
        $categoria = new Categoria(0, 'Test', 'Test descripción');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ssi', 'Test', 'Test descripción', 0)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->actualizar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarConIdNegativo()
    {
        $categoria = new Categoria(-1, 'Test', 'Test descripción');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ssi', 'Test', 'Test descripción', -1)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->actualizar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarConTextoLargo()
    {
        $nombreLargo = str_repeat('A', 255);
        $descripcionLarga = str_repeat('B', 1000);
        
        $categoria = new Categoria(15, $nombreLargo, $descripcionLarga);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ssi', $nombreLargo, $descripcionLarga, 15)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->actualizar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testAgregarConTextoLargo()
    {
        $nombreLargo = str_repeat('X', 500);
        $descripcionLarga = str_repeat('Y', 2000);
        
        $categoria = new Categoria(0, $nombreLargo, $descripcionLarga);
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ss', $nombreLargo, $descripcionLarga)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->agregar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testBindParamUsaTiposCorrectos()
    {
        // Para agregar: dos strings (ss)
        $categoriaAgregar = new Categoria(0, 'Test1', 'Desc1');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with(
                $this->equalTo('ss'), // Dos strings
                $this->anything(),
                $this->anything()
            )
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $this->dao->agregar($categoriaAgregar);
    }
    
    /**
     * @test
     */
    public function testActualizarBindParamUsaTiposCorrectos()
    {
        // Para actualizar: dos strings y un entero (ssi)
        $categoriaActualizar = new Categoria(5, 'Test2', 'Desc2');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with(
                $this->equalTo('ssi'), // Dos strings, un entero
                $this->anything(),
                $this->anything(),
                $this->anything()
            )
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $this->dao->actualizar($categoriaActualizar);
    }
    
    /**
     * @test
     */
    public function testStatementSeCierraEnAgregar()
    {
        $categoria = new Categoria(0, 'Test', 'Test');
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        
        // Verificar que close() se llama
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $this->dao->agregar($categoria);
    }
    
    /**
     * @test
     */
    public function testStatementSeCierraEnActualizar()
    {
        $categoria = new Categoria(1, 'Test', 'Test');
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        
        // Verificar que close() se llama
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $this->dao->actualizar($categoria);
    }
    
    /**
     * @test
     */
    public function testStatementSeCierraInclusoEnFallo()
    {
        $categoria = new Categoria(0, 'Test', 'Test');
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(false); // Falla
        
        // Aún así debe cerrar el statement
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $this->dao->agregar($categoria);
    }
    
    /**
     * @test
     */
    public function testAgregarConAcentosYEñes()
    {
        $categoria = new Categoria(0, 'Español', 'Libros en español con ñ y á é í ó ú');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ss', 'Español', 'Libros en español con ñ y á é í ó ú')
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->agregar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testActualizarConAcentosYEñes()
    {
        $categoria = new Categoria(8, 'Niños', 'Categoría para niños y niñas');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->with('ssi', 'Niños', 'Categoría para niños y niñas', 8)
            ->willReturn(true);
        
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->actualizar($categoria);
        
        $this->assertTrue($resultado);
    }
    
    protected function tearDown(): void
    {
        $this->dao = null;
        $this->mockConexion = null;
        $this->mockStatement = null;
    }
}