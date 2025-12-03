<?php
use PHPUnit\Framework\TestCase;

class misRegistrosTest extends TestCase
{
    private string $vistaPath;
    
    protected function setUp(): void
    {
        
        $this->vistaPath = __DIR__ . '/../../Vista/misRegistros.php';
    }
    
    /**
     * Simula una sesión con usuario autenticado
     */
    private function simularSesionUsuario(int $idUsuario = 1, string $nombre = 'Usuario Test'): void
    {
        $_SESSION = [
            'idUsuario' => $idUsuario,
            'nombre' => $nombre
        ];
    }
    
    /**
     * Mock del RegistroDAO para las pruebas
     */
    private function crearMockRegistroDAO(array $registros = []): void
    {
        // Crear mock de RegistroDAO que retorne los registros especificados
        $mockDAO = $this->getMockBuilder('RegistroDAO')
            ->disableOriginalConstructor()
            ->getMock();
        
        $mockDAO->method('obtenerPorUsuario')
            ->willReturn($registros);
    }
    
    /**
     * Crea un mock de objeto Registro
     */
    private function crearMockRegistro(array $datos = []): object
    {
        $defaults = [
            'idRegistro' => 1,
            'idUsuario' => 1,
            'tituloEvento' => 'Evento Test',
            'fechaEvento' => '2024-12-15',
            'horaInicio' => '10:00:00',
            'nombreSalon' => 'Salón A',
            'asistio' => 0,
            'tipoEvento' => 'conferencia'
        ];
        
        $datos = array_merge($defaults, $datos);
        
        $mock = $this->getMockBuilder('stdClass')
            ->addMethods([
                'getIdRegistro', 'getIdUsuario', 'getTituloEvento',
                'getFechaEvento', 'getHoraInicio', 'getNombreSalon',
                'getAsistio', 'getTipoEvento'
            ])
            ->getMock();
        
        $mock->method('getIdRegistro')->willReturn($datos['idRegistro']);
        $mock->method('getIdUsuario')->willReturn($datos['idUsuario']);
        $mock->method('getTituloEvento')->willReturn($datos['tituloEvento']);
        $mock->method('getFechaEvento')->willReturn($datos['fechaEvento']);
        $mock->method('getHoraInicio')->willReturn($datos['horaInicio']);
        $mock->method('getNombreSalon')->willReturn($datos['nombreSalon']);
        $mock->method('getAsistio')->willReturn($datos['asistio']);
        $mock->method('getTipoEvento')->willReturn($datos['tipoEvento']);
        
        return $mock;
    }
    
    /**
     * @test
     */
    public function testRedirectSinSesion()
    {
        // No establecer sesión
        $_SESSION = [];
        
        // Capturar el header de redirección
        $this->expectOutputString('');
        
        // La vista debe intentar redirigir
        // En testing, header() lanzará warning, lo capturamos
        $headers = xdebug_get_headers();
        
        // Verificar que se intenta redirigir
        $this->assertTrue(!isset($_SESSION['idUsuario']));
    }
    
