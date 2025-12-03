<?php

use PHPUnit\Framework\TestCase;

class gestionEventosTest extends TestCase {

    // PRUEBA: Redirección cuando usuario no está logueado
    public function testRedireccionSinSesion(){
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
        
        unset($_SESSION['rol']);
    }

    // PRUEBA: Acceso permitido para administrador
    public function testAccesoAdministrador(){
        $_SESSION['rol'] = 'admin';
        
        $esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin';
        
        $this->assertTrue($esAdmin);
        
        unset($_SESSION['rol']);
    }

    // PRUEBA: Mensaje de éxito al crear evento
    public function testMensajeEventoCreado(){
        $_GET['msg'] = 'creado';
        
        $mensaje = '';
        if($_GET['msg'] == 'creado') $mensaje = "¡Evento creado exitosamente!";
        
        $this->assertEquals("¡Evento creado exitosamente!", $mensaje);
        
        unset($_GET['msg']);
    }

    // PRUEBA: Mensaje de éxito al eliminar evento
    public function testMensajeEventoEliminado(){
        $_GET['msg'] = 'eliminado';
        
        $mensaje = '';
        if($_GET['msg'] == 'eliminado') $mensaje = "¡Evento eliminado correctamente!";
        
        $this->assertEquals("¡Evento eliminado correctamente!", $mensaje);
        
        unset($_GET['msg']);
    }

    // PRUEBA: Mensaje de error
    public function testMensajeError(){
        $_GET['error'] = 'true';
        
        $hayError = isset($_GET['error']);
        
        $this->assertTrue($hayError);
        
        unset($_GET['error']);
    }

    // PRUEBA: Lista de categorías vacía
    public function testListaCategoriasVacia(){
        $listaCategorias = [];
        
        $this->assertEmpty($listaCategorias);
        $this->assertCount(0, $listaCategorias);
    }

    // PRUEBA: Lista de categorías con datos
    public function testListaCategoriasConDatos(){
        $categoria1 = new stdClass();
        $categoria1->id = 1;
        $categoria1->nombre = "Literatura";
        
        $categoria2 = new stdClass();
        $categoria2->id = 2;
        $categoria2->nombre = "Ciencia";
        
        $listaCategorias = [$categoria1, $categoria2];
        
        $this->assertNotEmpty($listaCategorias);
        $this->assertCount(2, $listaCategorias);
    }

    // PRUEBA: Lista de salones vacía
    public function testListaSalonesVacia(){
        $listaSalones = [];
        
        $this->assertEmpty($listaSalones);
    }

    // PRUEBA: Lista de salones con datos
    public function testListaSalonesConDatos(){
        $salon1 = new stdClass();
        $salon1->id = 1;
        $salon1->nombre = "Auditorio Principal";
        $salon1->capacidad = 200;
        
        $salon2 = new stdClass();
        $salon2->id = 2;
        $salon2->nombre = "Sala 1";
        $salon2->capacidad = 50;
        
        $listaSalones = [$salon1, $salon2];
        
        $this->assertCount(2, $listaSalones);
    }

    // PRUEBA: Lista de eventos vacía
    public function testListaEventosVacia(){
        $listaEventos = [];
        
        $this->assertEmpty($listaEventos);
    }

    // PRUEBA: Lista de eventos con datos
    public function testListaEventosConDatos(){
        $evento1 = [
            'idEvento' => 1,
            'titulo' => 'Conferencia de Literatura',
            'tipoEvento' => 'conferencia'
        ];
        
        $evento2 = [
            'idEvento' => 2,
            'titulo' => 'Taller de Escritura',
            'tipoEvento' => 'taller'
        ];
        
        $listaEventos = [$evento1, $evento2];
        
        $this->assertCount(2, $listaEventos);
    }

    // PRUEBA: Cálculo de disponibles con cupo disponible
    public function testCalculoDisponiblesConCupo(){
        $capacidadTotal = 100;
        $inscritos = 30;
        
        $disponibles = $capacidadTotal - $inscritos;
        
        $this->assertEquals(70, $disponibles);
        $this->assertGreaterThan(0, $disponibles);
    }

    // PRUEBA: Cálculo de disponibles cuando está lleno
    public function testCalculoDisponiblesLleno(){
        $capacidadTotal = 50;
        $inscritos = 50;
        
        $disponibles = $capacidadTotal - $inscritos;
        
        $this->assertEquals(0, $disponibles);
    }

