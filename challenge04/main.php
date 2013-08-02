#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 4 
 * Missing numbers
 * 
 * ###NOT DONE -> INVALID###
 * 
 * @author Jose Lorente Martin
 */
$numbers = fopen('integers', 'r');

$numberStore = array();
$loops = 0;
while (($number = fgets($numbers)) !== false) {
    if (trim($number) == '') { 
        continue;
    }
    
    $n = unpack('Lvar', $number);
    echo $n['var'].PHP_EOL;
    
    if (!isset($n['var'])) {
        var_dump($number);
        echo $loops.PHP_EOL;
        var_dump($n);die;
    }
    
    /*if ($loops == 85) {
        echo 'fin';
       die;
    }*/
    $numberStore[$n['var']] = 1;
    ++$loops;
}

echo 'fin';