<?php
use PHPUnit\Framework\TestCase;

class salonesTest extends TestCase
{
    private string $vistaPath;
    
    protected function setUp(): void
    {
        $this->vistaPath = __DIR__ . '/../../Vista/salones.php';
    }
    
    private function simularSesionAdmin(string $nombre = 'Admin Test'): void
    {
        $_SESSION = [
            'rol' => 'admin',
            'nombre' => $nombre
        ];
    }
    
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
    public function testMuestraNombreAdministrador()
    {
        $this->simularSesionAdmin('Carlos Admin');
        $_GET = [];
        
        $output = $this->renderizarVistaConMocks([], null);
        
        $this->assertStringContainsString('Administrador: Carlos Admin', $output);
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
            'nombreSalon' => 'Sala VIP',
            'maxCapacidad' => 50
        ]);
        
        $output = $this->renderizarVistaConMocks([], $salonEditar);
        
        $this->assertStringContainsString('Editar Salón', $output);
        $this->assertStringContainsString('Guardar Cambios', $output);
        $this->assertStringContainsString('value="Sala VIP"', $output);
    }
    
    /**
     * @test
     */
    public function testTablaMuestraSalones()
    {
        $this->simularSesionAdmin();
        
        $salones = [
            $this->crearMockSalon(['nombreSalon' => 'Auditorio', 'maxCapacidad' => 200])
        ];
        
        $output = $this->renderizarVistaConMocks($salones, null);
        
        $this->assertStringContainsString('Auditorio', $output);
        $this->assertStringContainsString('200', $output);
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
    }
    
    private function renderizarVistaConMocks(array $lista, $salonEditar = null): string
    {
        $tempFile = $this->generarVistaTemporalMock($lista, $salonEditar);
        
        ob_start();
        include $tempFile;
        $output = ob_get_clean();
        
        unlink($tempFile);
        
        return $output;
    }
    
    private function generarVistaTemporalMock(array $lista, $salonEditar): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_salones_');
        
        $contenidoOriginal = file_get_contents($this->vistaPath);
        
        $contenidoModificado = str_replace(
            'if (!isset($_SESSION[\'rol\']) || $_SESSION[\'rol\'] != \'admin\') {
    header("Location: login.php");
    exit();
}',
            'if (!isset($_SESSION[\'rol\']) || $_SESSION[\'rol\'] != \'admin\') {
    $_SESSION[\'rol\'] = \'admin\';
    $_SESSION[\'nombre\'] = \'Test\';
}',
            $contenidoOriginal
        );
        
        $contenidoModificado = preg_replace(
            '/require_once.*SalonDAO\.php.*/',
            '',
            $contenidoModificado
        );
        
        $contenidoModificado = preg_replace(
            '/\$daoSalones = new SalonDAO\(\);.*\$lista = \$daoSalones->obtenerTodos\(\);/s',
            '$lista = $GLOBALS["__test_lista__"];
$salonEditar = $GLOBALS["__test_editar__"];',
            $contenidoModificado
        );
        
        $contenidoModificado = preg_replace(
            '/\$salonEditar = null;.*if \(isset\(\$_GET\[\'accion\'\]\).*\$salonEditar = \$daoSalones->obtenerPorId\(\$_GET\[\'id\'\]\);.*\}/s',
            '',
            $contenidoModificado
        );
        
        file_put_contents($tempFile, $contenidoModificado);
        
        $GLOBALS['__test_lista__'] = $lista;
        $GLOBALS['__test_editar__'] = $salonEditar;
        
        return $tempFile;
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
        $_GET = [];
        unset($GLOBALS['__test_lista__']);
        unset($GLOBALS['__test_editar__']);
    }
}

