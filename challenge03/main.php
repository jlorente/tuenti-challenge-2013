#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 3 
 * Lost in Lost
 * 
 * ###TO IMPROVE###
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');
$scriptsNumber = fgets($stdin);

for ($i = 0; $i < $scriptsNumber; ++$i) {
    processScript(fgets($stdin));
}

function processScript($script)
{
    $diverges = '\.>|<\.|<>|\.\.';
    $scenes = array();
    preg_match_all('/[\.<>]{1}[^\.<>]+/', $script, $scenes);
    
    $scenes = $scenes[0];
   
    $orderedScenes = $appearance = array();
    
    $possibleBeginning = $valid = 0;
    for ($i = 0, $total = count($scenes); $i < $total; ++$i) {
        $begin = false;
        $scenes[$i] = trim($scenes[$i]);
        $order = substr($scenes[$i], 0, 1);
        $possiblePosition = 0;
        if ($order == '<') {
            $pivot = $i;
            $value = $scenes[$pivot];
            while ($pivot > 0 && substr($scenes[$pivot - 1], 1) != substr($value, 1) && substr($scenes[$pivot - 1], 0, 1) != '<') {
                $scenes[$pivot] = $scenes[$pivot - 1];
                $possiblePosition++;
                $pivot--;
            }
            
            $scenes[$pivot] = $value;
            if ($possiblePosition > 1) {
                $valid = 1;
                
                if ($pivot == 0) {
                    $begin = true;
                }
            }
        }
        
        if ($i == 0 || $begin === true) {
            $possibleBeginning++;
            if ($possibleBeginning > 1) {
                $valid = -1;
                break 1;
            }
        }
    }

    $text = $separator = '';
    if ($valid == 0) {
        for ($i = 0, $total = count($scenes); $i < $total; ++$i) {
            $value = substr($scenes[$i], 1);
            if (!isset($appearance[$value])) {
                $text .= $separator.$value;
                $separator = ',';
                $appearance[$value] = 1;
            }
        }
    } elseif ($valid < 0) {
        $text = 'invalid';
    } else {
        $text = 'valid';
    }
    echo $text.PHP_EOL;
}
