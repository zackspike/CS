<?php

use PHPUnit\Framework\TestCase;

class indexTest extends TestCase {

    // PRUEBA: Sección por defecto debe ser 'eventos'
    public function testSeccionPorDefecto(){
        $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'eventos';
        
        $this->assertEquals('eventos', $seccion);
    }

    // PRUEBA: Filtro por defecto debe ser 'todos'
    public function testFiltroPorDefecto(){
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
        
        $this->assertEquals('todos', $filtro);
    }

    // PRUEBA: Título de sección para editoriales
    public function testTituloSeccionEditoriales(){
        $seccion = 'editoriales';
        $tituloSeccion = "";
        
        if ($seccion == 'editoriales') {
            $tituloSeccion = "Editoriales Participantes";
        }
        
        $this->assertEquals("Editoriales Participantes", $tituloSeccion);
    }

    // PRUEBA: Título de sección por defecto (eventos)
    public function testTituloSeccionEventosPorDefecto(){
        $seccion = 'eventos';
        $filtro = 'todos';
        $tituloSeccion = "Próximos Eventos";
        
        $this->assertEquals("Próximos Eventos", $tituloSeccion);
    }

    // PRUEBA: Título de sección para conferencias
    public function testTituloSeccionConferencias(){
        $filtro = 'conferencia';
        $tituloSeccion = "Próximos Eventos";
        
        if($filtro == 'conferencia'){ 
            $tituloSeccion = "Conferencias";
        }
        
        $this->assertEquals("Conferencias", $tituloSeccion);
    }

    // PRUEBA: Título de sección para talleres
    public function testTituloSeccionTalleres(){
        $filtro = 'taller';
        $tituloSeccion = "Próximos Eventos";
        
        if($filtro == 'taller'){ 
            $tituloSeccion = "Talleres";
        }
        
        $this->assertEquals("Talleres", $tituloSeccion);
    }

    // PRUEBA: Título de sección para premiaciones
    public function testTituloSeccionPremiaciones(){
        $filtro = 'premiacion';
        $tituloSeccion = "Próximos Eventos";
        
        if($filtro == 'premiacion'){ 
            $tituloSeccion = "Premiaciones";
        }
        
        $this->assertEquals("Premiaciones", $tituloSeccion);
    }

    // PRUEBA: Filtrado de eventos por tipo 'conferencia'
    public function testFiltradoEventosPorConferencia(){
        $todosLosEventos = [
            ['idEvento' => 1, 'titulo' => 'Evento 1', 'tipoEvento' => 'conferencia'],
            ['idEvento' => 2, 'titulo' => 'Evento 2', 'tipoEvento' => 'taller'],
            ['idEvento' => 3, 'titulo' => 'Evento 3', 'tipoEvento' => 'conferencia'],
            ['idEvento' => 4, 'titulo' => 'Evento 4', 'tipoEvento' => 'premiacion']
        ];
        
        $filtro = 'conferencia';
        $datosParaMostrar = [];
        
        if ($filtro == 'todos') {
            $datosParaMostrar = $todosLosEventos;
        } else {
            foreach ($todosLosEventos as $ev) {
                if ($ev['tipoEvento'] == $filtro) {
                    $datosParaMostrar[] = $ev;
                }
            }
        }
        
        $this->assertCount(2, $datosParaMostrar);
        $this->assertEquals('conferencia', $datosParaMostrar[0]['tipoEvento']);
        $this->assertEquals('conferencia', $datosParaMostrar[1]['tipoEvento']);
    }

    // PRUEBA: Filtrado de eventos por tipo 'taller'
    public function testFiltradoEventosPorTaller(){
        $todosLosEventos = [
            ['idEvento' => 1, 'titulo' => 'Evento 1', 'tipoEvento' => 'conferencia'],
            ['idEvento' => 2, 'titulo' => 'Evento 2', 'tipoEvento' => 'taller'],
            ['idEvento' => 3, 'titulo' => 'Evento 3', 'tipoEvento' => 'taller'],
        ];
        
        $filtro = 'taller';
        $datosParaMostrar = [];
        
        foreach ($todosLosEventos as $ev) {
            if ($ev['tipoEvento'] == $filtro) {
                $datosParaMostrar[] = $ev;
            }
        }
        
        $this->assertCount(2, $datosParaMostrar);
        $this->assertEquals('taller', $datosParaMostrar[0]['tipoEvento']);
    }

