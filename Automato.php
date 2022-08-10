<?php

require_once 'EstadoFinal.php';

class Automato {


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
        if(!$this->estadoInicialContidoEstados()) {
            throw new Exception("Estado Inicial não pertence aos x'estados informados.");
        }
    }

    private function estadoInicialContidoEstados() : bool
    {
        return in_array($this->getEstadoInicial(), this)
    }

    public function getEstadoFinal(string $entrada) : string
    {
        $estado = $this->getEstadoInicial();


        $caracteres = str_split($entrada);

        foreach ($caracteres as $caracter) {
            if(!in_array($caracter, $this->getAlfabeto())) {
                throw new Exception("Entrada contém caracteres não contidos no alfabeto. Caracter: {$caracter}"); 
            }
            
            $estado = $this->getDelta()[$estado][$caracter];
        }


        return $estado;
    }

    public function getTipoEntrada($entrada) : string 
    {
        $estadoFinal = $this->getEstadoFinal($entrada);

        
        foreach ($this->getEstadosFinais() as $estado) {
            if($estadoFinal === $estado->getEstado()) {
                return $estado->getTipo();
            }
        }
        
        throw new Exception("Entrada Inválida.");
    }

    public static function getCarateresParaEstado(string $estado, array $caracteres) : array 
    {
        $caracteresParaEstado = [];

        foreach ($caracteres as $caracter) {
            $caracteresParaEstado[$caracter] = $estado;
        }

        return $caracteresParaEstado;
    }

    public static function getLetras(array $caracteresExcluir = []) : array
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

    public static function getNumeros(array $caracteresExcluir = []) : array
    {
        $numeros = [
            '1', '2', '3', '4', '5', 
            '6', '7', '8', '9', '0'
        ];

        return array_diff($numeros, $caracteresExcluir);
    }
}