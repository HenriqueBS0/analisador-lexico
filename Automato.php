<?php

require_once 'EstadoFinal.php';
require_once 'TiposErrosAutomato.php';
class Automato 
{
    /** @var string[] */
    private array  $alfabeto;

    /** @var string[] */
    private array  $estados;
    
    /** @var string */
    private string $estadoInicial;
    
    /** @var EstadoFinal[] */
    private array  $estadosFinais;
    
    /** @var array */
    private array  $delta;


    public function __construct(array $alfabeto, array $estados, string $estadoInicial, array $estadosFinais, array $delta)
    {
        $this->setAlfabeto($alfabeto);
        $this->setEstados($estados);
        $this->setEstadoInicial($estadoInicial);
        $this->setEstadosFinais($estadosFinais);
        $this->setDelta($delta);
        $this->validarAtributos();
    }

    private function setAlfabeto(array $alfabeto) : void 
    {
        $this->alfabeto = $alfabeto;
    }

    private function getAlfabeto() : array
    {
        return $this->alfabeto;
    }

    private function setEstados(array $estados) : void 
    {
        $this->estados = $estados;
    }

    private function getEstados() : array
    {
        return $this->estados;
    }

    private function setEstadoInicial(string $estado) : void
    {
        $this->estadoInicial = $estado;
    }

    private function getEstadoInicial() : string
    {
        return $this->estadoInicial;
    }

    private function setEstadosFinais(array $estados)  : void
    {
        $this->estadosFinais = $estados;
    }

    /** @return EstadoFinal[] */
    private function getEstadosFinais() : array
    {
        return $this->estadosFinais;
    }

    private function setDelta(array $delta)  : void
    {
        $this->delta = $delta;
    }

    private function getDelta() : array
    {
        return $this->delta;
    }

    private function validarAtributos() : void
    {
        $this->validarEstadoInicialContidoEstados();
        $this->validarEstadosFinais();
        $this->validarTransicoesDelta();
    }

    private function validarEstadoInicialContidoEstados() : void 
    {
        if(!$this->estadoInicialContidoEstados()) {
            throw new Exception("Estado Inicial não pertence aos estados informados.", TiposErrosAutomato::ESTADO_INICIAL_IMPREVISTO->value);
        }
    }

    private function estadoInicialContidoEstados() : bool
    {
        return in_array($this->getEstadoInicial(), $this->getEstados());
    }

    private function validarEstadosFinais() : void
    {
        foreach ($this->getEstadosFinais() as $estadoFinal) {
            if(!$this->estadoPertenceEstados($estadoFinal->getEstado())) {
                throw new Exception("Estado final '{$estadoFinal->getEstado()}' não pertence aos estados informados.", TiposErrosAutomato::ESTADO_FINAL_IMPREVISTO->value);
            }
        }
    }

    private function validarTransicoesDelta() : void
    {
        foreach ($this->getDelta() as $estadoAtual => $transicoes) {
            foreach($transicoes as $entrada => $estado) {

                $funcaoTransicao = "'{$estado} = δ({$estadoAtual}, {$entrada})'";

                if(!$this->estadoPertenceEstados($estadoAtual)) {
                    throw new Exception("Função de transição {$funcaoTransicao} inválida, o estado atual '{$estadoAtual}' não pertence aos estados informados.", TiposErrosAutomato::DELTA_ESTADO_ATUAL_IMPREVISTO->value);
                }

                if(!$this->entradaPertenceAlfabeto($entrada)) {
                    throw new Exception("Função de transição {$funcaoTransicao} inválida, a entrada '{$entrada}' não pertence ao alfabeto.", TiposErrosAutomato::DELTA_ENTRADA_IMPREVISTA->value);
                }

                if(!$this->estadoPertenceEstados($estado)) {
                    throw new Exception("Função de transição {$funcaoTransicao} inválida, o estado resultante '{$estado}' não pertence aos estados informados.", TiposErrosAutomato::DELTA_ESTADO_RESULTANTE_IMPREVISTO->value);
                }
            }
        }
    }