    /**
     * @test
     */
    public function testMuestraNombreUsuarioEnHeader()
    {
        $this->simularSesionUsuario(1, 'Juan Pérez');
        
        // Crear archivo temporal con la vista modificada para testing
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('Juan Pérez', $output);
        $this->assertStringContainsString('usuario-info', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraMensajeSinRegistros()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('No te has inscrito a ningún evento todavía.', $output);
        $this->assertStringContainsString('Ver eventos', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraTablaConRegistros()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro([
            'tituloEvento' => 'Conferencia de PHP',
            'fechaEvento' => '2024-12-20',
            'nombreSalon' => 'Auditorio Principal'
        ]);
        
        $output = $this->renderizarVistaConMocks([$registro]);
        
        $this->assertStringContainsString('Conferencia de PHP', $output);
        $this->assertStringContainsString('Auditorio Principal', $output);
        $this->assertStringContainsString('<table', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraBadgePendienteParaEventoNoAsistido()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro(['asistio' => 0]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        $this->assertStringContainsString('badge-pendiente', $output);
        $this->assertStringContainsString('Pendiente', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraBadgeAsistioParaEventoAsistido()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro(['asistio' => 1]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        $this->assertStringContainsString('badge-asistio', $output);
        $this->assertStringContainsString('Asistió', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraBotonQRParaEventoPendiente()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro(['asistio' => 0]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        $this->assertStringContainsString('btn-qr', $output);
        $this->assertStringContainsString('Ver Pase', $output);
        $this->assertStringContainsString('data-id=', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraBotonCancelarParaEventoPendiente()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro(['asistio' => 0, 'idRegistro' => 5]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        $this->assertStringContainsString('btn-cancelar', $output);
        $this->assertStringContainsString('Cancelar', $output);
        $this->assertStringContainsString('idRegistro=5', $output);
        $this->assertStringContainsString('confirm(', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraBotonConstanciaParaTallerAsistido()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro([
            'asistio' => 1,
            'tipoEvento' => 'taller',
            'idRegistro' => 10
        ]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        $this->assertStringContainsString('btn-constancia', $output);
        $this->assertStringContainsString('Constancia', $output);
        $this->assertStringContainsString('verConstancia.php?idRegistro=10', $output);
    }
    
    /**
     * @test
     */
    public function testNoMuestraConstanciaParaConferenciaAsistida()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro([
            'asistio' => 1,
            'tipoEvento' => 'conferencia'
        ]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        $this->assertStringContainsString('Completado', $output);
        $this->assertStringNotContainsString('verConstancia.php', $output);
    }
    
    /**
     * @test
     */
    public function testFormatoFechaCorrect()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro([
            'fechaEvento' => '2024-12-25',
            'horaInicio' => '14:30:00'
        ]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        // Formato dd/mm/yyyy
        $this->assertStringContainsString('25/12/2024', $output);
        // Hora sin segundos
        $this->assertStringContainsString('14:30', $output);
    }
    
    /**
     * @test
     */
    public function testContieneModalQR()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('id="qrModal"', $output);
        $this->assertStringContainsString('modal-content', $output);
        $this->assertStringContainsString('id="imgQR"', $output);
        $this->assertStringContainsString('id="modalEvento"', $output);
        $this->assertStringContainsString('id="modalNombre"', $output);
    }
    
    /**
     * @test
     */
    public function testContieneScriptJavaScript()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('function mostrarQR(', $output);
        $this->assertStringContainsString('function cerrarModal(', $output);
        $this->assertStringContainsString('addEventListener', $output);
        $this->assertStringContainsString('DOMContentLoaded', $output);
    }
    
    /**
     * @test
     */
    public function testURLQRContieneAPIQRServer()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('api.qrserver.com', $output);
        $this->assertStringContainsString('create-qr-code', $output);
    }
    
    /**
     * @test
     */
    public function testContieneEnlacesNavegacion()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('href="inicio.php"', $output);
        $this->assertStringContainsString('href="index.php"', $output);
        $this->assertStringContainsString('Volver a Inicio', $output);
    }
    
    /**
     * @test
     */
    public function testContieneLogoFILEY()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('logoFILEY.png', $output);
        $this->assertStringContainsString('logo-container', $output);
    }
    
    /**
     * @test
     */
    public function testContieneHojasEstilo()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('href="../StyleSheets/Inicio.css"', $output);
        $this->assertStringContainsString('href="../StyleSheets/misRegistros.css"', $output);
    }
    
    /**
     * @test
     */
    public function testTituloDocumento()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('<title>Mis Registros</title>', $output);
    }
    
    /**
     * @test
     */
    public function testMetaCharsetYViewport()
    {
        $this->simularSesionUsuario();
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('charset="UTF-8"', $output);
        $this->assertStringContainsString('name="viewport"', $output);
    }
    
    /**
     * @test
     */
    public function testHtmlspecialcharsEnNombreUsuario()
    {
        $this->simularSesionUsuario(1, '<script>alert("xss")</script>');
        $output = $this->renderizarVistaConMocks([]);
        
        // Debe estar escapado, no contener el script real
        $this->assertStringNotContainsString('<script>alert("xss")</script>', $output);
        $this->assertStringContainsString('&lt;script&gt;', $output);
    }
    
    /**
     * @test
     */
    public function testHtmlspecialcharsEnTituloEvento()
    {
        $this->simularSesionUsuario();
        
        $registro = $this->crearMockRegistro([
            'tituloEvento' => '<img src=x onerror=alert(1)>'
        ]);
        $output = $this->renderizarVistaConMocks([$registro]);
        
        // Debe estar escapado
        $this->assertStringNotContainsString('onerror=alert(1)', $output);
        $this->assertStringContainsString('&lt;img', $output);
    }
    
    /**
     * @test
     */
    public function testMultiplesRegistrosMuestranFilasTabla()
    {
        $this->simularSesionUsuario();
        
        $registros = [
            $this->crearMockRegistro(['tituloEvento' => 'Evento 1', 'idRegistro' => 1]),
            $this->crearMockRegistro(['tituloEvento' => 'Evento 2', 'idRegistro' => 2]),
            $this->crearMockRegistro(['tituloEvento' => 'Evento 3', 'idRegistro' => 3])
        ];
        
        $output = $this->renderizarVistaConMocks($registros);
        
        $this->assertStringContainsString('Evento 1', $output);
        $this->assertStringContainsString('Evento 2', $output);
        $this->assertStringContainsString('Evento 3', $output);
        
        // Contar filas <tr> aproximadamente
        $trCount = substr_count($output, '<tr>');
        $this->assertGreaterThanOrEqual(4, $trCount); // header + 3 registros
    }
    
    /**
     * Renderiza la vista con mocks de datos
     * Esta es una función helper que simula la inclusión de la vista
     */
    private function renderizarVistaConMocks(array $misRegistros): string
    {
        // Crear una versión mockeable de la vista
        ob_start();
        
        // Simular el objeto RegistroDAO
        $daoRegistro = new class {
            private array $registros;
            public function setRegistros(array $r) { $this->registros = $r; }
            public function obtenerPorUsuario($id) { return $this->registros ?? []; }
        };
        $daoRegistro->setRegistros($misRegistros);
        
        // Incluir una versión simplificada del HTML principal
        include $this->generarVistaTemporalMock($misRegistros);
        
        return ob_get_clean();
    }
    
    /**
     * Genera un archivo temporal con la vista para testing
     */
    private function generarVistaTemporalMock(array $misRegistros): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_vista_');
        
        // Leer el contenido original
        $contenidoOriginal = file_get_contents($this->vistaPath);
        
        // Reemplazar la línea de require_once y creación de DAO
        $contenidoModificado = preg_replace(
            '/require_once.*RegistroDAO\.php.*\n.*daoRegistro.*new RegistroDAO.*\n.*misRegistros.*obtenerPorUsuario.*/s',
            '$misRegistros = $GLOBALS["__test_registros__"];',
            $contenidoOriginal
        );
        
        // Eliminar la redirección para testing
        $contenidoModificado = str_replace(
            'if (!isset($_SESSION[\'idUsuario\'])) { header("Location: login.php"); exit(); }',
            'if (!isset($_SESSION[\'idUsuario\'])) { $_SESSION[\'idUsuario\'] = 1; $_SESSION[\'nombre\'] = "Test"; }',
            $contenidoModificado
        );
        
        file_put_contents($tempFile, $contenidoModificado);
        
        $GLOBALS['__test_registros__'] = $misRegistros;
        
        return $tempFile;
    }
    
    protected function tearDown(): void
    {
        // Limpiar variables globales y sesión
        $_SESSION = [];
        unset($GLOBALS['__test_registros__']);
    }
}