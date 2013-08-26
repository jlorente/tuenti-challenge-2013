#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 15 
 * The only winning move is not to play
 * 
 * @author Jose Lorente Martin
 */
require 'KeyFinder.php';

$stdin = fopen('php://stdin', 'r');
$keyFinder = new KeyFinder(trim(fgets($stdin)));

echo $keyFinder->getKey();