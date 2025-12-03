<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/Salon.php';

class SalonTest extends TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $salon = new Salon(1, 'Auditorio Principal', 200);
        
        $this->assertEquals(1, $salon->getIdSalon());
        $this->assertEquals('Auditorio Principal', $salon->getNombreSalon());
        $this->assertEquals(200, $salon->getMaxCapacidad());
    }
    
    /**
     * @test
     */
    public function testGetters()
    {
        $salon = new Salon(5, 'Sala VIP', 50);
        
        $this->assertEquals(5, $salon->getIdSalon());
        $this->assertEquals('Sala VIP', $salon->getNombreSalon());
        $this->assertEquals(50, $salon->getMaxCapacidad());
    }
    
    /**
     * @test
     */
    public function testSetNombre()
    {
        $salon = new Salon(1, 'Salon A', 100);
        
        $salon->setNombreSalon('Salon B');
        
        $this->assertEquals('Salon B', $salon->getNombreSalon());
    }
    
    /**
     * @test
     */
    public function testSetCapacidad()
    {
        $salon = new Salon(1, 'Test', 100);
        
        $salon->setMaxCapacidad(150);
        
        $this->assertEquals(150, $salon->getMaxCapacidad());
    }
    
    /**
     * @test
     */
    public function testSetId()
    {
        $salon = new Salon(1, 'Test', 100);
        
        $salon->setIdSalon(10);
        
        $this->assertEquals(10, $salon->getIdSalon());
    }
}