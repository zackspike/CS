<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../Modelo/conexionBD.php';
require_once __DIR__ . '/../../Modelo/Usuario.php';
require_once __DIR__ . '/../../Modelo/UsuarioDAO.php';

class UsuarioDAOTest extends TestCase {

    private $dao;
    private $conexion;

    protected function setUp(): void {
        // Conexión a la base de datos de prueba
        $bd = new conexionBD();
        $this->conexion = $bd->conectar();

        $this->conexion->query("DELETE FROM Usuarios");

        $this->dao = new UsuarioDAO();
    }

    public function testRegistrarUsuario() {
        $usuario = new Usuario(
            0,
            "Jesús Test",
            "jesus@test.com",
            "usuario",
            password_hash("1234", PASSWORD_BCRYPT)
        );

        $resultado = $this->dao->registrar($usuario);

        $this->assertTrue($resultado);

        // verificamos que se guardó
        $consulta = $this->conexion->query("SELECT * FROM Usuarios WHERE email='jesus@test.com'");
        $this->assertEquals(1, $consulta->num_rows);
    }

    public function testLoginExitoso() {
        // Insertar usuario manualmente
        $passwordHash = password_hash("1234", PASSWORD_BCRYPT);

        $this->conexion->query("
            INSERT INTO Usuarios (nombre, email, password, rolUsuario)
            VALUES ('Jesús Login', 'login@test.com', '$passwordHash', 'usuario')
        ");

        // Intentar login
        $resultado = $this->dao->login("login@test.com", "1234");

        $this->assertIsArray($resultado);
        $this->assertEquals("Jesús Login", $resultado['nombre']);
        $this->assertEquals("usuario", $resultado['rolUsuario']);
        $this->assertArrayNotHasKey('password', $resultado);
    }

    public function testLoginFallidoPorPasswordIncorrecto() {
        // Insertar usuario
        $passwordHash = password_hash("correcto", PASSWORD_BCRYPT);

        $this->conexion->query("
            INSERT INTO Usuarios (nombre, email, password, rolUsuario)
            VALUES ('Usuario Test', 'fail@test.com', '$passwordHash', 'usuario')
        ");

        // Password incorrecto
        $resultado = $this->dao->login("fail@test.com", "incorrecto");

        $this->assertFalse($resultado);
    }

    public function testLoginEmailInexistente() {
        $resultado = $this->dao->login("noexiste@test.com", "1234");

        $this->assertFalse($resultado);
    }
}
