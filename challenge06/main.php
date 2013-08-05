#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 6 
 * Ice Cave
 * 
 * @author Jose Lorente Martin
 */
require_once 'IceCave.php';
require_once 'Robot.php';

$stdin = fopen('php://stdin', 'r');
$testNumber = (int) trim(fgets($stdin));

for ($i = 0; $i < $testNumber; ++$i) {
    $iceCave = new IceCave(trim(fgets($stdin)));
    
    for ($j = 0; $j < $iceCave->getRows(); ++$j) {
        $str = trim(fgets($stdin));
        $iceCave->addMapLine($str);
    }
    
    $robot = new IceCave\Robot($iceCave);
    $robot->start();
    echo $robot->getMinSecondsSpent().PHP_EOL;
}