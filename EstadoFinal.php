<?php

class EstadoFinal {
    private string $estado;
    private string $tipo;

    public function __construct($estado, $tipo)
    {
        $this->estado = $estado;
        $this->tipo = $tipo;
    }

    public function getEstado() : string
    {
        return $this->estado;
    }

    public function getTipo() : string
    {
        return $this->tipo;
    }
}