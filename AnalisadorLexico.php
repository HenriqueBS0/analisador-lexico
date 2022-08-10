<?php

require_once 'Automato.php';

class AnalisadorLexico {
    private Automato $automato;

    public function __construct(Automato $automato) {
        $this->automato = $automato;
    }

    private function getAutomato() {
        return $this->automato;
    }

    
}