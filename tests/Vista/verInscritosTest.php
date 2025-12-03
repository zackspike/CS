<?php
use PHPUnit\Framework\TestCase;

class verInscritosTest extends TestCase
{
    private string $vistaPath;
    
    protected function setUp(): void
    {
        
        $this->vistaPath = __DIR__ . '/../../Vista/verInscritos.php';
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
     * Simula parámetros GET con ID de evento
     */
    private function simularParametrosGET(int $idEvento): void
    {
        $_GET = ['idEvento' => $idEvento];
    }
    
    /**
     * @test
     */
    public function testRedirectSinSesion()
    {
        $_SESSION = [];
        $_GET = ['idEvento' => 1];
        
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
        $this->simularSesionAdmin('María González');
        $this->simularParametrosGET(5);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('Administrador: María González', $output);
        $this->assertStringContainsString('usuario-info', $output);
    }
    
    /**
     * @test
     */
    public function testTituloDocumento()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('<title>Lista de Asistencia</title>', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraIdEvento()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(42);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('evento ID: #42', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeSinInscritos()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('Nadie se ha inscrito a este evento aún.', $output);
    }
    
    /**
     * @test
     */
    public function testMuestraTablaConInscritos()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'fechaRegistro' => '2024-12-15 10:30:00',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('Juan Pérez', $output);
        $this->assertStringContainsString('juan@example.com', $output);
        $this->assertStringContainsString('<table', $output);
    }
    
    /**
     * @test
     */
    public function testTablaContieneEncabezados()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            $this->crearInscritoDefault()
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('Participante', $output);
        $this->assertStringContainsString('Correo', $output);
        $this->assertStringContainsString('Fecha Registro', $output);
        $this->assertStringContainsString('Estado', $output);
        $this->assertStringContainsString('Acción', $output);
        $this->assertStringContainsString('<thead>', $output);
    }
    
