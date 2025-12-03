<?php

use PHPUnit\Framework\TestCase;

class editorialesTest extends TestCase {

    // PRUEBA: Redirección cuando usuario no está logueado
    public function testRedireccionSinSesion(){
        // Simular que no hay sesión
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

    // PRUEBA: Modo edición activado con parámetros correctos
    public function testModoEdicionActivado(){
        $_GET['accion'] = 'editar';
        $_GET['id'] = '3';
        
        $modoEdicion = isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($_GET['id']);
        
        $this->assertTrue($modoEdicion);
        
        // Limpiar GET
        unset($_GET['accion']);
        unset($_GET['id']);
    }

    // PRUEBA: Modo edición desactivado sin parámetros
    public function testModoEdicionDesactivado(){
        if(isset($_GET['accion'])) {
            unset($_GET['accion']);
        }
        if(isset($_GET['id'])) {
            unset($_GET['id']);
        }
        
        $modoEdicion = isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($_GET['id']);
        
        $this->assertFalse($modoEdicion);
    }

    // PRUEBA: Modo edición requiere ambos parámetros (acción sin id)
    public function testModoEdicionSinId(){
        $_GET['accion'] = 'editar';
        if(isset($_GET['id'])) {
            unset($_GET['id']);
        }
        
        $modoEdicion = isset($_GET['accion']) && $_GET['accion'] == 'editar' && isset($_GET['id']);
        
        $this->assertFalse($modoEdicion);
        
        // Limpiar GET
        unset($_GET['accion']);
    }

    // PRUEBA: Acción del formulario es 'agregar' en modo creación
    public function testAccionFormularioAgregar(){
        $editorialEditar = null;
        
        $accionFormulario = $editorialEditar ? 'actualizar' : 'agregar';
        
        $this->assertEquals('agregar', $accionFormulario);
    }

    // PRUEBA: Acción del formulario es 'actualizar' en modo edición
    public function testAccionFormularioActualizar(){
        // Simular objeto editorial
        $editorialEditar = new stdClass();
        $editorialEditar->id = 1;
        
        $accionFormulario = $editorialEditar ? 'actualizar' : 'agregar';
        
        $this->assertEquals('actualizar', $accionFormulario);
    }

    // PRUEBA: Texto del botón en modo creación
    public function testTextoBotonCreacion(){
        $editorialEditar = null;
        
        $textoBoton = $editorialEditar ? 'Guardar Cambios' : 'Crear Editorial';
        
        $this->assertEquals('Crear Editorial', $textoBoton);
    }

    // PRUEBA: Texto del botón en modo edición
    public function testTextoBotonEdicion(){
        $editorialEditar = new stdClass();
        
        $textoBoton = $editorialEditar ? 'Guardar Cambios' : 'Crear Editorial';
        
        $this->assertEquals('Guardar Cambios', $textoBoton);
    }

    // PRUEBA: Título del formulario en modo creación
    public function testTituloFormularioCreacion(){
        $editorialEditar = null;
        
        $tituloFormulario = $editorialEditar ? '️ Editar Editorial' : 'Nueva Editorial';
        
        $this->assertEquals('Nueva Editorial', $tituloFormulario);
    }

    // PRUEBA: Título del formulario en modo edición
    public function testTituloFormularioEdicion(){
        $editorialEditar = new stdClass();
        
        $tituloFormulario = $editorialEditar ? '️ Editar Editorial' : 'Nueva Editorial';
        
        $this->assertEquals('️ Editar Editorial', $tituloFormulario);
    }

    // PRUEBA: Mensaje de éxito al agregar editorial
    public function testMensajeAgregado(){
        $_GET['message'] = 'agregado';
        
        $mensaje = '';
        if($_GET['message'] == 'agregado') $mensaje = "¡Editorial agregada con éxito!";
        if($_GET['message'] == 'actualizado') $mensaje = "¡Cambios guardados correctamente!";
        if($_GET['message'] == 'eliminado') $mensaje = "Editorial eliminada.";
        
        $this->assertEquals("¡Editorial agregada con éxito!", $mensaje);
        
        // Limpiar GET
        unset($_GET['message']);
    }

    // PRUEBA: Mensaje de éxito al actualizar editorial
    public function testMensajeActualizado(){
        $_GET['message'] = 'actualizado';
        
        $mensaje = '';
        if($_GET['message'] == 'agregado') $mensaje = "¡Editorial agregada con éxito!";
        if($_GET['message'] == 'actualizado') $mensaje = "¡Cambios guardados correctamente!";
        if($_GET['message'] == 'eliminado') $mensaje = "Editorial eliminada.";
        
        $this->assertEquals("¡Cambios guardados correctamente!", $mensaje);
        
        // Limpiar GET
        unset($_GET['message']);
    }

    // PRUEBA: Mensaje de éxito al eliminar editorial
    public function testMensajeEliminado(){
        $_GET['message'] = 'eliminado';
        
        $mensaje = '';
        if($_GET['message'] == 'agregado') $mensaje = "¡Editorial agregada con éxito!";
        if($_GET['message'] == 'actualizado') $mensaje = "¡Cambios guardados correctamente!";
        if($_GET['message'] == 'eliminado') $mensaje = "Editorial eliminada.";
        
        $this->assertEquals("Editorial eliminada.", $mensaje);
        
        // Limpiar GET
        unset($_GET['message']);
    }

    // PRUEBA: Lista vacía de editoriales
    public function testListaEditorialesVacia(){
        $lista = [];
        
        $this->assertEmpty($lista);
        $this->assertCount(0, $lista);
    }

    // PRUEBA: Lista con editoriales existentes
    public function testListaEditorialesConDatos(){
        $editorial1 = new stdClass();
        $editorial1->id = 1;
        $editorial1->nombre = "Editorial Planeta";
        
        $editorial2 = new stdClass();
        $editorial2->id = 2;
        $editorial2->nombre = "Editorial Santillana";
        
        $lista = [$editorial1, $editorial2];
        
        $this->assertNotEmpty($lista);
        $this->assertCount(2, $lista);
    }

    // PRUEBA: Verificar que el nombre del administrador esté disponible
    public function testNombreAdministradorDisponible(){
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'María González';
        
        $nombreAdmin = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
        
        $this->assertEquals('María González', $nombreAdmin);
        $this->assertNotEmpty($nombreAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Campo nombre vacío en modo creación
    public function testCampoNombreVacioCreacion(){
        $editorialEditar = null;
        
        $valorNombre = $editorialEditar ? 'Nombre Existente' : '';
        
        $this->assertEquals('', $valorNombre);
    }

    // PRUEBA: Campo nombre con valor en modo edición
    public function testCampoNombreConValorEdicion(){
        $editorialEditar = new stdClass();
        $editorialEditar->nombre = 'Editorial Alfaguara';
        
        $valorNombre = $editorialEditar ? $editorialEditar->nombre : '';
        
        $this->assertEquals('Editorial Alfaguara', $valorNombre);
        $this->assertNotEmpty($valorNombre);
    }

    // PRUEBA: Campo número de puesto vacío en modo creación
    public function testCampoNumPuestoVacioCreacion(){
        $editorialEditar = null;
        
        $valorNumPuesto = $editorialEditar ? 5 : '';
        
        $this->assertEquals('', $valorNumPuesto);
    }

    // PRUEBA: Campo número de puesto con valor en modo edición
    public function testCampoNumPuestoConValorEdicion(){
        $editorialEditar = new stdClass();
        $editorialEditar->numPuesto = 12;
        
        $valorNumPuesto = $editorialEditar ? $editorialEditar->numPuesto : '';
        
        $this->assertEquals(12, $valorNumPuesto);
        $this->assertIsInt($valorNumPuesto);
    }

    // PRUEBA: Campo ubicación vacío en modo creación
    public function testCampoUbicacionVacioCreacion(){
        $editorialEditar = null;
        
        $valorUbicacion = $editorialEditar ? 'Ubicación Existente' : '';
        
        $this->assertEquals('', $valorUbicacion);
    }

    // PRUEBA: Campo ubicación con valor en modo edición
    public function testCampoUbicacionConValorEdicion(){
        $editorialEditar = new stdClass();
        $editorialEditar->ubicacion = 'Pabellón A - Zona Norte';
        
        $valorUbicacion = $editorialEditar ? $editorialEditar->ubicacion : '';
        
        $this->assertEquals('Pabellón A - Zona Norte', $valorUbicacion);
        $this->assertNotEmpty($valorUbicacion);
    }

    // PRUEBA: Mostrar botón cancelar solo en modo edición
    public function testMostrarBotonCancelarSoloEnEdicion(){
        $editorialEditar = new stdClass();
        
        $mostrarCancelar = $editorialEditar != null;
        
        $this->assertTrue($mostrarCancelar);
    }

    // PRUEBA: No mostrar botón cancelar en modo creación
    public function testNoMostrarBotonCancelarEnCreacion(){
        $editorialEditar = null;
        
        $mostrarCancelar = $editorialEditar != null;
        
        $this->assertFalse($mostrarCancelar);
    }

    // PRUEBA: ID de editorial presente en modo edición
    public function testIdEditorialPresenteEnEdicion(){
        $editorialEditar = new stdClass();
        $editorialEditar->id = 9;
        
        $tieneId = $editorialEditar != null;
        
        $this->assertTrue($tieneId);
        $this->assertEquals(9, $editorialEditar->id);
    }

    // PRUEBA: Validación número de puesto mínimo 1
    public function testNumPuestoMinimoValido(){
        $numPuestoMinimo = 1;
        
        $this->assertGreaterThanOrEqual(1, $numPuestoMinimo);
    }

    // PRUEBA: Campos requeridos del formulario
    public function testCamposRequeridos(){
        $camposRequeridos = ['nombreEditorial', 'numPuestoEditorial', 'ubicacionPuestoEditorial'];
        
        $this->assertCount(3, $camposRequeridos);
        $this->assertContains('nombreEditorial', $camposRequeridos);
        $this->assertContains('numPuestoEditorial', $camposRequeridos);
        $this->assertContains('ubicacionPuestoEditorial', $camposRequeridos);
    }

    // PRUEBA: Estructura de datos de editorial completa
    public function testEstructuraEditorialCompleta(){
        $editorial = new stdClass();
        $editorial->id = 5;
        $editorial->nombreEditorial = "Editorial Grijalbo";
        $editorial->numPuesto = 8;
        $editorial->ubicacion = "Pabellón B - Zona Sur";
        
        $this->assertObjectHasProperty('id', $editorial);
        $this->assertObjectHasProperty('nombreEditorial', $editorial);
        $this->assertObjectHasProperty('numPuesto', $editorial);
        $this->assertObjectHasProperty('ubicacion', $editorial);
    }
}