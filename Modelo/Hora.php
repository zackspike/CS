<?php
/**
 * Description of Hora
 *
 * @author Gabriela Puch
 */
class Hora {
    private int $hora;
    private int $minutos;
    
    public function __construct(int $hora, int $minutos) {
        $this->hora = $hora;
        $this->minutos = $minutos;
    }

    public function getHora(): int {
        return $this->hora;
    }

    public function getMinutos(): int {
        return $this->minutos;
    }

    public function setHora(int $hora): void {
        if ($hora >= 0 && $hora <= 23) {
            $this->hora = $hora;
        } else {
            throw new InvalidArgumentException("Hora inválida: debe estar entre 0 y 23.");
        }
    }

    public function setMinutos(int $minutos): void {
        if ($minutos >= 0 && $minutos <= 59) {
            $this->minutos = $minutos;
        } else {
            throw new InvalidArgumentException("Minuto inválido: debe estar entre 0 y 59.");
        }
    }

    public function __toString(): string {
        return sprintf("%02d:%02d", $this->getHora(), $this->getMinutos());
    }


}
