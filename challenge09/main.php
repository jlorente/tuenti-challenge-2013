#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 9 
 * Defenders of the Galaxy
 * 
 * @author Jose Lorente Martin
 */
use DefendersOfGalaxy\Game;
require_once 'Game.php';

$stdin = fopen('php://stdin', 'r');
$testNumber = fgets($stdin);

for ($i = 0; $i < $testNumber; ++$i) {
    $game = new Game(trim(fgets($stdin)));
    echo $game->analyze().PHP_EOL;
}