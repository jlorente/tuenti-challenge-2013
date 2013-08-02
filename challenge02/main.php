#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 2 
 * Did you mean...?
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');

fgets($stdin);
$dictionaryFile = trim(fgets($stdin));

fgets($stdin);
$numberOfWords = (int) trim(fgets($stdin));

$dictionary = file(realpath(dirname(__FILE__)).'/'.$dictionaryFile);

fgets($stdin);
for ($i = 0; $i < $numberOfWords; ++$i) {
    while (($line = trim(fgets($stdin))) == '') {}
    $text = $line.' ->';
    
    foreach ($dictionary as $wordInDictionary) {
        $wordInDictionary = trim($wordInDictionary);
        
        if (isAnagram($line, $wordInDictionary) === true) {
            $text .= ' '.$wordInDictionary;
        }
    }
    
    echo $text.PHP_EOL;
}

function isAnagram($wordA, $wordB)
{
    if ($wordA == $wordB) {
        return false;
    }
    
    $wordA = str_split($wordA);
    $wordB = str_split($wordB);
    
    $lengthA = count($wordA);
    $lengthB = count($wordB);
    
    if ($lengthA == $lengthB) {
        $isAnagram = true;
        for ($i = 0; $i < $lengthA; ++$i) {
            $match = false;
            for ($j = 0; $j < $lengthB; ++$j) {
                if ($wordA[$i] == $wordB[$j]) {
                    $wordB[$j] = $wordB[$lengthB - 1];
                    unset($wordB[$lengthB - 1]);
                    --$lengthB;
                    $match = true;
                    break 1;
                }
            }
            
            if ($match === false) {
                $isAnagram = false;
                break;
            }
        }
    } else {
        $isAnagram = false;
    }
    
    return $isAnagram;
}