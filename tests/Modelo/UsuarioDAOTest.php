<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/UsuarioDAO.php';
require_once __DIR__ . '/../../Modelo/Usuario.php';


class UsuarioDAOTest extends TestCase
{
    private UsuarioDAO $dao;
    private $mockConexion;
    private $mockStatement;
    private $mockResult;
    
    protected function setUp(): void
    {
        $this->mockConexion = $this->createMock(mysqli::class);
        $this->mockStatement = $this->createMock(mysqli_stmt::class);
        $this->mockResult = $this->createMock(mysqli_result::class);
        
        $this->dao = new UsuarioDAO();
        
        // inyectar mock
        $reflection = new ReflectionClass($this->dao);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        $property->setValue($this->dao, $this->mockConexion);
    }
    
    /**
     * @test
     */
    public function testRegistrarUsuario()
    {
        $usuario = new Usuario(0, 'Juan Pérez', 'juan@test.com', 'usuario', 'password123');
        
        $this->mockConexion->expects($this->once())
            ->method('prepare')
            ->willReturn($this->mockStatement);
        
        $this->mockStatement->expects($this->once())
            ->method('bind_param')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        
        $this->mockStatement->expects($this->once())
            ->method('close');
        
        $resultado = $this->dao->registrar($usuario);
        
        $this->assertTrue($resultado);
    }
    
    /**
     * @test
     */
    public function testRegistrarFalla()
    {
        $usuario = new Usuario(0, 'Test', 'test@test.com', 'usuario', 'pass');
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(false);
        $this->mockStatement->method('close');
        
        $resultado = $this->dao->registrar($usuario);
        
        $this->assertFalse($resultado);
    }
    
    /**
     * @test
     */
    public function testLoginExitoso()
    {
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        
        $this->mockResult->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn([
                'idUsuario' => 5,
                'nombre' => 'Juan Pérez',
                'password' => $hashedPassword,
                'rolUsuario' => 'usuario'
            ]);
        
        $resultado = $this->dao->login('juan@test.com', 'password123');
        
        $this->assertIsArray($resultado);
        $this->assertEquals(5, $resultado['idUsuario']);
        $this->assertEquals('Juan Pérez', $resultado['nombre']);
        $this->assertArrayNotHasKey('password', $resultado);
    }
    
    /**
     * @test
     */
    public function testLoginPasswordIncorrecto()
    {
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        
        $this->mockResult->method('fetch_assoc')
            ->willReturn([
                'idUsuario' => 5,
                'nombre' => 'Juan',
                'password' => $hashedPassword,
                'rolUsuario' => 'usuario'
            ]);
        
        $resultado = $this->dao->login('juan@test.com', 'passwordIncorrecto');
        
        $this->assertFalse($resultado);
    }
    
    /**
     * @test
     */
    public function testLoginUsuarioNoExiste()
    {
        $this->mockConexion->method('prepare')->willReturn($this->mockStatement);
        $this->mockStatement->method('bind_param')->willReturn(true);
        $this->mockStatement->method('execute')->willReturn(true);
        $this->mockStatement->method('get_result')->willReturn($this->mockResult);
        
        $this->mockResult->expects($this->once())
            ->method('fetch_assoc')
            ->willReturn(null);
        
        $resultado = $this->dao->login('noexiste@test.com', 'password');
        
        $this->assertFalse($resultado);
    }
}