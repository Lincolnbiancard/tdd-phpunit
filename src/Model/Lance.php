<?php

namespace Alura\Leilao\Model;

class Lance
{
    private Usuario $usuario;
    private float $valor;

    public function __construct(Usuario $usuario, float $valor)
    {
        $this->usuario = $usuario;
        $this->valor = $valor;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function getValor(): float
    {
        return $this->valor;
    }
}
