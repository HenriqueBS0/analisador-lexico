<?php

require_once 'AnalisadorLexico.php';

$alfabeto = array_merge(Automato::getLetras(), Automato::getNumeros(), ['<', '>', '(', ')', '{', '}', '-', '+', '*', '/', '=', '!', ' ', PHP_EOL]);

$estadoInicial= 'INICIO';

$estados = [
    'INICIO', 'VARIAVEL', 'IF', 'FOR', 'PRINT', 'CONSTANTE', 'RECEBE', 'IGUALDADE', 'DESIGUALDADE',
    'MAIOR', 'MENOR', 'ABRE-PARENTESES', 'FECHA-PARENTESES', 'ABRE-CHAVES', 'FECHA-CHAVES', 'MENOS', 'MAIS', 'MULTIPLICA', 'DIVIDE',
    'I-IF', 'F-FOR', 'O-FOR', 'P-PRINT', 'R-PRINT', 'I-PRINT', 'N-PRINT', '!-DESIGUALDADE'
];

$delta = [
    'INICIO' => array_merge([
            'I' => 'I-IF',
            'F' => 'F-FOR',
            'P' => 'P-PRINT',
            '>' => 'MAIOR',
            '<' => 'MENOR',
            '(' => 'ABRE-PARENTESES',
            ')' => 'FECHA-PARENTESES',
            '{' => 'ABRE-CHAVES',
            '}' => 'FECHA-CHAVES',
            '-' => 'MENOS',
            '+' => 'MAIS',
            '*' => 'MULTIPLICA',
            '/' => 'DIVIDE',
            '=' => 'RECEBE',
            '!' => '!-DESIGUALDADE'
        ], 
        Automato::getCarateresParaEstado(Automato::getNumeros(),                'CONSTANTE'),
        Automato::getCarateresParaEstado(Automato::getLetras(['I', 'F', 'P']),  'VARIAVEL')
    ),
    'I-IF' => array_merge([
            'F' => 'IF'
        ],
        Automato::getCarateresParaEstado(Automato::getLetras(['F']),  'VARIAVEL')
    ),
    'IF' => Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras()),  'VARIAVEL'),
    'F-FOR' => array_merge([
            'O' => 'O-FOR'
        ],
        Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras(['O'])),  'VARIAVEL')
    ), 
    'O-FOR' => array_merge([
            'R' => 'FOR'
        ],
        Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras(['R'])),  'VARIAVEL')
    ),
    'FOR' => Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras()),  'VARIAVEL'),
    'P-PRINT' => array_merge([
            'R' => 'R-PRINT'
        ],
        Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras(['R'])),  'VARIAVEL')
    ),
    'R-PRINT' => array_merge([
            'I' => 'I-PRINT'
        ],
        Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras(['I'])),  'VARIAVEL')
    ), 
    'I-PRINT' => array_merge([
            'N' => 'N-PRINT'
        ],
        Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras(['N'])),  'VARIAVEL')
    ),
    'N-PRINT' => array_merge([
            'T' => 'PRINT'
        ],
        Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras(['T'])),  'VARIAVEL')
    ),
    'PRINT' => Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras()),  'VARIAVEL'),
    'VARIAVEL' => Automato::getCarateresParaEstado(array_merge(Automato::getNumeros(), Automato::getLetras()),  'VARIAVEL'),
    'CONSTANTE' => array_merge(
        Automato::getCarateresParaEstado(Automato::getNumeros(), 'CONSTANTE'),
        Automato::getCarateresParaEstado(Automato::getLetras(),  'VARIAVEL')
    ),
    'RECEBE' => [
        '=' => 'IGUALDADE'
    ],
    '!-DESIGUALDADE' => [
        '=' => 'DESIGUALDADE'
    ],
];

$estadosFinais = [
    new EstadoFinal('VARIAVEL',         'Variável'),
    new EstadoFinal('IF',               'IF'),
    new EstadoFinal('FOR',              'FOR'),
    new EstadoFinal('PRINT',            'PRINT'),
    new EstadoFinal('CONSTANTE',        'Constante'),
    new EstadoFinal('MAIOR',            'Maior'),
    new EstadoFinal('MENOR',            'Menor'),
    new EstadoFinal('ABRE-PARENTESES',  'Abre Parenteses'),
    new EstadoFinal('FECHA-PARENTESES', 'Fecha Parenteses'),
    new EstadoFinal('ABRE-CHAVES',      'Abre Chaves'),
    new EstadoFinal('FECHA-CHAVES',     'Fecha Chaves'),
    new EstadoFinal('MENOS',            'Menos'),
    new EstadoFinal('MAIS',             'Mais'),
    new EstadoFinal('MULTIPLICA',       'Multiplica'),
    new EstadoFinal('DIVIDE',           'Divide'),
    new EstadoFinal('RECEBE',           'Recebe'),
    new EstadoFinal('IGUALDADE',        'Igualdade'),
    new EstadoFinal('DESIGUALDADE',     'Desigualdade'),
];

try {
    $automato = new Automato($alfabeto, $estados, $estadoInicial, $estadosFinais, $delta);
    $analisador = new AnalisadorLexico($automato);
    $entrada = implode(PHP_EOL, [
        "x = 10",
        "WHILE (x > 0){",
    ]);

    $entrada = "x = 10
    WHILE (x > 0){
       PRINT(x)
       X = x - 1
    }
    IF (x == 0)
       PRINT(0)";

    var_dump($analisador->getTokensEntrada($entrada));
} catch (Exception $ex) {
    echo $ex->getMessage();
} 