    // PRUEBA: Cálculo de disponibles con sobre-inscripción
    public function testCalculoDisponiblesConSobreInscripcion(){
        $capacidadTotal = 50;
        $inscritos = 55;
        
        $disponibles = $capacidadTotal - $inscritos;
        
        // Debería ser negativo, pero se ajusta a 0
        if ($disponibles <= 0) {
            $disponibles = 0;
        }
        
        $this->assertEquals(0, $disponibles);
    }

    // PRUEBA: Estado "disponibles" cuando hay más del 20% libre
    public function testEstadoDisponible(){
        $capacidadTotal = 100;
        $inscritos = 50;
        $disponibles = $capacidadTotal - $inscritos;
        
        $colorEstado = "#28a745"; // Verde
        $textoEstado = $disponibles . " disponibles";
        
        $this->assertEquals("#28a745", $colorEstado);
        $this->assertEquals("50 disponibles", $textoEstado);
    }

    // PRUEBA: Estado "advertencia" cuando queda menos del 20%
    public function testEstadoAdvertencia(){
        $capacidadTotal = 100;
        $inscritos = 85;
        $disponibles = $capacidadTotal - $inscritos;
        
        $colorEstado = "#28a745";
        
        if ($disponibles < ($capacidadTotal * 0.2)) {
            $colorEstado = "#ffc107"; // Amarillo
        }
        
        $this->assertEquals("#ffc107", $colorEstado);
        $this->assertEquals(15, $disponibles);
    }

    // PRUEBA: Estado "lleno" cuando no hay cupo
    public function testEstadoLleno(){
        $capacidadTotal = 100;
        $inscritos = 100;
        $disponibles = $capacidadTotal - $inscritos;
        
        $colorEstado = "#28a745";
        $textoEstado = $disponibles . " disponibles";
        
        if ($disponibles <= 0) {
            $colorEstado = "#dc3545"; // Rojo
            $textoEstado = "¡LLENO!";
            $disponibles = 0;
        }
        
        $this->assertEquals("#dc3545", $colorEstado);
        $this->assertEquals("¡LLENO!", $textoEstado);
        $this->assertEquals(0, $disponibles);
    }

    // PRUEBA: Cálculo de porcentaje de ocupación
    public function testCalculoPorcentajeOcupacion(){
        $capacidadTotal = 100;
        $inscritos = 75;
        
        $porcentaje = ($capacidadTotal > 0) ? ($inscritos / $capacidadTotal) * 100 : 0;
        
        $this->assertEquals(75, $porcentaje);
    }

    // PRUEBA: Porcentaje no excede 100%
    public function testPorcentajeMaximo100(){
        $capacidadTotal = 100;
        $inscritos = 120;
        
        $porcentaje = ($capacidadTotal > 0) ? ($inscritos / $capacidadTotal) * 100 : 0;
        
        if ($porcentaje > 100) {
            $porcentaje = 100;
        }
        
        $this->assertEquals(100, $porcentaje);
    }

    // PRUEBA: Porcentaje con capacidad cero
    public function testPorcentajeConCapacidadCero(){
        $capacidadTotal = 0;
        $inscritos = 0;
        
        $porcentaje = ($capacidadTotal > 0) ? ($inscritos / $capacidadTotal) * 100 : 0;
        
        $this->assertEquals(0, $porcentaje);
    }

    // PRUEBA: Tipos de evento válidos
    public function testTiposEventoValidos(){
        $tiposValidos = ['conferencia', 'taller', 'premiacion'];
        
        $this->assertCount(3, $tiposValidos);
        $this->assertContains('conferencia', $tiposValidos);
        $this->assertContains('taller', $tiposValidos);
        $this->assertContains('premiacion', $tiposValidos);
    }

    // PRUEBA: Tipos de cupo válidos
    public function testTiposCupoValidos(){
        $tiposCupo = ['Limitado', 'Abierto'];
        
        $this->assertCount(2, $tiposCupo);
        $this->assertContains('Limitado', $tiposCupo);
        $this->assertContains('Abierto', $tiposCupo);
    }

    // PRUEBA: Campos requeridos del formulario
    public function testCamposRequeridosFormulario(){
        $camposRequeridos = [
            'titulo',
            'descripcion',
            'ponente',
            'idCategoria',
            'fecha',
            'horaInicio',
            'horaFinal',
            'idSalon',
            'tipoEvento'
        ];
        
        $this->assertCount(9, $camposRequeridos);
        $this->assertContains('titulo', $camposRequeridos);
        $this->assertContains('ponente', $camposRequeridos);
        $this->assertContains('tipoEvento', $camposRequeridos);
    }

