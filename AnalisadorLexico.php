<?php

require_once 'Automato.php';
require_once 'ControladorLinhas.php';
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

    /**
     * @param string $entrada
     * @return 
     */
    public function getTokensEntrada(string $entrada) : array
    {
        $tokens = [];
        $token = null;

        $controladorLinhas = new ControladorLinhas($entrada);

        $caracteres             = str_split($entrada);
        $posicaoCaracterAtual   = 1;
        $posicaoCaracterInicial = 1;
        $posicaoCaracterFinal = count($caracteres);

        do {
            $pedacoEntrada = self::getStringFromParteArray($caracteres, $posicaoCaracterInicial, $posicaoCaracterAtual);

            try {
                $linha = $controladorLinhas->getLinha($posicaoCaracterInicial, $posicaoCaracterAtual);

                if(is_null($linha)) {
                    $posicaoCaracterInicial++;
                    if($posicaoCaracterInicial - $posicaoCaracterAtual !== 1) {
                        $posicaoCaracterAtual--;
                    }
                }
                else {
                    $token = new Token(
                        $this->getAutomato()->getTipoEntrada($pedacoEntrada),
                        $pedacoEntrada,
                        $linha->getPosicaoCaracterLinha($posicaoCaracterInicial),
                        $linha->getPosicaoCaracterLinha($posicaoCaracterAtual),
                        $linha->getNumeroLinha()
                    );
                }
            } catch (Exception $ex) {
                $caracterEntradaNaoPertenceAlfabeto = $ex->getCode() === TiposErrosAutomato::CARACTER_ENTRADA_NAO_PERTENCE_ALFABETO->value;
                $transicaoInexistente  = $ex->getCode() === TiposErrosAutomato::TRANSICAO_INEXISTENTE_ESTADO_CARACTER_ATUAL->value;
                $nenhumTokenEncontrado = is_null($token);

                if($caracterEntradaNaoPertenceAlfabeto || ($posicaoCaracterAtual === $posicaoCaracterFinal && $nenhumTokenEncontrado)) {
                    throw $ex;
                }

                if($transicaoInexistente && $nenhumTokenEncontrado) {
                    $posicaoCaracterAtual = $posicaoCaracterInicial;
                    $posicaoCaracterInicial++;
                }
                else if($transicaoInexistente) {
                    $tokens[] = $token;
                    $linhaToken = $controladorLinhas->getLinhaFromNumero($token->getLinha());
                    if($linhaToken === null) {
                        $teste = null;
                    }
                    $posicaoCaracterAtual = $linhaToken->getPosicaoCaracter($token->getPosicaoFinal());
                    $posicaoCaracterInicial = $posicaoCaracterAtual + 1;
                    $token = null;
                }
            }

            $posicaoCaracterAtual++;
        } while ($posicaoCaracterAtual <= $posicaoCaracterFinal);
        
        $tokens[] = $token;

        return $tokens;
    }

    private static function getStringFromParteArray(array $array, int $posicaoInicial, int $posicaoFinal) : string
    {
        $posicaoInicial--;
        $posicaoFinal--;
        return implode(array_splice($array, $posicaoInicial, (($posicaoFinal + 1) - $posicaoInicial)));
    }
    
}