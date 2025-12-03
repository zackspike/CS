<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/Editorial.php';

class EditorialTest extends TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $editorial = new Editorial(1, 'Penguin Random House', 15, 'A-15');
        
        $this->assertEquals(1, $editorial->getIdEditorial());
        $this->assertEquals('Penguin Random House', $editorial->getNombreEditorial());
        $this->assertEquals(15, $editorial->getNumPuestoEditorial());
        $this->assertEquals('A-15', $editorial->getUbicacionPuestoEditorial());
    }
    
    /**
     * @test
     */
    public function testGetters()
    {
        $editorial = new Editorial(5, 'Planeta', 20, 'B-20');
        
        $this->assertEquals(5, $editorial->getIdEditorial());
        $this->assertEquals('Planeta', $editorial->getNombreEditorial());
    }
    
    /**
     * @test
     */
    public function testSetters()
    {
        $editorial = new Editorial(1, 'Test', 1, 'Test');
        
        $editorial->setIdEditorial(10);
        $editorial->setNombreEditorial('Santillana');
        $editorial->setNumPuestoEditorial(25);
        $editorial->setUbicacionPuestoEditorial('C-25');
        
        $this->assertEquals(10, $editorial->getIdEditorial());
        $this->assertEquals('Santillana', $editorial->getNombreEditorial());
        $this->assertEquals(25, $editorial->getNumPuestoEditorial());
        $this->assertEquals('C-25', $editorial->getUbicacionPuestoEditorial());
    }
    
    /**
     * @test
     */
    public function testSetNombre()
    {
        $editorial = new Editorial(1, 'Editorial A', 5, 'A-5');
        
        $editorial->setNombreEditorial('Editorial B');
        
        $this->assertEquals('Editorial B', $editorial->getNombreEditorial());
    }
    
    /**
     * @test
     */
    public function testSetNumPuesto()
    {
        $editorial = new Editorial(1, 'Test', 10, 'A-10');
        
        $editorial->setNumPuestoEditorial(50);
        
        $this->assertEquals(50, $editorial->getNumPuestoEditorial());
    }
}