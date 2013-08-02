#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 6 
 * Ice Cave
 * 
 * ###TROUBLE WITH RECURSION - Nesting Level of 100 reached in big maps###
 * 
 * @author Jose Lorente Martin
 */
require_once 'IceCave.php';

$stdin = fopen('php://stdin', 'r');
$testNumber = (int) trim(fgets($stdin));

for ($i = 0; $i < $testNumber; ++$i) {
    $iceCave = new IceCave(trim(fgets($stdin)));
    
    for ($j = 0; $j < $iceCave->getRows(); ++$j) {
        $str = trim(fgets($stdin));
        $iceCave->addMapLine($str);
    }
    
    echo $iceCave->getMinSeconds().PHP_EOL;
}