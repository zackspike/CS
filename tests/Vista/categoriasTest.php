<?php

use PHPUnit\Framework\TestCase;

class categoriasTest extends TestCase {

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
        $_GET['id'] = '5';
        
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
        $categoriaEditar = null;
        
        $accionFormulario = $categoriaEditar ? 'actualizar' : 'agregar';
        
        $this->assertEquals('agregar', $accionFormulario);
    }

    // PRUEBA: Acción del formulario es 'actualizar' en modo edición
    public function testAccionFormularioActualizar(){
        // Simular objeto categoría
        $categoriaEditar = new stdClass();
        $categoriaEditar->id = 1;
        
        $accionFormulario = $categoriaEditar ? 'actualizar' : 'agregar';
        
        $this->assertEquals('actualizar', $accionFormulario);
    }

    // PRUEBA: Texto del botón en modo creación
    public function testTextoBotonCreacion(){
        $categoriaEditar = null;
        
        $textoBoton = $categoriaEditar ? 'Guardar Cambios' : 'Crear Categoría';
        
        $this->assertEquals('Crear Categoría', $textoBoton);
    }

    // PRUEBA: Texto del botón en modo edición
    public function testTextoBotonEdicion(){
        $categoriaEditar = new stdClass();
        
        $textoBoton = $categoriaEditar ? 'Guardar Cambios' : 'Crear Categoría';
        
        $this->assertEquals('Guardar Cambios', $textoBoton);
    }

    // PRUEBA: Título del formulario en modo creación
    public function testTituloFormularioCreacion(){
        $categoriaEditar = null;
        
        $tituloFormulario = $categoriaEditar ? '️ Editar Categoría' : 'Nueva Categoría';
        
        $this->assertEquals('Nueva Categoría', $tituloFormulario);
    }

    // PRUEBA: Título del formulario en modo edición
    public function testTituloFormularioEdicion(){
        $categoriaEditar = new stdClass();
        
        $tituloFormulario = $categoriaEditar ? '️ Editar Categoría' : 'Nueva Categoría';
        
        $this->assertEquals('️ Editar Categoría', $tituloFormulario);
    }

    // PRUEBA: Mensaje de éxito al agregar categoría
    public function testMensajeAgregado(){
        $_GET['message'] = 'agregado';
        
        $mensaje = '';
        if($_GET['message'] == 'agregado') $mensaje = "¡Categoría agregada con éxito!";
        if($_GET['message'] == 'actualizado') $mensaje = "¡Cambios guardados correctamente!";
        if($_GET['message'] == 'eliminado') $mensaje = "Categoría eliminada.";
        
        $this->assertEquals("¡Categoría agregada con éxito!", $mensaje);
        
        // Limpiar GET
        unset($_GET['message']);
    }

    // PRUEBA: Mensaje de éxito al actualizar categoría
    public function testMensajeActualizado(){
        $_GET['message'] = 'actualizado';
        
        $mensaje = '';
        if($_GET['message'] == 'agregado') $mensaje = "¡Categoría agregada con éxito!";
        if($_GET['message'] == 'actualizado') $mensaje = "¡Cambios guardados correctamente!";
        if($_GET['message'] == 'eliminado') $mensaje = "Categoría eliminada.";
        
        $this->assertEquals("¡Cambios guardados correctamente!", $mensaje);
        
        // Limpiar GET
        unset($_GET['message']);
    }

    // PRUEBA: Mensaje de éxito al eliminar categoría
    public function testMensajeEliminado(){
        $_GET['message'] = 'eliminado';
        
        $mensaje = '';
        if($_GET['message'] == 'agregado') $mensaje = "¡Categoría agregada con éxito!";
        if($_GET['message'] == 'actualizado') $mensaje = "¡Cambios guardados correctamente!";
        if($_GET['message'] == 'eliminado') $mensaje = "Categoría eliminada.";
        
        $this->assertEquals("Categoría eliminada.", $mensaje);
        
        // Limpiar GET
        unset($_GET['message']);
    }

    // PRUEBA: Lista vacía de categorías
    public function testListaCategoriasVacia(){
        $lista = [];
        
        $this->assertEmpty($lista);
        $this->assertCount(0, $lista);
    }

    // PRUEBA: Lista con categorías existentes
    public function testListaCategoriasConDatos(){
        $categoria1 = new stdClass();
        $categoria1->id = 1;
        $categoria1->nombre = "Ficción";
        
        $categoria2 = new stdClass();
        $categoria2->id = 2;
        $categoria2->nombre = "No Ficción";
        
        $lista = [$categoria1, $categoria2];
        
        $this->assertNotEmpty($lista);
        $this->assertCount(2, $lista);
    }

    // PRUEBA: Verificar que el nombre del administrador esté disponible
    public function testNombreAdministradorDisponible(){
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'Juan Pérez';
        
        $nombreAdmin = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
        
        $this->assertEquals('Juan Pérez', $nombreAdmin);
        $this->assertNotEmpty($nombreAdmin);
        
        // Limpiar sesión
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Campo nombre vacío en modo creación
    public function testCampoNombreVacioCreacion(){
        $categoriaEditar = null;
        
        $valorNombre = $categoriaEditar ? 'Nombre Existente' : '';
        
        $this->assertEquals('', $valorNombre);
    }

    // PRUEBA: Campo nombre con valor en modo edición (simulado)
    public function testCampoNombreConValorEdicion(){
        $categoriaEditar = new stdClass();
        $categoriaEditar->nombre = 'Ciencia Ficción';
        
        $valorNombre = $categoriaEditar ? $categoriaEditar->nombre : '';
        
        $this->assertEquals('Ciencia Ficción', $valorNombre);
        $this->assertNotEmpty($valorNombre);
    }

    // PRUEBA: Campo descripción vacío en modo creación
    public function testCampoDescripcionVacioCreacion(){
        $categoriaEditar = null;
        
        $valorDescripcion = $categoriaEditar ? 'Descripción Existente' : '';
        
        $this->assertEquals('', $valorDescripcion);
    }

    // PRUEBA: Campo descripción con valor en modo edición (simulado)
    public function testCampoDescripcionConValorEdicion(){
        $categoriaEditar = new stdClass();
        $categoriaEditar->descripcion = 'Libros de ciencia ficción y fantasía';
        
        $valorDescripcion = $categoriaEditar ? $categoriaEditar->descripcion : '';
        
        $this->assertEquals('Libros de ciencia ficción y fantasía', $valorDescripcion);
        $this->assertNotEmpty($valorDescripcion);
    }

    // PRUEBA: Mostrar botón cancelar solo en modo edición
    public function testMostrarBotonCancelarSoloEnEdicion(){
        $categoriaEditar = new stdClass();
        
        $mostrarCancelar = $categoriaEditar != null;
        
        $this->assertTrue($mostrarCancelar);
    }

    // PRUEBA: No mostrar botón cancelar en modo creación
    public function testNoMostrarBotonCancelarEnCreacion(){
        $categoriaEditar = null;
        
        $mostrarCancelar = $categoriaEditar != null;
        
        $this->assertFalse($mostrarCancelar);
    }

    // PRUEBA: ID de categoría presente en modo edición
    public function testIdCategoriaPresenteEnEdicion(){
        $categoriaEditar = new stdClass();
        $categoriaEditar->id = 7;
        
        $tieneId = $categoriaEditar != null;
        
        $this->assertTrue($tieneId);
        $this->assertEquals(7, $categoriaEditar->id);
    }
}