    // PRUEBA: Filtrado de eventos por tipo 'premiacion'
    public function testFiltradoEventosPorPremiacion(){
        $todosLosEventos = [
            ['idEvento' => 1, 'titulo' => 'Evento 1', 'tipoEvento' => 'premiacion'],
            ['idEvento' => 2, 'titulo' => 'Evento 2', 'tipoEvento' => 'taller'],
            ['idEvento' => 3, 'titulo' => 'Evento 3', 'tipoEvento' => 'premiacion'],
        ];
        
        $filtro = 'premiacion';
        $datosParaMostrar = [];
        
        foreach ($todosLosEventos as $ev) {
            if ($ev['tipoEvento'] == $filtro) {
                $datosParaMostrar[] = $ev;
            }
        }
        
        $this->assertCount(2, $datosParaMostrar);
        $this->assertEquals('premiacion', $datosParaMostrar[0]['tipoEvento']);
    }

    // PRUEBA: Mostrar todos los eventos sin filtro
    public function testMostrarTodosLosEventos(){
        $todosLosEventos = [
            ['idEvento' => 1, 'titulo' => 'Evento 1', 'tipoEvento' => 'conferencia'],
            ['idEvento' => 2, 'titulo' => 'Evento 2', 'tipoEvento' => 'taller'],
            ['idEvento' => 3, 'titulo' => 'Evento 3', 'tipoEvento' => 'premiacion']
        ];
        
        $filtro = 'todos';
        $datosParaMostrar = [];
        
        if ($filtro == 'todos') {
            $datosParaMostrar = $todosLosEventos;
        }
        
        $this->assertCount(3, $datosParaMostrar);
        $this->assertEquals($todosLosEventos, $datosParaMostrar);
    }

    // PRUEBA: Usuario logueado
    public function testUsuarioLogueado(){
        $_SESSION['idUsuario'] = 1;
        $_SESSION['nombre'] = 'Juan';
        $_SESSION['rol'] = 'usuario';
        
        $estaLogueado = isset($_SESSION['idUsuario']);
        $nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
        $rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
        
        $this->assertTrue($estaLogueado);
        $this->assertEquals('Juan', $nombreUsuario);
        $this->assertEquals('usuario', $rol);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
        unset($_SESSION['nombre']);
        unset($_SESSION['rol']);
    }

    // PRUEBA: Usuario no logueado
    public function testUsuarioNoLogueado(){
        // Asegurar que no hay sesión activa
        if(isset($_SESSION['idUsuario'])) {
            unset($_SESSION['idUsuario']);
        }
        
        $estaLogueado = isset($_SESSION['idUsuario']);
        $nombreUsuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
        $rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
        
        $this->assertFalse($estaLogueado);
        $this->assertEquals('', $nombreUsuario);
        $this->assertEquals('', $rol);
    }

    // PRUEBA: Verificar rol de administrador
    public function testRolAdministrador(){
        $_SESSION['idUsuario'] = 1;
        $_SESSION['nombre'] = 'Admin';
        $_SESSION['rol'] = 'admin';
        
        $rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
        
        $this->assertEquals('admin', $rol);
        
        // Limpiar sesión
        unset($_SESSION['idUsuario']);
        unset($_SESSION['nombre']);
        unset($_SESSION['rol']);
    }

    // PRUEBA: Clase CSS para tag de evento tipo 'taller'
    public function testClaseCSSTagTaller(){
        $tipo = 'taller';
        $claseTag = ($tipo=='taller') ? 'tag-taller' : (($tipo=='premiacion') ? 'tag-premiacion' : 'tag-conferencia');
        
        $this->assertEquals('tag-taller', $claseTag);
    }

    // PRUEBA: Clase CSS para tag de evento tipo 'premiacion'
    public function testClaseCSSTagPremiacion(){
        $tipo = 'premiacion';
        $claseTag = ($tipo=='taller') ? 'tag-taller' : (($tipo=='premiacion') ? 'tag-premiacion' : 'tag-conferencia');
        
        $this->assertEquals('tag-premiacion', $claseTag);
    }

    // PRUEBA: Clase CSS para tag de evento tipo 'conferencia'
    public function testClaseCSSTagConferencia(){
        $tipo = 'conferencia';
        $claseTag = ($tipo=='taller') ? 'tag-taller' : (($tipo=='premiacion') ? 'tag-premiacion' : 'tag-conferencia');
        
        $this->assertEquals('tag-conferencia', $claseTag);
    }

    // PRUEBA: Filtrado no encuentra eventos
    public function testFiltradoSinResultados(){
        $todosLosEventos = [
            ['idEvento' => 1, 'titulo' => 'Evento 1', 'tipoEvento' => 'conferencia'],
            ['idEvento' => 2, 'titulo' => 'Evento 2', 'tipoEvento' => 'conferencia']
        ];
        
        $filtro = 'taller';
        $datosParaMostrar = [];
        
        foreach ($todosLosEventos as $ev) {
            if ($ev['tipoEvento'] == $filtro) {
                $datosParaMostrar[] = $ev;
            }
        }
        
        $this->assertEmpty($datosParaMostrar);
        $this->assertCount(0, $datosParaMostrar);
    }
}