<?php

class Token {
    private string $token;
    private string $lexema;

    private int $posicaoInicial;
    private int $posicaoFinal;
    private int $linha;

    public function __construct(string $token, string $lexema, int $posicaoInicial, int $posicaoFinal, $linha) {
        $this->token          = $token;
        $this->lexema         = $lexema;
        $this->posicaoInicial = $posicaoInicial;
        $this->posicaoFinal   = $posicaoFinal;
        $this->linha          = $linha;
    }

    public function getToken() : string
    {
        return $this->token;
    }

    public function getLexema() : string
    {
        return $this->lexema;
    }

    public function getPosicaoInicial() : int
    {
        return $this->posicaoInicial;
    }

    public function getPosicaoFinal() : int
    {
        return $this->posicaoFinal;
    }

    public function getLinha() : int
    {
        return $this->linha;
    }
}