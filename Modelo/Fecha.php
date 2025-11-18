<?php

class Fecha {
    private int $dia;
    private int $mes;
    private int $año;
    
    public function __construct(int $dia, int $mes, int $año) {
        $this->dia = $dia;
        $this->mes = $mes;
        $this->año = $año;
    }

    public function getDia(): int {
        return $this->dia;
    }

    public function getMes(): int {
        return $this->mes;
    }

    public function getAño(): int {
        return $this->año;
    }

    
    public function setDia(int $dia): void {
        if ($dia > 0) {
            $this->dia = $dia;
        } else {
            throw new InvalidArgumentException("Día inválido: debe ser mayor a 0.");
        }
    }

    public function setMes(int $mes): void {
        $diasPorMes = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        
        if ($mes == 2 && $this->esBisiesto($this->getAño())) {
            $diasPorMes[1] = 29;
        }

        if ($mes >= 1 && $mes <= 12 && $this->getDia() <= $diasPorMes[$mes - 1]) {
            $this->mes = $mes;
        } else {
            throw new InvalidArgumentException("Mes inválido o el día no existe en ese mes.");
        }
    }

    public function setAño(int $año): void {
        if ($año > 1900) {
            $this->año = $año;
        } else {
            throw new InvalidArgumentException("Año inválido: debe ser mayor a 1900.");
        }
    }

    public function esBisiesto(int $año): bool {
        return ($año % 4 == 0 && $año % 100 != 0) || ($año % 400 == 0);
    }
    
    public function __toString(): string {
        return "{$this->getDia()}/{$this->getMes()}/{$this->getAño()}";
    }

}
