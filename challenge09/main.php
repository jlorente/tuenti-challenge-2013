#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 9 
 * Defenders of the Galaxy
 * 
 * ###IN PROGRESS###
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');
$testNumber = fgets($stdin);

for ($i = 0; $i < $testNumber; ++$i) {
    $gameRules = trim(fgets($stdin));
}