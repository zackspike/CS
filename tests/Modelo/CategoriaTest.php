<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Categoria.php';

class CategoriaTest extends TestCase{

    //PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters(){

        $id = 1;
        $nombre = "Aventura";
        $descripcion = "¿Emocionado de descubrir una nueva experiencia? \n
                        Este género abarca desde experiencias emocionantes y a veces arriesgadas, hasta géneros narrativos.";

        $categoria = new Categoria($id,$nombre,$descripcion);

        $this->assertEquals($id, $categoria->getIdCategoria());
        $this->assertEquals($nombre, $categoria->getNombre());
        $this->assertEquals($descripcion, $categoria->getDescripcion());
    }

    //PRUEBA SETTERS
    public function testSetters(){
        $categoria = new Categoria(1, "Terror", "¿Le temes a algo?\nEste género abarca desde relatos en el olvido, hasta experiencias de aterradoras.");

        $nuevoNombre = "Horror";
        $nuevaDescripcion = "¿Estas asustado?";

        $categoria->setNombre($nuevoNombre);
        $categoria->setDescripcion($nuevaDescripcion);

        //REVISAR QUE HUBIERON CAMBIOS CORRECTAMENTE
        $this->assertEquals($nuevoNombre, $categoria->getNombre());
        $this->assertEquals($nuevaDescripcion, $categoria->getDescripcion());

        //REVISAR QUE LOS DEMAS NO CAMBIEN
        $this->assertEquals(1, $categoria->getIdCategoria());

    }
}