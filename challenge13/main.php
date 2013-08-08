#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 13 
 * Sparse randomness
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');
$testNumber = fgets($stdin);

for ($i = 1; $i <= $testNumber; ++$i) {
    echo 'Test case #'.$i.PHP_EOL;
    list($nNumber, $tNumber) = explode(' ',trim(fgets($stdin)));

    $secuence = explode(' ',trim(fgets($stdin)));
    
    $sec = array();
    $pivot = $secuence[0];
    $maxRepetition = 0;
    for ($j = 0; $j < $nNumber; ++$j) {
        $sec[$j] = 1;
        if ($secuence[$j] !== $pivot) {
            $k = $j;
            $inc = 1;
            while ($k > 0 && $secuence[$k - 1] == $pivot) {
                $sec[--$k] = $inc++;
            }
            
            if ($maxRepetition < $sec[$k]) {
                $maxRepetition = $sec[$k];
            }
            $pivot = $secuence[$j];
        }
    }
    
    for ($j = 0; $j < $tNumber; ++$j) {
        list($startPoint, $endPoint) = explode(' ',trim(fgets($stdin)));
        
        $startPoint--;
        $endPoint--;
        $max = 0;
        $pivot = null;
        while ($startPoint <= $endPoint) {
            $rep = $sec[$startPoint];
            if ($startPoint + $rep > $endPoint) {
                $rep = $endPoint - $startPoint + 1;
            }
            
            if ($max < $rep) {
                $max = $rep;
                if ($max == $maxRepetition) {
                    break;
                }
            }
            $startPoint += $sec[$startPoint];
        }
        echo $max.PHP_EOL;
    }
}