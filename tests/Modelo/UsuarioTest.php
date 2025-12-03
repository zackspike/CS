<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/Usuario.php';

class UsuarioTest extends TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $usuario = new Usuario(1, 'Juan Pérez', 'juan@test.com', 'usuario', 'password123');
        
        $this->assertEquals(1, $usuario->getIdUsuario());
        $this->assertEquals('Juan Pérez', $usuario->getNombre());
        $this->assertEquals('juan@test.com', $usuario->getEmail());
        $this->assertEquals('usuario', $usuario->getRolUsuario());
        $this->assertEquals('password123', $usuario->getPassword());
    }
    
    /**
     * @test
     */
    public function testGetters()
    {
        $usuario = new Usuario(5, 'María García', 'maria@test.com', 'admin', 'pass456');
        
        $this->assertEquals(5, $usuario->getIdUsuario());
        $this->assertEquals('María García', $usuario->getNombre());
        $this->assertEquals('maria@test.com', $usuario->getEmail());
    }
    
    /**
     * @test
     */
    public function testSetNombre()
    {
        $usuario = new Usuario(1, 'Test', 'test@test.com', 'usuario', 'pass');
        
        $usuario->setNombre('Carlos López');
        
        $this->assertEquals('Carlos López', $usuario->getNombre());
    }
    
    /**
     * @test
     */
    public function testSetEmail()
    {
        $usuario = new Usuario(1, 'Test', 'test@test.com', 'usuario', 'pass');
        
        $usuario->setEmail('nuevo@email.com');
        
        $this->assertEquals('nuevo@email.com', $usuario->getEmail());
    }
    
    /**
     * @test
     */
    public function testSetRol()
    {
        $usuario = new Usuario(1, 'Test', 'test@test.com', 'usuario', 'pass');
        
        $usuario->setRolUsuario('admin');
        
        $this->assertEquals('admin', $usuario->getRolUsuario());
    }
    
    /**
     * @test
     */
    public function testSetPassword()
    {
        $usuario = new Usuario(1, 'Test', 'test@test.com', 'usuario', 'pass123');
        
        $usuario->setPassword('nuevoPass456');
        
        $this->assertEquals('nuevoPass456', $usuario->getPassword());
    }
}