    private function entradaPertenceAlfabeto(string $entrada) : bool 
    {
        return in_array($entrada, $this->getAlfabeto());
    }

    private function estadoPertenceEstados(string $estado) : bool
    {
        return in_array($estado, $this->getEstados());
    }

    public function getEstadoFinal(string $entrada) : string
    {

        $this->validarCaracteresEntradaPertencemAlfabeto($entrada);

        $estado = $this->getEstadoInicial();

        $caracteres = str_split($entrada);

        foreach ($caracteres as $caracter) {
            $this->validarTransicaoExiste($estado, $caracter);
            $estado = $this->getDelta()[$estado][$caracter];
        }

        return $estado;
    }

    private function validarCaracteresEntradaPertencemAlfabeto($entrada) : void
    {
        foreach(str_split($entrada) as $caracter) {
            if(!in_array($caracter, $this->getAlfabeto())) {
                throw new Exception("Entrada contém caracteres não contidos no alfabeto. Caracter: {$caracter}", TiposErrosAutomato::CARACTER_ENTRADA_NAO_PERTENCE_ALFABETO->value);
            }
        }
    }

    private function validarTransicaoExiste(string $estadoAtual, string $caracter) : void 
    {
        if(!isset($this->getDelta()[$estadoAtual][$caracter])) {
            throw new Exception("Função de transição δ({$estadoAtual}, {$caracter}) não foi prevista", TiposErrosAutomato::TRANSICAO_INEXISTENTE_ESTADO_CARACTER_ATUAL->value);
        }
    }

    public function getTipoEntrada(string $entrada) : string 
    {
        $estadoFinal = $this->getEstadoFinal($entrada);

        foreach ($this->getEstadosFinais() as $estado) {
            if($estadoFinal === $estado->getEstado()) {
                return $estado->getTipo();
            }
        }
        
        throw new Exception("Nenhum tipo de entrada encontrado.", TiposErrosAutomato::ESTADO_FINAL_INEXISTENTE_PARA_ENTRADA->value);
    }

    public static function getCarateresParaEstado(array $caracteres, string $estado) : array 
    {
        $caracteresParaEstado = [];

        foreach ($caracteres as $caracter) {
            $caracteresParaEstado[$caracter] = $estado;
        }

        return $caracteresParaEstado;
    }

    public static function getLetrasMinusculas(array $caracteresExcluir = []) : array
    {
        $letras = [
            'a', 'b', 'c', 'd', 'e',
            'f', 'g', 'h', 'i', 'j',
            'k', 'l', 'm', 'n', 'o',
            'p', 'q', 'r', 's', 't',
            'u', 'v', 'w', 'x', 'y',
            'z'
        ];

        return array_diff($letras, $caracteresExcluir);
    }

    public static function getLetrasMaiusculas(array $caracteresExcluir = []) : array
    {
        return array_diff(array_map(function($letra) {return strtoupper($letra);}, self::getLetrasMinusculas()), $caracteresExcluir);
    }

    public static function getLetras(array $caracteresExcluir = [], bool $caseSensitive = true) {

        if(!$caseSensitive) {
            $caracteresExcluir = array_map(function($caracter) {return strtoupper($caracter);}, $caracteresExcluir);
        }

        return array_filter(array_merge(self::getLetrasMinusculas(), self::getLetrasMaiusculas()), function($letra) use($caracteresExcluir, $caseSensitive) {
            return !in_array($caseSensitive ? $letra : strtoupper($letra), $caracteresExcluir);
        });
    }

    public static function getNumeros(array $caracteresExcluir = []) : array
    {
        $numeros = [
            '1', '2', '3', '4', '5', 
            '6', '7', '8', '9', '0'
        ];

        return array_diff($numeros, $caracteresExcluir);
    }
}