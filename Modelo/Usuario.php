<?php
/**
 * @author KarenM
* @author Isaac Herrera
*/
class Usuario {
    private int $idUsuario;
    private string $nombre;
    private string $email;
    private string $rolUsuario;
    private string $password;
    
    public function __construct(int $idUsuario, string $nombre, string $email, string $rolUsuario, string $password) {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->rolUsuario = $rolUsuario;
        $this->password = $password;
    }
    
    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRolUsuario(): string {
        return $this->rolUsuario;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setIdUsuario(int $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setRolUsuario(string $rolUsuario): void {
        $this->rolUsuario = $rolUsuario;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }


    
}
