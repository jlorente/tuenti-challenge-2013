#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Test Challenge
 * Super Hard Sum
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');

while (($line = fgets($stdin)) !== false) {
   $values = array();
   $line = trim($line);
   
   if ($line != '') {
       preg_match_all('/-{0,1}[0-9]+/', $line, $values);
    
       $sum = 0;
       foreach ($values[0] as $value) {
           $sum = bcadd($sum, $value);
       }
       
       echo $sum.PHP_EOL;
   }
}