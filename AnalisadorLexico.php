<?php

require_once 'Automato.php';
require_once 'Token.php';
class AnalisadorLexico {
    private Automato $automato;

    public function __construct(Automato $automato) {
        $this->automato = $automato;
    }

    private function getAutomato() : Automato
    {
        return $this->automato;
    }

    public function getTokensEntrada(string $entrada) : array 
    {

        $tokens = [];

        $caracteres = str_split($entrada);
        
        $posicaoLeituraInicial = 1;
        $posicaoLeituraFinal = count($caracteres);

        $posicaoFinalUltimoTokenEncontrado = null;

        for ($posicaoLeituraAtual=1; $posicaoLeituraAtual <= $posicaoLeituraFinal ; $posicaoLeituraAtual++) { 
            $parteEntrada = self::getStringFromParteArray($caracteres, $posicaoLeituraInicial, $posicaoLeituraAtual);
            $posicao = self::getInformacoesPosicao($entrada, $posicaoLeituraInicial, $posicaoLeituraAtual);

            try {
                $token = new Token(
                    $this->getAutomato()->getTipoEntrada($parteEntrada),
                    $parteEntrada,
                    $posicao->posicaoInicial,
                    $posicao->posicaoFinal,
                    $posicao->linha
                );

                $posicaoFinalUltimoTokenEncontrado = $posicaoLeituraAtual;

            } catch (Exception $ex) {
                $caracterEntradaNaoPertenceAlfabeto = $ex->getCode() === TiposErrosAutomato::CARACTER_ENTRADA_NAO_PERTENCE_ALFABETO->value;
                $transicaoInexistente  = $ex->getCode() === TiposErrosAutomato::TRANSICAO_INEXISTENTE_ESTADO_CARACTER_ATUAL->value;
                $nenhumTokenEncontrado = is_null($token);

                if($caracterEntradaNaoPertenceAlfabeto) {
                    throw new Exception($ex->getMessage() . " Entrada: {$entrada}, Linha: {$posicao->linha}, Posição Inicial: {$posicao->posicaoInicial}, Posição Final: {$posicao->posicaoFinal}");
                }

                if($transicaoInexistente && $nenhumTokenEncontrado) {
                    $posicaoLeituraAtual = $posicaoLeituraInicial;
                    $posicaoLeituraInicial++;
                }
                else if($transicaoInexistente) {
                    $tokens[] = $token;

                    $posicaoLeituraAtual = $posicaoFinalUltimoTokenEncontrado;
                    $posicaoLeituraInicial = $posicaoLeituraAtual + 1;

                    $token = null;
                }
            }
        }

        if(!is_null($token)) {
            $tokens[] = $token;
        }
        
        return $tokens;
    }

    private static function getInformacoesPosicao(string $entrada, int $posicaoInicial, int $posicaoFinal) : object
    {
        $linha = 1;
        $posicaoLinha = 0;
        $posicaoInicalLinha = 0;
        $posicaoFinalLinha = 0;

        $caracteres = str_split($entrada);

        for ($posicao=1; $posicao <= $posicaoFinal; $posicao++) { 
            $caracter = $caracteres[$posicao - 1];

            $posicaoLinha++;

            if(PHP_EOL === $caracter) {
                $linha++;
                $posicaoLinha = 0;
            }

            if($posicao === $posicaoInicial) {
                $posicaoInicalLinha = $posicaoLinha;
            }

            if($posicao === $posicaoFinal) {
                $posicaoFinalLinha = $posicaoLinha;
            }
        }

        return (object) [
            'linha'          => $linha,
            'posicaoInicial' => $posicaoInicalLinha,
            'posicaoFinal'   => $posicaoFinalLinha
        ];
    }

    private static function getStringFromParteArray(array $array, int $posicaoInicial, int $posicaoFinal) : string
    {
        $posicaoInicial--;
        $posicaoFinal--;
        return implode(array_splice($array, $posicaoInicial, (($posicaoFinal + 1) - $posicaoInicial)));
    }
}