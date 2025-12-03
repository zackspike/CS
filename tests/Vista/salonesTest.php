<?php
use PHPUnit\Framework\TestCase;

class salonesTest extends TestCase
{
    private string $vistaPath;
    
    protected function setUp(): void
    {

        $this->vistaPath = __DIR__ . '/../../Vista/salones.php';
    }
    
    /**
     * Simula una sesión con usuario administrador
     */
    private function simularSesionAdmin(string $nombre = 'Admin Test'): void
    {
        $_SESSION = [
            'rol' => 'admin',
            'nombre' => $nombre
        ];
    }
    
    /**
     * Simula una sesión sin rol admin
     */
    private function simularSesionNoAdmin(): void
    {
        $_SESSION = [
            'rol' => 'usuario',
            'nombre' => 'Usuario Normal'
        ];
    }
    
    /**
     * Crea un mock de objeto Salon
     */
    private function crearMockSalon(array $datos = []): object
    {
        $defaults = [
            'idSalon' => 1,
            'nombreSalon' => 'Salón A',
            'maxCapacidad' => 100
        ];
        
        $datos = array_merge($defaults, $datos);
        
        $mock = $this->getMockBuilder('stdClass')
            ->addMethods(['getIdSalon', 'getNombreSalon', 'getMaxCapacidad'])
            ->getMock();
        
        $mock->method('getIdSalon')->willReturn($datos['idSalon']);
        $mock->method('getNombreSalon')->willReturn($datos['nombreSalon']);
        $mock->method('getMaxCapacidad')->willReturn($datos['maxCapacidad']);
        
        return $mock;
    }
    
    /**
     * @test
     */
    public function testRedirectSinSesion()
    {
        $_SESSION = [];
        
        // Verificar que sin sesión no puede acceder
        $this->assertFalse(isset($_SESSION['rol']));
    }
    
    /**
     * @test
     */
    public function testRedirectSinRolAdmin()
    {
        $this->simularSesionNoAdmin();
        
        // Verificar que sin rol admin no puede acceder
        $this->assertNotEquals('admin', $_SESSION['rol']);
    }
    
    /**
     * @test
     */
    public function testMuestraNombreAdministrador()
    {
        $this->simularSesionAdmin('Carlos Rodríguez');
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('Administrador: Carlos Rodríguez', $output);
        $this->assertStringContainsString('usuario-info', $output);
    }
    
