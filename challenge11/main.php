#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 11 
 * The escape from Pixel Island
 * 
 * This programs requires PHP GD library
 * 
 * @author Jose Lorente Martin
 */
use PixelIsland\PixelTree;

require_once 'PixelTree.php';

$stdin = fopen('php://stdin', 'r');
$testNumber = fgets($stdin);

for ($i = 0; $i < $testNumber; ++$i) {
    $line = trim(fgets($stdin));
   
    if ($line != '') {
        $codes = explode(' ',trim($line));
        $pTrees = array();
        foreach ($codes as $code) {
            $pTrees[] = PixelTree::createFromString($code);
        }
    
        $pivot = $pTrees[0];
        for ($j = 1, $t = count($pTrees); $j < $t; ++$j) {
            $pivot = PixelTree::sum($pivot, $pTrees[$j]);
        }
        
        echo $pivot->decode().PHP_EOL;
    }
}