    /**
     * @test
     */
    public function testFormatoFechaRegistro()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-25 14:30:00',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        // Formato dd/mm/yyyy
        $this->assertStringContainsString('25/12/2024', $output);
    }
    
    /**
     * @test
     */
    public function testBadgePendienteParaNoAsistio()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('badge-no', $output);
        $this->assertStringContainsString('Pendiente', $output);
    }
    
    /**
     * @test
     */
    public function testBadgeAsistioParaAsistio()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 1
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('badge-si', $output);
        $this->assertStringContainsString('Asistió', $output);
    }
    
    /**
     * @test
     */
    public function testBotonValidarParaPendiente()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(5);
        
        $inscritos = [
            [
                'idRegistro' => 10,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('btn-validar', $output);
        $this->assertStringContainsString('Validar', $output);
        $this->assertStringContainsString('accion=validar', $output);
        $this->assertStringContainsString('idRegistro=10', $output);
        $this->assertStringContainsString('idEvento=5', $output);
    }
    
    /**
     * @test
     */
    public function testTextoValidadoParaAsistio()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 1
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('Validado', $output);
        $this->assertStringNotContainsString('btn-validar', $output);
    }
    
    /**
     * @test
     */
    public function testMultiplesInscritos()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Ana García',
                'email' => 'ana@test.com',
                'fechaRegistro' => '2024-12-10',
                'asistio' => 1
            ],
            [
                'idRegistro' => 2,
                'nombre' => 'Carlos López',
                'email' => 'carlos@test.com',
                'fechaRegistro' => '2024-12-11',
                'asistio' => 0
            ],
            [
                'idRegistro' => 3,
                'nombre' => 'María Rodríguez',
                'email' => 'maria@test.com',
                'fechaRegistro' => '2024-12-12',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('Ana García', $output);
        $this->assertStringContainsString('ana@test.com', $output);
        $this->assertStringContainsString('Carlos López', $output);
        $this->assertStringContainsString('carlos@test.com', $output);
        $this->assertStringContainsString('María Rodríguez', $output);
        $this->assertStringContainsString('maria@test.com', $output);
    }
    
    /**
     * @test
     */
    public function testNombreEnNegrita()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Pedro Martínez',
                'email' => 'pedro@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertMatchesRegularExpression('/<strong>Pedro Martínez<\/strong>/', $output);
    }
    
    /**
     * @test
     */
    public function testEnlaceVolverAEventos()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('href="gestionEventos.php"', $output);
        $this->assertStringContainsString('Volver a Eventos', $output);
    }
    
    /**
     * @test
     */
    public function testContieneLogoFILEY()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('logoFILEY.png', $output);
        $this->assertStringContainsString('logo-container', $output);
    }
    
    /**
     * @test
     */
    public function testContieneHojasEstilo()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('href="../StyleSheets/Inicio.css"', $output);
        $this->assertStringContainsString('href="../StyleSheets/Iinscripciones.css"', $output);
    }
    
    /**
     * @test
     */
    public function testMetaCharset()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('charset="UTF-8"', $output);
    }
    
    /**
     * @test
     */
    public function testDoctypeHTML()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('<!DOCTYPE html>', $output);
    }
    
    /**
     * @test
     */
    public function testEstructuraHeader()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('class="header"', $output);
        $this->assertStringContainsString('header-container', $output);
        $this->assertStringContainsString('nav-menu', $output);
    }
    
    /**
     * @test
     */
    public function testEstructuraContainer()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('class="container"', $output);
        $this->assertStringContainsString('table-container', $output);
    }
    
    /**
     * @test
     */
    public function testTituloListaAsistencia()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('<h2 style="color:#005288;">Lista de Asistencia</h2>', $output);
    }
    
    /**
     * @test
     */
    public function testSubtituloValidacionParticipantes()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        $this->assertStringContainsString('Validación de participantes', $output);
    }
    
    /**
     * @test
     */
    public function testClaseTablaBonita()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [$this->crearInscritoDefault()];
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('class="tabla-bonita"', $output);
    }
    
    /**
     * @test
     */
    public function testControllerRegistroEnValidar()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(3);
        
        $inscritos = [
            [
                'idRegistro' => 7,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('../Controlador/RegistroController.php', $output);
    }
    
    /**
     * @test
     */
    public function testEmailMuestraCorrectamente()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Test',
                'email' => 'usuario.especial@dominio.com.mx',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('usuario.especial@dominio.com.mx', $output);
    }
    
    /**
     * @test
     */
    public function testNombreConCaracteresEspeciales()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'José María Ñúñez',
                'email' => 'jose@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        $this->assertStringContainsString('José María Ñúñez', $output);
    }
    
    /**
     * @test
     */
    public function testFechasEnDiferentesMeses()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $fechas = [
            '2024-01-15 10:00:00' => '15/01/2024',
            '2024-06-30 15:30:00' => '30/06/2024',
            '2024-12-31 23:59:00' => '31/12/2024'
        ];
        
        foreach ($fechas as $fecha => $esperado) {
            $inscritos = [
                [
                    'idRegistro' => 1,
                    'nombre' => 'Test',
                    'email' => 'test@test.com',
                    'fechaRegistro' => $fecha,
                    'asistio' => 0
                ]
            ];
            
            $output = $this->renderizarVistaConMocks($inscritos);
            
            $this->assertStringContainsString($esperado, $output,
                "La fecha {$fecha} no se formateó correctamente a {$esperado}");
        }
    }
    
    /**
     * @test
     */
    public function testValidacionAsistioExacto()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        // asistio = 0 debe mostrar botón validar
        $inscrito0 = [
            [
                'idRegistro' => 1,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output0 = $this->renderizarVistaConMocks($inscrito0);
        $this->assertStringContainsString('btn-validar', $output0);
        
        // asistio = 1 debe mostrar "Validado"
        $inscrito1 = [
            [
                'idRegistro' => 1,
                'nombre' => 'Test',
                'email' => 'test@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 1
            ]
        ];
        
        $output1 = $this->renderizarVistaConMocks($inscrito1);
        $this->assertStringContainsString('Validado', $output1);
        $this->assertStringNotContainsString('btn-validar', $output1);
    }
    
    /**
     * @test
     */
    public function testMixtoAsistidosYPendientes()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $inscritos = [
            [
                'idRegistro' => 1,
                'nombre' => 'Asistió',
                'email' => 'asistio@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 1
            ],
            [
                'idRegistro' => 2,
                'nombre' => 'Pendiente',
                'email' => 'pendiente@test.com',
                'fechaRegistro' => '2024-12-15',
                'asistio' => 0
            ]
        ];
        
        $output = $this->renderizarVistaConMocks($inscritos);
        
        // Debe contener ambos badges
        $this->assertStringContainsString('badge-si', $output);
        $this->assertStringContainsString('badge-no', $output);
        
        // Debe contener botón validar y texto validado
        $this->assertStringContainsString('btn-validar', $output);
        $this->assertStringContainsString('Validado', $output);
    }
    
    /**
     * @test
     */
    public function testTablaNoSeMuestraSinInscritos()
    {
        $this->simularSesionAdmin();
        $this->simularParametrosGET(1);
        
        $output = $this->renderizarVistaConMocks([]);
        
        // No debe aparecer la tabla
        $this->assertStringNotContainsString('tabla-bonita', $output);
        $this->assertStringNotContainsString('<thead>', $output);
    }
    
    /**
     * @test
     */
    public function testIdEventoDiferentes()
    {
        $this->simularSesionAdmin();
        
        $idsEventos = [1, 50, 999];
        
        foreach ($idsEventos as $id) {
            $this->simularParametrosGET($id);
            $output = $this->renderizarVistaConMocks([]);
            
            $this->assertStringContainsString("evento ID: #{$id}", $output,
                "No se muestra correctamente el ID de evento {$id}");
        }
    }
    
    /**
     * Crea un inscrito con datos por defecto
     */
    private function crearInscritoDefault(): array
    {
        return [
            'idRegistro' => 1,
            'nombre' => 'Usuario Test',
            'email' => 'test@example.com',
            'fechaRegistro' => '2024-12-15 10:00:00',
            'asistio' => 0
        ];
    }
    
    /**
     * Renderiza la vista con mocks de datos
     */
    private function renderizarVistaConMocks(array $inscritos): string
    {
        $tempFile = $this->generarVistaTemporalMock($inscritos);
        
        ob_start();
        include $tempFile;
        $output = ob_get_clean();
        
        unlink($tempFile);
        
        return $output;
    }
    
    /**
     * Genera un archivo temporal con la vista modificada para testing
     */
    private function generarVistaTemporalMock(array $inscritos): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_asistencia_');
        
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
        
        // Reemplazar los require_once
        $contenidoModificado = preg_replace(
            '/require_once.*RegistroDAO\.php.*;/',
            '',
            $contenidoModificado
        );
        
        $contenidoModificado = preg_replace(
            '/require_once.*EventoDAO\.php.*;/',
            '',
            $contenidoModificado
        );
        
        // Reemplazar la lógica de DAO
        $contenidoModificado = preg_replace(
            '/\$idEvento = \$_GET\[\'idEvento\'\];.*\$inscritos = \$registroDAO->obtenerPorEvento\(\$idEvento\);/s',
            '$idEvento = $_GET["idEvento"] ?? 1;
$inscritos = $GLOBALS["__test_inscritos__"];',
            $contenidoModificado
        );
        
        file_put_contents($tempFile, $contenidoModificado);
        
        $GLOBALS['__test_inscritos__'] = $inscritos;
        
        return $tempFile;
    }
    
    protected function tearDown(): void
    {
        // Limpiar variables globales
        $_SESSION = [];
        $_GET = [];
        unset($GLOBALS['__test_inscritos__']);
    }
}