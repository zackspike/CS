<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/DAO.php';

/**
 * Pruebas unitarias para DAO
 */
class DAOTest extends TestCase
{
    /**
     * @test
     */
    public function testConstructorCreaConexion()
    {
        $dao = new DAOTestable();
        
        $reflection = new ReflectionClass($dao);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        
        $conexion = $property->getValue($dao);
        
        $this->assertNotNull($conexion);
    }
    
    /**
     * @test
     */
    public function testConexionEsMySQLi()
    {
        $dao = new DAOTestable();
        
        $reflection = new ReflectionClass($dao);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        
        $conexion = $property->getValue($dao);
        
        $this->assertInstanceOf(mysqli::class, $conexion);
    }
    
    /**
     * @test
     */
    public function testConexionEstaActiva()
    {
        $dao = new DAOTestable();
        
        $reflection = new ReflectionClass($dao);
        $property = $reflection->getProperty('conexion');
        $property->setAccessible(true);
        
        $conexion = $property->getValue($dao);
        
        // verificar que la conexion funciona
        $this->assertTrue($conexion->ping());
    }
    
    /**
     * @test
     */
    public function testConexionProtegida()
    {
        $dao = new DAOTestable();
        
        $reflection = new ReflectionClass($dao);
        $property = $reflection->getProperty('conexion');
        
        $this->assertTrue($property->isProtected());
    }
}

/**
 * Clase para testear DAO
 */
class DAOTestable extends DAO
{
    
}
