<?php

require_once 'Automato.php';

class AnalisadorLexico {
    private Automato $automato;

    public function __construct(Automato $automato) {
        $this->automato = $automato;
    }

    private function getAutomato() : Automato
    {
        return $this->automato;
    }

    /**
     * @param string $entrada
     * @return 
     */
    public function getTokensEntrada(string $entrada) : array
    {
        $tokens = [];

        $posicaoAtual = 0;
        
        $posicaoInicialLeitura = 0;

        $ultimoTokenValidoEncontrado = null;

        $linha = 1;

        $caracteres = str_split($entrada);

        for($posicao = $posicaoInicialLeitura; $posicao < count($caracteres); $posicao++) {

            $caracterAtual = $caracteres[$posicao];

            if($caracterAtual === PHP_EOL) {
                $linha++;
            }

            $entrada = self::getStringFromParteArray($caracteres, $posicaoInicialLeitura, $posicao);

            try {
                $ultimoTokenValidoEncontrado = new Token(
                    $this->getAutomato()->getEstadoFinal($entrada),
                    $entrada,
                    $posicaoInicialLeitura,
                    $posicao,
                    $linha
                );
            } catch (Exception $ex) {
                if($ex->getCode()) {

                }
            }
        }


        return $tokens;
    }

    private static function getStringFromParteArray(array $array, int $posicaoInicial, int $posicaoFinal) : string
    {
        return implode(array_splice($array, $posicaoInicial, (($posicaoFinal + 1) - $posicaoInicial)));
    }
    
}