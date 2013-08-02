#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 1 
 * Bitcoin to the future
 * 
 * @author Jose Lorente Martin
 */
use BitCoinToTheFuture\PredictionMachine;

require_once 'PredictionMachine.php';

$stdin = fopen('php://stdin', 'r');
$testNumber = fgets($stdin);
for ($i = 0; $i < $testNumber; ++$i) {
    $amount = trim(fgets($stdin));
    
    $predictions = array();
    preg_match_all('/-{0,1}[0-9]+/', trim(fgets($stdin)), $predictions);
    $predictions = $predictions[0];

    $predictionMachine = new PredictionMachine($amount, $predictions);
    echo $predictionMachine->calculateMaximumAmount().PHP_EOL;
}