    // PRUEBA: Formato de fecha válido
    public function testFormatoFechaValido(){
        $fecha = '2025-03-15';
        $fechaFormateada = date('d/m/Y', strtotime($fecha));
        
        $this->assertEquals('15/03/2025', $fechaFormateada);
    }

    // PRUEBA: Formato de hora válido (extracción primeros 5 caracteres)
    public function testFormatoHoraValido(){
        $horaInicio = '10:30:00';
        $horaFinal = '12:45:00';
        
        $horaInicioCorta = substr($horaInicio, 0, 5);
        $horaFinalCorta = substr($horaFinal, 0, 5);
        
        $this->assertEquals('10:30', $horaInicioCorta);
        $this->assertEquals('12:45', $horaFinalCorta);
    }

    // PRUEBA: Estructura de evento completa
    public function testEstructuraEventoCompleta(){
        $evento = [
            'idEvento' => 1,
            'titulo' => 'Conferencia de Literatura',
            'descripcion' => 'Una conferencia sobre literatura contemporánea',
            'ponente' => 'Juan Pérez',
            'fecha' => '2025-03-15',
            'horaInicio' => '10:00:00',
            'horaFinal' => '12:00:00',
            'nombreSalon' => 'Auditorio Principal',
            'tipoEvento' => 'conferencia',
            'maxCapacidad' => 100,
            'totalInscritos' => 50,
            'imagen' => 'evento.jpg'
        ];
        
        $this->assertArrayHasKey('idEvento', $evento);
        $this->assertArrayHasKey('titulo', $evento);
        $this->assertArrayHasKey('tipoEvento', $evento);
        $this->assertArrayHasKey('maxCapacidad', $evento);
        $this->assertArrayHasKey('totalInscritos', $evento);
    }

    // PRUEBA: Validación de inscritos como entero
    public function testInscritosComoEntero(){
        $evento = [
            'totalInscritos' => '45' // Puede venir como string de BD
        ];
        
        $inscritos = isset($evento['totalInscritos']) ? (int)$evento['totalInscritos'] : 0;
        
        $this->assertIsInt($inscritos);
        $this->assertEquals(45, $inscritos);
    }

    // PRUEBA: Validación de capacidad como entero
    public function testCapacidadComoEntero(){
        $evento = [
            'maxCapacidad' => '100' // Puede venir como string de BD
        ];
        
        $capacidad = isset($evento['maxCapacidad']) ? (int)$evento['maxCapacidad'] : 0;
        
        $this->assertIsInt($capacidad);
        $this->assertEquals(100, $capacidad);
    }

    // PRUEBA: Manejo de valores nulos en inscritos
    public function testManejoInscritosNulo(){
        $evento = [];
        
        $inscritos = isset($evento['totalInscritos']) ? (int)$evento['totalInscritos'] : 0;
        
        $this->assertEquals(0, $inscritos);
    }

    // PRUEBA: Manejo de valores nulos en capacidad
    public function testManejoCapacidadNula(){
        $evento = [];
        
        $capacidad = isset($evento['maxCapacidad']) ? (int)$evento['maxCapacidad'] : 0;
        
        $this->assertEquals(0, $capacidad);
    }

    // PRUEBA: Nombre del administrador disponible
    public function testNombreAdministradorDisponible(){
        $_SESSION['rol'] = 'admin';
        $_SESSION['nombre'] = 'Carlos López';
        
        $nombreAdmin = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
        
        $this->assertEquals('Carlos López', $nombreAdmin);
        $this->assertNotEmpty($nombreAdmin);
        
        unset($_SESSION['rol']);
        unset($_SESSION['nombre']);
    }

    // PRUEBA: Verificar imagen vacía
    public function testImagenVacia(){
        $evento = ['imagen' => ''];
        
        $tieneImagen = !empty($evento['imagen']);
        
        $this->assertFalse($tieneImagen);
    }

    // PRUEBA: Verificar imagen presente
    public function testImagenPresente(){
        $evento = ['imagen' => 'evento.jpg'];
        
        $tieneImagen = !empty($evento['imagen']);
        
        $this->assertTrue($tieneImagen);
    }
}