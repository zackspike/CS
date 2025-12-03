<?php
use PHPUnit\Framework\TestCase;

class loginTest extends TestCase
{
    private string $vistaPath;
    
    protected function setUp(): void
    {
    
        $this->vistaPath = __DIR__ . '/../../Vista/login.php';
    }
    
    private function capturarOutputVista(array $getParams): string
    {
        $_GET = $getParams;
        
        ob_start();
        include $this->vistaPath;
        $output = ob_get_clean();
        
        return $output;
    }
    
    /**
     * @test
     */
    public function testMensajeErrorCredenciales()
    {
        $output = $this->capturarOutputVista(['error' => 'credenciales']);
        
        $this->assertStringContainsString('Correo o contraseña incorrectos.', $output);
        $this->assertStringNotContainsString('success', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeErrorFalloRegistro()
    {
        $output = $this->capturarOutputVista(['error' => 'fallo_registro']);
        
        $this->assertStringContainsString('Error al registrar.', $output);
        $this->assertStringNotContainsString('success', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeRegistroExitoso()
    {
        $output = $this->capturarOutputVista(['mensaje' => 'registrado']);
        
        $this->assertStringContainsString('El registro fue exitoso, por favor inicia sesión.', $output);
        $this->assertStringContainsString('success', $output);
    }
    
    /**
     * @test
     */
    public function testSinMensaje()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertStringNotContainsString('Correo o contraseña incorrectos.', $output);
        $this->assertStringNotContainsString('Error al registrar.', $output);
        $this->assertStringNotContainsString('El registro fue exitoso', $output);
    }
    
    /**
     * @test
     */
    public function testErrorDesconocidoNoMuestraMensaje()
    {
        $output = $this->capturarOutputVista(['error' => 'otro_error']);
        
        // No debe mostrar ningún mensaje específico
        $this->assertStringNotContainsString('Correo o contraseña incorrectos.', $output);
        $this->assertStringNotContainsString('Error al registrar.', $output);
    }
    
    /**
     * @test
     */
    public function testMensajeDesconocidoNoMuestraMensaje()
    {
        $output = $this->capturarOutputVista(['mensaje' => 'otro_mensaje']);
        
        // No debe mostrar el mensaje de registro exitoso
        $this->assertStringNotContainsString('El registro fue exitoso', $output);
    }
    
    /**
     * @test
     */
    public function testContieneFormularioLogin()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertStringContainsString('<form action="../Controlador/UsuarioController.php" method="post">', $output);
        $this->assertStringContainsString('name="accion" value="login"', $output);
        $this->assertStringContainsString('name="email"', $output);
        $this->assertStringContainsString('name="password"', $output);
    }
    
    /**
     * @test
     */
    public function testContieneFormularioRegistro()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertStringContainsString('name="accion" value="registrar"', $output);
        $this->assertStringContainsString('name="nombre"', $output);
        $this->assertStringContainsString('id="nombreRegistro"', $output);
    }
    
    /**
     * @test
     */
    public function testContieneFuncionesJavaScript()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertStringContainsString('function mostrarRegistro()', $output);
        $this->assertStringContainsString('function mostrarLogin()', $output);
    }
    
    /**
     * @test
     */
    public function testTituloDelDocumento()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertStringContainsString('<title>Acceso FILEY</title>', $output);
    }
    
    /**
     * @test
     */
    public function testEnlaceCSS()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertStringContainsString('href="../StyleSheets/login.css"', $output);
    }
    
    /**
     * @test
     */
    public function testBotonRegresar()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertStringContainsString('href="index.php"', $output);
        $this->assertStringContainsString('Regresar', $output);
    }
    
    /**
     * @test
     */
    public function testRegistroUsuarioOcultoInicial()
    {
        $output = $this->capturarOutputVista([]);
        
        $this->assertMatchesRegularExpression(
            '/id="registroUsuario"[^>]*style="display:\s*none;?"/', 
            $output
        );
    }
    
    /**
     * @test
     */
    public function testAlertaSoloApareceConMensaje()
    {
        $outputSinMensaje = $this->capturarOutputVista([]);
        $outputConMensaje = $this->capturarOutputVista(['error' => 'credenciales']);
        
        $this->assertStringNotContainsString('<div class="alert', $outputSinMensaje);
        $this->assertStringContainsString('<div class="alert', $outputConMensaje);
    }
    
    /**
     * @test
     */
    public function testClaseSuccessConMensajePositivo()
    {
        $outputError = $this->capturarOutputVista(['error' => 'credenciales']);
        $outputMensaje = $this->capturarOutputVista(['mensaje' => 'registrado']);
        
        $this->assertStringNotContainsString('alert success', $outputError);
        $this->assertStringContainsString('alert success', $outputMensaje);
    }
    
    protected function tearDown(): void
    {
        
        $_GET = [];
    }
}