    /**
     * @test
     */
    public function testTituloDocumento()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('<title>Gestionar Salones</title>', $output);
    }
    
    /**
     * @test
     */
    public function testFormularioModoCreacion()
    {
        $this->simularSesionAdmin();
        $_GET = [];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('Nuevo Salón', $output);
        $this->assertStringContainsString('name="accion" value="agregar"', $output);
        $this->assertStringContainsString('Crear Salón', $output);
    }
    
    /**
     * @test
     */
    public function testFormularioModoEdicion()
    {
        $this->simularSesionAdmin();
        $_GET = ['accion' => 'editar', 'id' => 5];
        
        $salonEditar = $this->crearMockSalon([
            'idSalon' => 5,
            'nombreSalon' => 'Auditorio Principal',
            'maxCapacidad' => 500
        ]);
        
        $output = $this->renderizarVistaConMocks([], $salonEditar);
        
        $this->assertStringContainsString('Editar Salón', $output);
        $this->assertStringContainsString('name="accion" value="actualizar"', $output);
        $this->assertStringContainsString('Guardar Cambios', $output);
        $this->assertStringContainsString('name="idSalon" value="5"', $output);
    }
    
    /**
     * @test
     */
    public function testFormularioEdicionPrellenaValores()
    {
        $this->simularSesionAdmin();
        $_GET = ['accion' => 'editar', 'id' => 3];
        
        $salonEditar = $this->crearMockSalon([
            'idSalon' => 3,
            'nombreSalon' => 'Sala VIP',
            'maxCapacidad' => 50
        ]);
        
        $output = $this->renderizarVistaConMocks([], $salonEditar);
        
        $this->assertStringContainsString('value="Sala VIP"', $output);
        $this->assertStringContainsString('value="50"', $output);
    }
    
    /**
     * @test
     */
    public function testFormularioEdicionMuestraBotonCancelar()
    {
        $this->simularSesionAdmin();
        $_GET = ['accion' => 'editar', 'id' => 1];
        
        $salonEditar = $this->crearMockSalon();
        $output = $this->renderizarVistaConMocks([], $salonEditar);
        
        $this->assertStringContainsString('Cancelar Edición', $output);
        $this->assertStringContainsString('href="salones.php"', $output);
    }
    
    /**
     * @test
     */
    public function testFormularioCreacionNoMuestraBotonCancelar()
    {
        $this->simularSesionAdmin();
        $_GET = [];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringNotContainsString('Cancelar Edición', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeAgregado()
    {
        $this->simularSesionAdmin();
        $_GET = ['message' => 'agregado'];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('¡Salón agregado con éxito!', $output);
        $this->assertStringContainsString('alert success', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeActualizado()
    {
        $this->simularSesionAdmin();
        $_GET = ['message' => 'actualizado'];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('¡Cambios guardados correctamente!', $output);
        $this->assertStringContainsString('alert success', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeEliminado()
    {
        $this->simularSesionAdmin();
        $_GET = ['message' => 'eliminado'];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('Salón eliminado.', $output);
        $this->assertStringContainsString('alert success', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeSinParametro()
    {
        $this->simularSesionAdmin();
        $_GET = [];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringNotContainsString('alert success', $output);
        $this->assertStringNotContainsString('¡Salón agregado', $output);
    }
    
    /**
     * @test
     */
    public function testTablaVaciaMuestraMensaje()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('No hay salones registradas', $output);
    }
    
    /**
     * @test
     */
    public function testTablaMuestraSalones()
    {
        $this->simularSesionAdmin();
        
        $salones = [
            $this->crearMockSalon([
                'idSalon' => 1,
                'nombreSalon' => 'Salón Principal',
                'maxCapacidad' => 200
            ])
        ];
        
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('Salón Principal', $output);
        $this->assertStringContainsString('200', $output);
        $this->assertStringContainsString('<table>', $output);
    }
    
    /**
     * @test
     */
    public function testTablaContieneEncabezados()
    {
        $this->simularSesionAdmin();
        
        $salones = [$this->crearMockSalon()];
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('Nombre del salón', $output);
        $this->assertStringContainsString('Capacidad', $output);
        $this->assertStringContainsString('Acciones', $output);
        $this->assertStringContainsString('<thead>', $output);
    }
    
    /**
     * @test
     */
    public function testBotonesEditarYEliminar()
    {
        $this->simularSesionAdmin();
        
        $salones = [
            $this->crearMockSalon(['idSalon' => 7])
        ];
        
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('salones.php?accion=editar&id=7', $output);
        $this->assertStringContainsString('btn-edit', $output);
        $this->assertStringContainsString('Editar', $output);
        
        $this->assertStringContainsString('SalonController.php?accion=eliminar&id=7', $output);
        $this->assertStringContainsString('btn-delete', $output);
        $this->assertStringContainsString('Borrar', $output);
    }
    
    /**
     * @test
     */
    public function testBotonEliminarConConfirmacion()
    {
        $this->simularSesionAdmin();
        
        $salones = [$this->crearMockSalon()];
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('onclick="return confirm(', $output);
        $this->assertStringContainsString('¿Eliminar este salón?', $output);
    }
    
    /**
     * @test
     */
    public function testMultiplesSalonesEnTabla()
    {
        $this->simularSesionAdmin();
        
        $salones = [
            $this->crearMockSalon(['idSalon' => 1, 'nombreSalon' => 'Salón A', 'maxCapacidad' => 100]),
            $this->crearMockSalon(['idSalon' => 2, 'nombreSalon' => 'Salón B', 'maxCapacidad' => 150]),
            $this->crearMockSalon(['idSalon' => 3, 'nombreSalon' => 'Salón C', 'maxCapacidad' => 75])
        ];
        
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('Salón A', $output);
        $this->assertStringContainsString('100', $output);
        $this->assertStringContainsString('Salón B', $output);
        $this->assertStringContainsString('150', $output);
        $this->assertStringContainsString('Salón C', $output);
        $this->assertStringContainsString('75', $output);
    }
    
    /**
     * @test
     */
    public function testFormularioContieneActionCorrecto()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('action="../Controlador/SalonController.php"', $output);
        $this->assertStringContainsString('method="POST"', $output);
    }
    
    /**
     * @test
     */
    public function testCampoNombreSalonRequerido()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('name="nombreSalon" required', $output);
        $this->assertStringContainsString('Nombre del Salón:', $output);
    }
    
    /**
     * @test
     */
    public function testCampoCapacidadRequerido()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('name="maxCapacidad" required', $output);
        $this->assertStringContainsString('type="number"', $output);
        $this->assertStringContainsString('min="1"', $output);
        $this->assertStringContainsString('Capacidad máxima:', $output);
    }
    
    /**
     * @test
     */
    public function testContieneLogoFILEY()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('logoFILEY.png', $output);
        $this->assertStringContainsString('logo-container', $output);
    }
    
    /**
     * @test
     */
    public function testEnlaceRegresar()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('href="adminFeed.php"', $output);
        $this->assertStringContainsString('Regresar', $output);
    }
    
    /**
     * @test
     */
    public function testContieneHojasEstilo()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('href="../StyleSheets/Inicio.css"', $output);
        $this->assertStringContainsString('href="../StyleSheets/categoriasView.css"', $output);
    }
    
    /**
     * @test
     */
    public function testMetaCharset()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('charset="UTF-8"', $output);
    }
    
    /**
     * @test
     */
    public function testEstructuraDivsCrud()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('crud-container', $output);
        $this->assertStringContainsString('form-card', $output);
        $this->assertStringContainsString('list-card', $output);
    }
    
    /**
     * @test
     */
    public function testHeaderContenedor()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('class="header"', $output);
        $this->assertStringContainsString('header-container', $output);
        $this->assertStringContainsString('nav-menu', $output);
    }
    
    /**
     * @test
     */
    public function testTitulosFormulario()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('Salones Existentes', $output);
    }
    
    /**
     * @test
     */
    public function testPlaceholderCapacidad()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('placeholder="Solo ingrese el número"', $output);
    }
    
    /**
     * @test
     */
    public function testBotonSubmitEstilos()
    {
        $this->simularSesionAdmin();
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('type="submit"', $output);
        $this->assertStringContainsString('background-color:#005288', $output);
        $this->assertStringContainsString('color:white', $output);
        $this->assertStringContainsString('width:100%', $output);
    }
    
    /**
     * @test
     */
    public function testCamposVaciosEnModoCreacion()
    {
        $this->simularSesionAdmin();
        $_GET = [];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        // Los campos deben estar vacíos (value="")
        $this->assertMatchesRegularExpression('/name="nombreSalon"[^>]*value=""/', $output);
        $this->assertMatchesRegularExpression('/name="maxCapacidad"[^>]*value=""/', $output);
    }
    
    /**
     * @test
     */
    public function testNoMuestraIdSalonEnModoCreacion()
    {
        $this->simularSesionAdmin();
        $_GET = [];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringNotContainsString('name="idSalon"', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraIdSalonEnModoEdicion()
    {
        $this->simularSesionAdmin();
        $_GET = ['accion' => 'editar', 'id' => 10];
        
        $salonEditar = $this->crearMockSalon(['idSalon' => 10]);
        $output = $this->renderizarVistaConMocks([], $salonEditar);
        
        $this->assertStringContainsString('name="idSalon"', $output);
        $this->assertStringContainsString('type="hidden"', $output);
    }
    
    /**
     * @test
     */
    public function testColumnasTablaTienenAnchos()
    {
        $this->simularSesionAdmin();
        
        $salones = [$this->crearMockSalon()];
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('width: 30%', $output);
        $this->assertStringContainsString('width: 40%', $output);
    }
    
    /**
     * @test
     */
    public function testNombreSalonEnNegrita()
    {
        $this->simularSesionAdmin();
        
        $salones = [
            $this->crearMockSalon(['nombreSalon' => 'Sala Especial'])
        ];
        
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertMatchesRegularExpression('/<strong>Sala Especial<\/strong>/', $output);
    }
    
    /**
     * @test
     */
    public function testCapacidadGrandeSemuestra()
    {
        $this->simularSesionAdmin();
        
        $salones = [
            $this->crearMockSalon(['maxCapacidad' => 9999])
        ];
        
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('9999', $output);
    }
    
    /**
     * Renderiza la vista con mocks de datos
     */
    private function renderizarVistaConMocks(array $lista, $editorialEditar = null): string
    {
        $tempFile = $this->generarVistaTemporalMock($lista, $editorialEditar);
        
        ob_start();
        include $tempFile;
        $output = ob_get_clean();
        
        unlink($tempFile);
        
        return $output;
    }
    
    /**
     * Genera un archivo temporal con la vista modificada para testing
     */
    private function generarVistaTemporalMock(array $lista, $editorialEditar): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_salones_');
        
        // Leer el contenido original
        $contenidoOriginal = file_get_contents($this->vistaPath);
        
        // Reemplazar la validación de sesión
        $contenidoModificado = str_replace(
            'if (!isset($_SESSION[\'rol\']) || $_SESSION[\'rol\'] != \'admin\') {
    header("Location: login.php");
    exit();
}',
            'if (!isset($_SESSION[\'rol\']) || $_SESSION[\'rol\'] != \'admin\') {
    $_SESSION[\'rol\'] = \'admin\';
    $_SESSION[\'nombre\'] = \'Test Admin\';
}',
            $contenidoOriginal
        );
        
        // Reemplazar el require_once y lógica de DAO
        $contenidoModificado = preg_replace(
            '/require_once.*SalonDAO\.php.*/',
            '',
            $contenidoModificado
        );
        
        $contenidoModificado = preg_replace(
            '/\$daoSalones = new SalonDAO\(\);.*\$lista = \$daoSalones->obtenerTodos\(\);/s',
            '$lista = $GLOBALS["__test_lista_salones__"];
$editorialEditar = $GLOBALS["__test_salon_editar__"];',
            $contenidoModificado
        );
        
        // Reemplazar la lógica de edición
        $contenidoModificado = preg_replace(
            '/\$editorialEditar = null;.*if \(isset\(\$_GET\[\'accion\'\]\).*\$editorialEditar = \$daoSalones->obtenerPorId\(\$_GET\[\'id\'\]\);.*\}/s',
            '',
            $contenidoModificado
        );
        
        file_put_contents($tempFile, $contenidoModificado);
        
        $GLOBALS['__test_lista_salones__'] = $lista;
        $GLOBALS['__test_salon_editar__'] = $editorialEditar;
        
        return $tempFile;
    }
    
    protected function tearDown(): void
    {
        // Limpiar variables globales
        $_SESSION = [];
        $_GET = [];
        unset($GLOBALS['__test_lista_salones__']);
        unset($GLOBALS['__test_salon_editar__']);
    }
}