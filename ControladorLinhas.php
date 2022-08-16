<?php

require_once 'Linha.php';

class ControladorLinhas {

    /** @var Linha[] */
    private array $linhas;

    public function __construct(string $entrada) {
        $this->setLinhas($entrada);
    }

    private function setLinhas(string $entrada) : void
    {      
        $conteudosLinhas = explode(PHP_EOL, $entrada);

        $posicaoCaracterInicial = 1;
        $posicaoCaracterFinal = 0;

        foreach ($conteudosLinhas as $iteracao => $conteudo) {
            if(!$iteracao === 0) {
                $conteudo = PHP_EOL . $conteudo;
            }

            $posicaoCaracterInicial = $posicaoCaracterFinal + 1;
            $posicaoCaracterFinal = $posicaoCaracterInicial + (strlen($conteudo) - 1);

            $this->addLinha(new Linha($conteudo, $posicaoCaracterInicial, $posicaoCaracterFinal, $iteracao + 1));
        }

    }

    private function addLinha(Linha $linha) : void 
    {
        $this->linhas[] = $linha;
    }

    public function getLinha(int $posicaoCaracterInicial, int $posicaoCaracterFinal) : Linha|null
    {
        foreach ($this->linhas as $linha) {
            if($linha->pertenceLinha($posicaoCaracterInicial, $posicaoCaracterFinal)) {
                return $linha;
            }
        }

        return null;
    }

    public function getLinhaFromNumero(int $numeroLinha) : Linha
    {
        foreach ($this->linhas as $linha) {
            if($linha->getNumeroLinha() === $numeroLinha) {
                return $linha;
            }
        }
    }
}