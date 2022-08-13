<?php
echo "<pre>";
var_dump(str_split("x = 10
WHILE (x > 0){
   PRINT(x)
   X = x - 1
}
IF (x == 0)
   PRINT(0)"));

die;

require_once 'Automato.php';

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
    new EstadoFinal('MAIOR',            '>'),
    new EstadoFinal('MENOR',            '<'),
    new EstadoFinal('ABRE-PARENTESES',  '('),
    new EstadoFinal('FECHA-PARENTESES', ')'),
    new EstadoFinal('ABRE-CHAVES',      '{'),
    new EstadoFinal('FECHA-CHAVES',     '}'),
    new EstadoFinal('MENOS',            '-'),
    new EstadoFinal('MAIS',             '+'),
    new EstadoFinal('MULTIPLICA',       '*'),
    new EstadoFinal('DIVIDE',           '/'),
    new EstadoFinal('RECEBE',           '='),
    new EstadoFinal('IGUALDADE',        '=='),
    new EstadoFinal('DESIGUALDADE',     '!='),
];

try {
    $automato = new Automato($alfabeto, $estados, $estadoInicial, $estadosFinais, $delta);
    $analisador = new AnalisadorLexico($automato);
    var_dump($analisador->getTokensEntrada(""));
} catch (Exception $ex) {
    echo $ex->getMessage();
}