#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 5 
 * Dungeon Quest
 * 
 * @author Jose Lorente Martin
 */
require_once 'DungeonQuest.php';

$stdin = fopen('php://stdin', 'r');
$testNumber = (int) trim(fgets($stdin));

for ($i = 0; $i < $testNumber; ++$i) {
    $size = trim(fgets($stdin));
    $initCoords = trim(fgets($stdin));
    $seconds = trim(fgets($stdin));
    $gemNumber = trim(fgets($stdin));
    $gemCoords = trim(fgets($stdin));
    
    $dungeon = new DungeonQuest($size, $initCoords, $seconds, $gemCoords);
    echo $dungeon->getMaxValue().PHP_EOL;
}