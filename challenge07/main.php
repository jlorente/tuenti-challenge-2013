#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 7 
 * Boozzle
 * 
 * @author Jose Lorente Martin
 */
use Boozzle\Robot;
use Boozzle\Boozzle;

require_once 'Robot.php';

$stdin = fopen('php://stdin', 'r');
$testNumber = (int) trim(fgets($stdin));

$robot = new Robot();
for ($i = 0; $i < $testNumber; ++$i) {
    $characterScores = trim(fgets($stdin));
    $gameDuration = (int) trim(fgets($stdin));
    $rows = (int) trim(fgets($stdin));
    $columns = (int) trim(fgets($stdin));
    
    $boozzle = new Boozzle($characterScores, $gameDuration, $rows, $columns);
    
    for ($j = 0; $j < $rows; ++$j) {
        $boozzle->addBoardRow(fgets($stdin));
    }
    $robot->setBoozzle($boozzle);
    $robot->start();
    echo $robot->getScore().PHP_EOL;
}