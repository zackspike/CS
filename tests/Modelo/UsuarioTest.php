<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../Modelo/Usuario.php';

class UsuarioTest extends TestCase {

    //PRUEBA DE CONSTRUCTOR Y GETTERS
    public function testConstructorYGetters(){

        $id = 1;
        $nombre = "Karen";
        $email = "karen@gmail.com";
        $rol = "Administrador";
        $contraseña = "pass123";

        $usuario = new Usuario($id, $nombre, $email, $rol, $contraseña);

        $this->assertEquals($id, $usuario->getIdUsuario());
        $this->assertEquals($nombre, $usuario->getNombre());
        $this->assertEquals($email, $usuario->getEmail());
        $this->assertEquals($rol, $usuario->getRolUsuario());
        $this->assertEquals($contraseña, $usuario->getContraseña());
    }

    //PRUEBA SETTERS
    public function testSetters(){
        $usuario = new Usuario(1, "Nombre Anterior", "anterior@mail.com", "Usuario", "oldPass");

        $nuevoNombre = "Nombre Actualizado";
        $nuevoEmail = "actualizado@mail.com";

        $usuario->setNombre($nuevoNombre);
        $usuario->setEmail($nuevoEmail);

        //REVISAR QUE HUBIERON CAMBIOS CORRECTAMENTE
        $this->assertEquals($nuevoNombre, $usuario->getNombre());
        $this->assertEquals($nuevoEmail, $usuario->getEmail());

        //REVISAR QUE LOS DEMAS NO CAMBIEN
        $this->assertEquals(1, $usuario->getIdUsuario());
        $this->assertEquals("Usuario", $usuario->getRolUsuario());
        $this->assertEquals("oldPass", $usuario->getContraseña());

    }
}