<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Editorial.php';

class EditorialTest extends TestCase {

    //PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters(){


        $id = 1;
        $nombre = "Editorial Zardoya";
        $numPuesto = 3;
        $ubicacionPuesto = "Salón Maya";

        $editorial = new Editorial($id,$nombre,$numPuesto,$ubicacionPuesto);

        $this->assertEquals($id, $editorial->getIdEditorial());
        $this->assertEquals($nombre, $editorial->getNombreEditorial());
        $this->assertEquals($numPuesto, $editorial->getNumPuestoEditorial());
        $this->assertEquals($ubicacionPuesto, $editorial->getUbicacionPuestoEditorial());
    }

    //PRUEBA SETTERS
    public function testSetters(){
        $editorial = new Editorial(1, "Editorial Zardoya", 4, "Salón Principal");

        $cambioNombreEditorial = "Editorial Zardoya 2: Ahora es Personal";
        $nuevaUbicacion = "Salón UXMAL";

        $editorial->setNombreEditorial($cambioNombreEditorial);
        $editorial->setUbicacionPuestoEditorial($nuevaUbicacion);

        //REVISAR QUE HUBIERON CAMBIOS CORRECTAMENTE
        $this->assertEquals($cambioNombreEditorial, $editorial->getNombreEditorial());
        $this->assertEquals($nuevaUbicacion, $editorial->getUbicacionPuestoEditorial());

        //REVISAR QUE LOS DEMAS NO CAMBIEN
        $this->assertEquals(1, $editorial->getIdEditorial());
        $this->assertEquals(4, $editorial->getNumPuestoEditorial());

    }
}
