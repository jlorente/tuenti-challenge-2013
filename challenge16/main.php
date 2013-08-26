#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 16 
 * Legacy code
 * 
 * The script to run the Turing Machine multiplies the first element
 * of the tape with the sum of the following.
 * 
 * Class TuringMachine works correctly but takes too much time to 
 * compute the result.
 * 
 * @author Jose Lorente Martin
 */
require 'TuringMachine.php';
$stdin = fopen('php://stdin', 'r');

/*
$turingMachine = new TuringMachine();
$machineScript = fopen(realpath(dirname(__FILE__)).'/machineScript.txt', 'r');
while (($line = fgets($machineScript)) !== false) {
    $turingMachine->addScriptLine(trim($line));
}
fclose($machineScript);

while (($line = fgets($stdin)) !== false) {
    $tapeString = trim($line);
    
    $tape = new ArrayObject(str_split($tapeString));
    $turingMachine->setTape($tape);
    $turingMachine->compute();
    
    $computedTape = $turingMachine->getTape();
    
    $tapeString = '';
    foreach ($computedTape as $tapeElement) {
        if ($tapeElement != '_') {
            $tapeString .= $tapeElement;
        }
    }
    echo $tapeString.PHP_EOL;
}*/

while (($line = fgets($stdin)) !== false) {
    $tapeString = trim($line);
    $tape = explode('#', $tapeString);
    
    $result = 0;
    $nBits = strlen($tape[1]);
    
    $multi = bindec($tape[1]);
    for ($i = 2, $t = count($tape) - 1; $i < $t; ++$i) {
        $result += bindec($tape[$i]);
    }
    
    $result = decbin($multi * $result);
    if (strlen($result) > $nBits) {
        $result = str_repeat('1', $nBits);
    } else {
        $result = str_pad($result, $nBits, '0', STR_PAD_LEFT);
    }
    echo '#'.$result.'#'.PHP_EOL;
}