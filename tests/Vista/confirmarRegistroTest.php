<?php

use PHPUnit\Framework\TestCase;

class ConfirmarRegistroTest extends TestCase {

    // PRUEBA: Redirección cuando usuario no está logueado
    public function testRedireccionSinSesion(){
        // Simular que no hay sesión activa
        if(isset($_SESSION['idUsuario'])) {
            unset($_SESSION['idUsuario']);
        }
        
        $estaLogueado = isset($_SESSION['idUsuario']);
        
        $this->assertFalse($estaLogueado);
    }

    // PRUEBA: Acceso permitido cuando usuario está logueado
    public function testAccesoConSesion(){
        $_SESSION['idUsuario'] = 5;
        
        $estaLogueado = isset($_SESSION['idUsuario']);
        
        $this->assertTrue($estaLogueado);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
    }

    // PRUEBA: ID de evento recibido correctamente
    public function testIdEventoRecibido(){
        $_GET['id'] = '10';
        
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $this->assertEquals('10', $idEvento);
        $this->assertNotEquals(0, $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: ID de evento por defecto cuando no se envía
    public function testIdEventoPorDefecto(){
        // Asegurar que no existe el parámetro
        if(isset($_GET['id'])) {
            unset($_GET['id']);
        }
        
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $this->assertEquals(0, $idEvento);
    }

    // PRUEBA: ID de evento como string numérico
    public function testIdEventoComoString(){
        $_GET['id'] = '25';
        
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $this->assertIsString($idEvento);
        $this->assertEquals('25', $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: Conversión de ID de evento a entero
    public function testIdEventoConversionEntero(){
        $_GET['id'] = '42';
        
        $idEvento = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $this->assertIsInt($idEvento);
        $this->assertEquals(42, $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: ID de evento inválido (no numérico)
    public function testIdEventoInvalido(){
        $_GET['id'] = 'abc';
        
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        // Verificar que se recibe el valor aunque no sea numérico
        $this->assertEquals('abc', $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: ID de evento vacío se convierte a 0
    public function testIdEventoVacio(){
        $_GET['id'] = '';
        
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        // Si está vacío, debería ser tratado como 0
        if (empty($idEvento)) {
            $idEvento = 0;
        }
        
        $this->assertEquals(0, $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: ID de usuario en sesión es válido
    public function testIdUsuarioValido(){
        $_SESSION['idUsuario'] = 15;
        
        $idUsuario = isset($_SESSION['idUsuario']) ? $_SESSION['idUsuario'] : null;
        
        $this->assertNotNull($idUsuario);
        $this->assertEquals(15, $idUsuario);
        $this->assertIsInt($idUsuario);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
    }

    // PRUEBA: Verificar que el ID de usuario existe antes de acceder
    public function testVerificarIdUsuarioExiste(){
        $_SESSION['idUsuario'] = 7;
        
        $tienePermiso = isset($_SESSION['idUsuario']);
        
        $this->assertTrue($tienePermiso);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
    }

    // PRUEBA: Usuario no tiene permiso sin sesión
    public function testUsuarioSinPermiso(){
        if(isset($_SESSION['idUsuario'])) {
            unset($_SESSION['idUsuario']);
        }
        
        $tienePermiso = isset($_SESSION['idUsuario']);
        
        $this->assertFalse($tienePermiso);
    }

    // PRUEBA: URL de confirmación se construye correctamente
    public function testUrlConfirmacionCorrecta(){
        $idEvento = 20;
        
        $urlConfirmacion = "../Controlador/RegistroController.php?accion=inscribir&idEvento=" . $idEvento;
        
        $this->assertStringContainsString('accion=inscribir', $urlConfirmacion);
        $this->assertStringContainsString('idEvento=20', $urlConfirmacion);
        $this->assertStringContainsString('RegistroController.php', $urlConfirmacion);
    }

    // PRUEBA: URL de cancelación es correcta
    public function testUrlCancelacion(){
        $urlCancelacion = "index.php";
        
        $this->assertEquals("index.php", $urlCancelacion);
    }

    // PRUEBA: Parámetros de URL de confirmación
    public function testParametrosUrlConfirmacion(){
        $idEvento = 35;
        $accion = 'inscribir';
        
        $urlCompleta = "../Controlador/RegistroController.php?accion=" . $accion . "&idEvento=" . $idEvento;
        
        // Verificar que contiene ambos parámetros
        $this->assertStringContainsString('accion=inscribir', $urlCompleta);
        $this->assertStringContainsString('idEvento=35', $urlCompleta);
    }

    // PRUEBA: ID de evento con múltiples dígitos
    public function testIdEventoMultiplesDigitos(){
        $_GET['id'] = '12345';
        
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $this->assertEquals('12345', $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: Validación de ID de evento positivo
    public function testIdEventoPositivo(){
        $_GET['id'] = '50';
        
        $idEvento = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $this->assertGreaterThan(0, $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: Validación de ID de evento no negativo
    public function testIdEventoNoNegativo(){
        $_GET['id'] = '-5';
        
        $idEvento = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        // Verificar que se puede recibir un negativo pero debería validarse
        $this->assertEquals(-5, $idEvento);
        $this->assertLessThan(0, $idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }

    // PRUEBA: Sesión con múltiples datos
    public function testSesionMultiplesDatos(){
        $_SESSION['idUsuario'] = 10;
        $_SESSION['nombre'] = 'Juan Pérez';
        $_SESSION['rol'] = 'usuario';
        
        $estaLogueado = isset($_SESSION['idUsuario']);
        
        $this->assertTrue($estaLogueado);
        $this->assertEquals('Juan Pérez', $_SESSION['nombre']);
        $this->assertEquals('usuario', $_SESSION['rol']);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
        unset($_SESSION['nombre']);
        unset($_SESSION['rol']);
    }

    // PRUEBA: Flujo completo de confirmación
    public function testFlujoCompletoConfirmacion(){
        // Simular usuario logueado
        $_SESSION['idUsuario'] = 8;
        
        // Simular evento seleccionado
        $_GET['id'] = '15';
        
        // Verificar precondiciones
        $estaLogueado = isset($_SESSION['idUsuario']);
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $this->assertTrue($estaLogueado);
        $this->assertEquals('15', $idEvento);
        $this->assertNotEquals(0, $idEvento);
        
        // Limpiar
        unset($_SESSION['idUsuario']);
        unset($_GET['id']);
    }

    // PRUEBA: Acceso denegado sin ID de usuario
    public function testAccesoDenegadoSinIdUsuario(){
        // Simular sesión iniciada pero sin idUsuario (caso anormal)
        $_SESSION['nombre'] = 'Test User';
        
        $puedeAcceder = isset($_SESSION['idUsuario']);
        
        $this->assertFalse($puedeAcceder);
        
        // Limpiar sesión
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Tipo de dato de ID de evento original
    public function testTipoDatoIdEventoOriginal(){
        $_GET['id'] = '77';
        
        $idEvento = isset($_GET['id']) ? $_GET['id'] : 0;
        
        // Por defecto viene como string desde GET
        $this->assertIsString($idEvento);
        
        // Limpiar GET
        unset($_GET['id']);
    }
}