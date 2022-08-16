<?php

class Linha {

    private string $conteudo;
    private int $posicaoCaracterInicial;
    private int $posicaoCaracterFinal;
    private int $numeroLinha;

    public function __construct(string $conteudo, int $posicaoCaracterInicial, int $posicaoCaracterFinal, int $numeroLinha) 
    {
        $this->conteudo = $conteudo;
        $this->posicaoCaracterInicial = $posicaoCaracterInicial;
        $this->posicaoCaracterFinal = $posicaoCaracterFinal;
        $this->numeroLinha = $numeroLinha;
    }

    public function getConteudo() : string
    {
        return $this->conteudo;
    }

    public function getPosicaoInicial() : int
    {
        return $this->posicaoCaracterInicial;
    }

    public function getPosicaoFinal() : int
    {
        return $this->posicaoCaracterFinal;
    }

    public function getNumeroLinha() : int
    {
        return $this->numeroLinha;
    }

    public function pertenceLinha(int $posicaoCaracterInicial, int $posicaoCaracterFinal) : bool 
    {
        return $posicaoCaracterInicial >= $this->getPosicaoInicial() && $posicaoCaracterFinal <= $this->getPosicaoFinal();
    }

    public function getPosicaoCaracterLinha(int $posicaoCaracter) : int 
    {
        return $posicaoCaracter - $this->getPosicaoInicial() + 1;
    }

    public function getPosicaoCaracter(int $posicaoLinha) : int
    {
        return ($this->getPosicaoInicial() - 1) + $posicaoLinha;
    }
}