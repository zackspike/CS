<?php

/**
 *
 * @author Isaac Herrera
 */
class Categoria {
    private int $idCategoria;
    private string $nombre;
    private string $descripcion;
    
    public function __construct(int $idCategoria, string $nombre, string $descripcion) {
        $this->idCategoria = $idCategoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function getIdCategoria(): int {
        return $this->idCategoria;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setIdCategoria(int $idCategoria): void {
        $this->idCategoria = $idCategoria;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    
}
