<?php

class Usuario {
    private int $idUsuario;
    private string $nombre;
    private string $email;
    private string $rolUsuario;
    private string $contraseña;
    
    public function __construct(int $idUsuario, string $nombre, string $email, string $rolUsuario, string $contraseña) {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->rolUsuario = $rolUsuario;
        $this->contraseña = $contraseña;
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

    public function getContraseña(): string {
        return $this->contraseña;
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

    public function setContraseña(string $contraseña): void {
        $this->contraseña = $contraseña;
    }


    
}
