#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 17 
 * Silence on the wire
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');
$j = 0;
while (($line = fgets($stdin)) !== false) {
    $factorial = factorialN((int) trim($line));
    $result = 0;
    for ($i = 0, $t = strlen($factorial); $i < $t; ++$i) {
        $result += $factorial{$i};
    }
    echo $result.PHP_EOL;
}

function factorialN($n)
{
    $result = 1;
    for ($i = $n; $i > 0; --$i) {
        $result = bcmul($result, $i);
    }
    return $result;
}