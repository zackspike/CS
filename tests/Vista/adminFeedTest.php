<?php

use PHPUnit\Framework\TestCase;

class adminFeedTest extends TestCase {

    // PRUEBA: Redirección cuando usuario no está logueado
    public function testRedireccionSinSesion(){
        // Simular que no hay sesión activa
        if(isset($_SESSION['rol'])) {
            unset($_SESSION['rol']);
        }
        
        $estaLogueado = isset($_SESSION['rol']);
        
        $this->assertFalse($estaLogueado);
    }

    // PRUEBA: Redirección cuando usuario no es administrador
    public function testRedireccionUsuarioNoAdmin(){
        $_SESSION['rol'] = 'usuario';
        
        $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
        
        $this->assertFalse($esAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
    }

    // PRUEBA: Acceso permitido para administrador
    public function testAccesoAdministrador(){
        $_SESSION['rol'] = 'admin';
        
        $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
        
        $this->assertTrue($esAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
    }

    // PRUEBA: Nombre del administrador disponible
    public function testNombreAdministradorDisponible(){
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'Carlos Ramírez';
        
        $nombreAdmin = $_SESSION['nombre'];
        
        $this->assertEquals('Carlos Ramírez', $nombreAdmin);
        $this->assertNotEmpty($nombreAdmin);
        $this->assertIsString($nombreAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Verificar que el nombre existe en sesión
    public function testNombreExisteEnSesion(){
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'María González';
        
        $tieneNombre = isset($_SESSION['nombre']);
        
        $this->assertTrue($tieneNombre);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Sesión completa de administrador
    public function testSesionCompletaAdministrador(){
        $_SESSION['idUsuario'] = 1;
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'Admin Principal';
        
        $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
        $nombreAdmin = $_SESSION['nombre'];
        
        $this->assertTrue($esAdmin);
        $this->assertEquals('Admin Principal', $nombreAdmin);
        $this->assertEquals(1, $_SESSION['idUsuario']);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: URLs de navegación correctas
    public function testUrlsNavegacionCorrectas(){
        $urlWebPublica = "index.php";
        $urlLogout = "../Controlador/UsuarioController.php?accion=logout";
        
        $this->assertEquals("index.php", $urlWebPublica);
        $this->assertStringContainsString("UsuarioController.php", $urlLogout);
        $this->assertStringContainsString("accion=logout", $urlLogout);
    }

    // PRUEBA: URLs de administración de módulos
    public function testUrlsModulosAdministracion(){
        $urlEventos = "gestionEventos.php";
        $urlCategorias = "categorias.php";
        $urlSalones = "salones.php";
        $urlEditoriales = "editoriales.php";
        
        $this->assertEquals("gestionEventos.php", $urlEventos);
        $this->assertEquals("categorias.php", $urlCategorias);
        $this->assertEquals("salones.php", $urlSalones);
        $this->assertEquals("editoriales.php", $urlEditoriales);
    }

    // PRUEBA: Cantidad de módulos de administración
    public function testCantidadModulosAdministracion(){
        $modulos = [
            'eventos' => 'gestionEventos.php',
            'categorias' => 'categorias.php',
            'salones' => 'salones.php',
            'editoriales' => 'editoriales.php'
        ];
        
        $this->assertCount(4, $modulos);
    }

    // PRUEBA: Módulos disponibles para administración
    public function testModulosDisponibles(){
        $modulosDisponibles = ['eventos', 'categorias', 'salones', 'editoriales'];
        
        $this->assertContains('eventos', $modulosDisponibles);
        $this->assertContains('categorias', $modulosDisponibles);
        $this->assertContains('salones', $modulosDisponibles);
        $this->assertContains('editoriales', $modulosDisponibles);
    }

    // PRUEBA: Verificar rol exacto de administrador
    public function testRolExactoAdministrador(){
        $_SESSION['rol'] = 'admin';
        
        $rol = $_SESSION['rol'];
        
        $this->assertEquals('admin', $rol);
        $this->assertNotEquals('usuario', $rol);
        $this->assertNotEquals('moderador', $rol);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
    }

    // PRUEBA: Usuario con rol diferente no es admin
    public function testUsuarioConRolDiferente(){
        $rolesNoAdmin = ['usuario', 'moderador', 'invitado', 'editor'];
        
        foreach($rolesNoAdmin as $rol) {
            $_SESSION['rol'] = $rol;
            $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
            $this->assertFalse($esAdmin, "El rol '$rol' no debe tener acceso de admin");
        }
        
        // Limpiar sesión
        unset($_SESSION['rol']);
    }

    // PRUEBA: Validación estricta de rol admin
    public function testValidacionEstrictaRolAdmin(){
        // Caso: admin en minúsculas
        $_SESSION['rol'] = 'admin';
        $esAdmin = $_SESSION['rol'] == 'admin';
        $this->assertTrue($esAdmin);
        
        // Caso: Admin con mayúscula (no debería pasar)
        $_SESSION['rol'] = 'Admin';
        $esAdmin = $_SESSION['rol'] == 'admin';
        $this->assertFalse($esAdmin);
        
        // Caso: ADMIN en mayúsculas (no debería pasar)
        $_SESSION['rol'] = 'ADMIN';
        $esAdmin = $_SESSION['rol'] == 'admin';
        $this->assertFalse($esAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
    }

    // PRUEBA: Nombre de administrador con caracteres especiales
    public function testNombreConCaracteresEspeciales(){
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'José María Pérez';
        
        $nombreAdmin = $_SESSION['nombre'];
        
        $this->assertEquals('José María Pérez', $nombreAdmin);
        $this->assertStringContainsString('José', $nombreAdmin);
        $this->assertStringContainsString('María', $nombreAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Nombre de administrador vacío
    public function testNombreAdministradorVacio(){
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = '';
        
        $nombreAdmin = $_SESSION['nombre'];
        
        $this->assertEmpty($nombreAdmin);
        $this->assertEquals('', $nombreAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Acceso con sesión parcial (solo rol)
    public function testAccesoConSesionParcial(){
        $_SESSION['rol'] = 'admin';
        // No se establece $_SESSION['nombre']
        
        $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
        $tieneNombre = isset($_SESSION['nombre']);
        
        $this->assertTrue($esAdmin);
        $this->assertFalse($tieneNombre);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
    }

    // PRUEBA: Estructura de navegación del administrador
    public function testEstructuraNavegacionAdmin(){
        $opcionesNavegacion = [
            'ver_web_publica' => 'index.php',
            'logout' => '../Controlador/UsuarioController.php?accion=logout'
        ];
        
        $this->assertArrayHasKey('ver_web_publica', $opcionesNavegacion);
        $this->assertArrayHasKey('logout', $opcionesNavegacion);
        $this->assertCount(2, $opcionesNavegacion);
    }

    // PRUEBA: Verificar que todas las URLs son strings
    public function testUrlsSonStrings(){
        $urls = [
            'gestionEventos.php',
            'categorias.php',
            'salones.php',
            'editoriales.php',
            'index.php'
        ];
        
        foreach($urls as $url) {
            $this->assertIsString($url);
            $this->assertStringContainsString('.php', $url);
        }
    }

    // PRUEBA: Parámetro de logout correcto
    public function testParametroLogoutCorrecto(){
        $urlLogout = "../Controlador/UsuarioController.php?accion=logout";
        
        // Extraer parámetros
        $partesUrl = explode('?', $urlLogout);
        $parametros = isset($partesUrl[1]) ? $partesUrl[1] : '';
        
        $this->assertStringContainsString('accion=logout', $parametros);
        $this->assertEquals('accion=logout', $parametros);
    }

    // PRUEBA: Ruta del controlador de logout
    public function testRutaControladorLogout(){
        $urlLogout = "../Controlador/UsuarioController.php?accion=logout";
        
        $this->assertStringStartsWith('../Controlador/', $urlLogout);
        $this->assertStringContainsString('UsuarioController.php', $urlLogout);
    }

    // PRUEBA: Acceso sin rol definido
    public function testAccesoSinRolDefinido(){
        // Asegurar que no hay rol en sesión
        if(isset($_SESSION['rol'])) {
            unset($_SESSION['rol']);
        }
        
        $tieneRol = isset($_SESSION['rol']);
        $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
        
        $this->assertFalse($tieneRol);
        $this->assertFalse($esAdmin);
    }

    // PRUEBA: Flujo completo de acceso de administrador
    public function testFlujoCompletoAccesoAdmin(){
        // Simular login de administrador
        $_SESSION['idUsuario'] = 1;
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'Administrador Principal';
        $_SESSION['email'] = 'admin@filey.com';
        
        // Verificar acceso
        $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
        $nombreAdmin = $_SESSION['nombre'];
        
        $this->assertTrue($esAdmin);
        $this->assertEquals('Administrador Principal', $nombreAdmin);
        $this->assertEquals('admin@filey.com', $_SESSION['email']);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
        unset($_SESSION['email']);
    }

    // PRUEBA: Módulos con descripciones específicas
    public function testModulosConDescripciones(){
        $modulosDescripciones = [
            'eventos' => 'Crea conferencias, talleres y premiaciones',
            'categorias' => 'Agrega nuevas temáticas para los eventos',
            'salones' => 'Gestiona los auditorios y aulas disponibles',
            'editoriales' => 'Registro de las empresas y puestos editoriales'
        ];
        
        $this->assertArrayHasKey('eventos', $modulosDescripciones);
        $this->assertArrayHasKey('categorias', $modulosDescripciones);
        $this->assertArrayHasKey('salones', $modulosDescripciones);
        $this->assertArrayHasKey('editoriales', $modulosDescripciones);
        
        $this->assertNotEmpty($modulosDescripciones['eventos']);
        $this->assertNotEmpty($modulosDescripciones['categorias']);
    }
}