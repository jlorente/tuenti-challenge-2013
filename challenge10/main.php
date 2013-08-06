#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 10 
 * The Checking Machine
 * 
 * ###NOT FINISHED ALREADY###
 * 
 * @author Jose Lorente Martin
 */
define('MAX_STRING_SIZE',    1024 * 1024);
$stdin = fopen('php://stdin', 'r');
while (($line = fgets($stdin)) !== false) {
    $line = trim($line);
    if ($line != '') {
        ob_start(function($string) {
            return md5($string);
        });
      
        createString($line);
        ob_end_flush();
        echo PHP_EOL;
    }
}
die;
$a = 6227020800;

$hash = hash_init('md5');

$i = 0;
$repeat = 10000000;

$string = '';
$mod = floor($a / $repeat);
$rest = 7020800;

while ($mod > $i++) {
    hash_update($hash, str_repeat('a', $repeat));
    echo $i.PHP_EOL;
}
hash_update($hash, str_repeat('a', $rest));
echo hash_final($hash).PHP_EOL;
echo $a.PHP_EOL;

die;
function createString($line)
{
    $length = strlen($line);
    $string = '';
    for ($i = 0; $i < $length; ++$i) {
        if ($line{$i} == '[') {
            $k = $i;
            $counter = 1;
            while ($counter > 0) {
                ++$k;
                if ($line{$k} == '[') {
                    $counter++;
                }
                if ($line{$k} == ']') {
                    $counter--;
                }
            }
            $endCharPos = $k;
            
            $k = $i;
            $n = '';
            while ($k > 0 && is_numeric($line{$k-1})) {
                $n = $line{$k-1} . $n;
                --$k;
            }
            $initCharPos = $k-1;
            $char = $i + 1;
            $toParse = substr($line, $char, $endCharPos - $char);
            
            //echo $toParse.PHP_EOL;
            $toParse = createString($toParse);
            for ($j = 1; $j <= $n; ++$j) {
                $string .= $toParse;
            }
            $i = $endCharPos;
        } elseif (!is_numeric($line{$i})) {
            $string .= $line{$i};
        }
    }
    //echo $string.PHP_EOL;
    return $string;
}

function stringify($multiplier, $string)
{
    
}