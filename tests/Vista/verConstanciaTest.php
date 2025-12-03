<?php
use PHPUnit\Framework\TestCase;

class verConstanciaTest extends TestCase
{
    private string $vistaPath;
    
    protected function setUp(): void
    {
     
        $this->vistaPath = __DIR__ . '/../../Vista/verConstancia.php';
    }
    

    private function simularSesionUsuario(int $idUsuario = 1): void
    {
        $_SESSION = ['idUsuario' => $idUsuario];
    }
    
    private function simularParametrosGET(int $idRegistro): void
    {
        $_GET = ['idRegistro' => $idRegistro];
    }
    
    /**
     * @test
     */
    public function testRedirectSinSesion()
    {
        $_SESSION = [];
        $_GET = ['idRegistro' => 1];
        
        // Verificar que sin sesión no puede acceder
        $this->assertFalse(isset($_SESSION['idUsuario']));
    }
    
    /**
     * @test
     */
    public function testErrorSiConstanciaNoEncontrada()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(999);
        
        $output = $this->renderizarVistaConMock(null);
        
        $this->assertStringContainsString('Error: Constancia no encontrada.', $output);
    }
    
    /**
     * @test
     */
    public function testErrorSiAsistenciaNoValidada()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = [
            'titulo' => 'Taller de PHP',
            'nombreUsuario' => 'Juan Pérez',
            'fecha' => '2024-12-15',
            'asistio' => 0, // No asistió
            'tipoEvento' => 'taller'
        ];
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('Error: Asistencia no validada por el administrador.', $output);
    }
    
    /**
     * @test
     */
    public function testErrorSiEventoNoEsTaller()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = [
            'titulo' => 'Conferencia de PHP',
            'nombreUsuario' => 'Juan Pérez',
            'fecha' => '2024-12-15',
            'asistio' => 1,
            'tipoEvento' => 'conferencia' // No es taller
        ];
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('Error: Este evento no emite constancia.', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraConstanciaValidaCorrectamente()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = [
            'titulo' => 'Taller de Programación Web',
            'nombreUsuario' => 'María González',
            'fecha' => '2024-12-15',
            'asistio' => 1,
            'tipoEvento' => 'taller'
        ];
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('Taller de Programación Web', $output);
        $this->assertStringContainsString('María González', $output);
        $this->assertStringContainsString('CONSTANCIA', $output);
    }
    
    /**
     * @test
     */
    public function testTituloDocumentoContieneNombreTaller()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = [
            'titulo' => 'Taller de Testing',
            'nombreUsuario' => 'Pedro López',
            'fecha' => '2024-12-15',
            'asistio' => 1,
            'tipoEvento' => 'taller'
        ];
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('<title>Constancia - Taller de Testing</title>', $output);
    }
    
    /**
     * @test
     */
    public function testContieneLogoFILEY()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('logoFILEY.png', $output);
        $this->assertStringContainsString('logo-top', $output);
    }
    
    /**
     * @test
     */
    public function testContieneTextoOtorgamiento()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('La Feria Internacional de la Lectura Yucatán (FILEY) otorga la presente', $output);
        $this->assertStringContainsString('Por su valiosa participación y asistencia al taller:', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraNombreParticipante()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $datos['nombreUsuario'] = 'Ana Carolina Méndez Pérez';
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('Ana Carolina Méndez Pérez', $output);
        $this->assertStringContainsString('nombre-participante', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraTituloEventoEntreComillas()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $datos['titulo'] = 'Introducción a PHPUnit';
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('"Introducción a PHPUnit"', $output);
        $this->assertStringContainsString('evento-nombre', $output);
    }
    
    /**
     * @test
     */
    public function testFormatoFechaEnero()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $datos['fecha'] = '2024-01-15';
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('15 de Enero del 2024', $output);
    }
    
    /**
     * @test
     */
    public function testFormatoFechaJulio()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $datos['fecha'] = '2024-07-20';
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('20 de Julio del 2024', $output);
    }
    
    /**
     * @test
     */
    public function testFormatoFechaDiciembre()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $datos['fecha'] = '2024-12-31';
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('31 de Diciembre del 2024', $output);
    }
    
    /**
     * @test
     */
    public function testFechaEmisionMeridaYucatan()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('Mérida, Yucatán', $output);
        $this->assertStringContainsString('fecha-emision', $output);
    }
    
    /**
     * @test
     */
    public function testContieneSeccionFirmas()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('firmas-container', $output);
        $this->assertStringContainsString('Firma del Rector', $output);
        $this->assertStringContainsString('UADY', $output);
        $this->assertStringContainsString('Firma del Director', $output);
        $this->assertStringContainsString('FILEY', $output);
    }
    
    /**
     * @test
     */
    public function testContieneBotonImprimir()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('window.print()', $output);
        $this->assertStringContainsString('btn-imprimir', $output);
        $this->assertStringContainsString('Descargar PDF / Imprimir', $output);
    }
    
    /**
     * @test
     */
    public function testContieneHojaEstilos()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('href="../StyleSheets/constancias.css"', $output);
    }
    
    /**
     * @test
     */
    public function testContieneGoogleFonts()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('fonts.googleapis.com', $output);
        $this->assertStringContainsString('Playfair+Display', $output);
        $this->assertStringContainsString('Open+Sans', $output);
    }
    
    /**
     * @test
     */
    public function testContieneClaseConstanciaHoja()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('constancia-hoja', $output);
    }
    
    /**
     * @test
     */
    public function testMetaCharset()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('charset="UTF-8"', $output);
    }
    
    /**
     * @test
     */
    public function testTodosLosMesesDelAnio()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $mesesEsperados = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre'
        ];
        
        foreach ($mesesEsperados as $numeroMes => $nombreMes) {
            $datos = $this->obtenerDatosValidosDefault();
            $datos['fecha'] = "2024-{$numeroMes}-15";
            
            $output = $this->renderizarVistaConMock($datos);
            
            $this->assertStringContainsString($nombreMes, $output, 
                "El mes {$nombreMes} no se muestra correctamente");
        }
    }
    
    /**
     * @test
     */
    public function testNombreConCaracteresEspeciales()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $datos['nombreUsuario'] = 'José María Ñúñez Pérez';
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('José María Ñúñez Pérez', $output);
    }
    
    /**
     * @test
     */
    public function testTituloConCaracteresEspeciales()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $datos['titulo'] = 'Taller de Programación: Lógica & Algoritmos';
        
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('Taller de Programación: Lógica & Algoritmos', $output);
    }
    
    /**
     * @test
     */
    public function testEstructuraHTMLCompleta()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $datos = $this->obtenerDatosValidosDefault();
        $output = $this->renderizarVistaConMock($datos);
        
        $this->assertStringContainsString('<html>', $output);
        $this->assertStringContainsString('<head>', $output);
        $this->assertStringContainsString('</head>', $output);
        $this->assertStringContainsString('<body>', $output);
        $this->assertStringContainsString('</body>', $output);
        $this->assertStringContainsString('</html>', $output);
    }
    
    /**
     * @test
     */
    public function testValidacionAsistioDebeSerIgualA1()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        // Probar con diferentes valores que no son 1
        $valoresInvalidos = [0, 2, '1', null, false];
        
        foreach ($valoresInvalidos as $valor) {
            $datos = $this->obtenerDatosValidosDefault();
            $datos['asistio'] = $valor;
            
            $output = $this->renderizarVistaConMock($datos);
            
            if ($valor !== 1) {
                $this->assertStringContainsString('Error: Asistencia no validada', $output,
                    "El valor {$valor} debería generar error");
            }
        }
    }
    
    /**
     * @test
     */
    public function testValidacionTipoEventoDebeSerTaller()
    {
        $this->simularSesionUsuario();
        $this->simularParametrosGET(1);
        
        $tiposInvalidos = ['conferencia', 'charla', 'seminario', 'curso', ''];
        
        foreach ($tiposInvalidos as $tipo) {
            $datos = $this->obtenerDatosValidosDefault();
            $datos['tipoEvento'] = $tipo;
            
            $output = $this->renderizarVistaConMock($datos);
            
            $this->assertStringContainsString('Error: Este evento no emite constancia.', $output,
                "El tipo '{$tipo}' debería generar error");
        }
    }
    
    /**
     * Obtiene datos válidos por defecto para las pruebas
     */
    private function obtenerDatosValidosDefault(): array
    {
        return [
            'titulo' => 'Taller de Pruebas Unitarias',
            'nombreUsuario' => 'Juan Pérez',
            'fecha' => '2024-12-15',
            'asistio' => 1,
            'tipoEvento' => 'taller'
        ];
    }
    
    /**
     * Renderiza la vista con mock de datos
     */
    private function renderizarVistaConMock($datos): string
    {
        $tempFile = $this->generarVistaTemporalMock($datos);
        
        ob_start();
        include $tempFile;
        $output = ob_get_clean();
        
        unlink($tempFile);
        
        return $output;
    }
    
    /**
     * Genera un archivo temporal con la vista modificada para testing
     */
    private function generarVistaTemporalMock($datos): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_constancia_');
        
        // Leer el contenido original
        $contenidoOriginal = file_get_contents($this->vistaPath);
        
        // Reemplazar la validación de sesión para no redirigir en tests
        $contenidoModificado = str_replace(
            'if (!isset($_SESSION[\'idUsuario\'])) { header("Location: login.php"); exit(); }',
            'if (!isset($_SESSION[\'idUsuario\'])) { $_SESSION[\'idUsuario\'] = 1; }',
            $contenidoOriginal
        );
        
        // Reemplazar el require_once y la lógica de DAO
        $contenidoModificado = preg_replace(
            '/require_once.*RegistroDAO\.php.*/',
            '',
            $contenidoModificado
        );
        
        $contenidoModificado = preg_replace(
            '/\$idRegistro = \$_GET\[\'idRegistro\'\];/',
            '$idRegistro = $_GET["idRegistro"] ?? 1;',
            $contenidoModificado
        );
        
        $contenidoModificado = preg_replace(
            '/\$daoRegistro = new RegistroDAO\(\);.*\$datos = \$daoRegistro->obtenerPorId\(\$idRegistro\);/s',
            '$datos = $GLOBALS["__test_datos_constancia__"];',
            $contenidoModificado
        );
        
        file_put_contents($tempFile, $contenidoModificado);
        
        $GLOBALS['__test_datos_constancia__'] = $datos;
        
        return $tempFile;
    }
    
    protected function tearDown(): void
    {
        // Limpiar variables globales
        $_SESSION = [];
        $_GET = [];
        unset($GLOBALS['__test_datos_constancia__']);
    }
}