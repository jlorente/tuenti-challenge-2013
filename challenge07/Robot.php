<?php
/**
 * @author Jose Lorente Martin
 */
namespace Boozzle;

use \Util\Trie;

require_once realpath(dirname(__FILE__).'/..').'/utilities/trie/Trie.php';
require_once 'Boozzle.php';

class Robot
{
    protected $dictionary;

    protected static $movements = array(
                                        'N'   => array('x' => -1, 'y' =>  0),
                                        'NE'  => array('x' => -1, 'y' =>  1),
                                        'E'   => array('x' =>  0, 'y' =>  1),
                                        'SE'  => array('x' =>  1, 'y' =>  1),
                                        'S'	  => array('x' =>  1, 'y' =>  0),
                                        'SW'  => array('x' =>  1, 'y' => -1),
                                        'W'   => array('x' =>  0, 'y' => -1),
                                        'NW'  => array('x' => -1, 'y' => -1)
                                     );
    
    protected $boozzle;
                                     
    protected $maxScore;
    
    protected $calculated;
    
    protected $scoredWords = array();
    
    protected $remainingSeconds;
    
    protected $secuence = array();
    
    protected $currentX;
    
    protected $currentY;
    
    protected $currentCharacters;
    
    protected $virtualScore;
    
    protected $virtualWordMultiplier;
    
    public function __construct($dictionaryPathFile = 'http://staticak1.tuenti.com/contest2013/boozzle-dict.txt')
    {
        $this->loadDictionary($dictionaryPathFile);
    }
    
    public function loadDictionary($dictionaryPathFile = 'http://staticak1.tuenti.com/contest2013/boozzle-dict.txt')
    {
        $input = fopen($dictionaryPathFile, 'r');
        $this->dictionary = new Trie();
        while (($word = fgets($input)) !== false) {
            $this->dictionary->addWord(trim($word));
        }
    }
    
    public function setBoozzle(Boozzle $boozzle)
    {
        $this->reset();
        
        $this->boozzle = $boozzle;
        $this->remainingSeconds = $boozzle->getGameDuration();
    }
    
    protected function reset()
    {
        $this->boozzle = null;
        
        $this->maxScore = 0;
        $this->calculated = false;
        
        $this->scoredWords = array();
        $this->remainingSeconds = 0;
        $this->secuence = array();
        $this->currentX = 0;
        $this->currentY = 0;
        $this->currentCharacters = '';
        $this->virtualScore = 0;
        $this->virtualWordMultiplier = 1;
    }
    
    public function getScore()
    {
        if ($this->calculated === false) {
            $wordNumber = count($this->scoredWords);
            if ($wordNumber > 0) {
                $totalSeconds = $this->boozzle->getGameDuration();
                
                $m = array(array());
                for ($i = 0; $i <= $totalSeconds; ++$i) {
                    $m[0][$i] = 0;
                }

                $i = 1;
                foreach ($this->scoredWords as $scoredWord) {
                    $score = $scoredWord['score'];
                    $secondSpent = $scoredWord['seconds'];
                    
                    for ($j = 0; $j <= $totalSeconds; ++$j) {
                        if ($j >= $secondSpent) {
                            $m[$i][$j] = max($m[$i - 1][$j], $m[$i - 1][$j - $secondSpent] + $score);
                        } else {
                            $m[$i][$j] = $m[$i - 1][$j];
                        }
                    }
                    ++$i;
                }
    
                $this->maxScore = $m[$wordNumber][$totalSeconds];
            }
            $this->calculated = true;
        }
        return $this->maxScore;
    }
    
    public function start()
    {
        if ($this->boozzle === null) {
            throw new Exception('Unable to start a game without a Boozzle');
        }
        
        $this->remainingSeconds--;
        for ($i = 0; $i < $this->boozzle->getRows(); ++$i) {
            for ($j = 0; $j < $this->boozzle->getColumns(); ++$j) {
                $this->currentX = $i;
                $this->currentY = $j;
                
                $this->moveTo(0, 0);
            }
        }
    }
    
    public function displayWords()
    {
        foreach ($this->scoredWords as $word) {
            echo $word['word'].' => Pps: '.$word['pps'].', Score: '.$word['score'].', Seconds: '.$word['seconds'].PHP_EOL;
        }
    }
    
    protected function searchSolutions()
    {
        $this->scorePosition();
       
        if ($this->remainingSeconds > 0) {
            foreach (self::$movements as $movement) {
                if ($this->canMoveTo($this->currentX + $movement['x'], $this->currentY + $movement['y']) === true) {
                    $this->moveTo($movement['x'], $movement['y']);
                }
            }
        }
    }

    protected function moveTo($xPos, $yPos)
    {
        $state = $this->getState();
        
        $this->currentX += $xPos;
        $this->currentY += $yPos;
        
        $this->searchSolutions();
        
        $this->restoreState($state);
    }
    
    protected function scorePosition()
    {
        $this->remainingSeconds--;
        
        $this->secuence[$this->currentX.','.$this->currentY] = 1;
        $character = $this->boozzle->getBoardCharacter($this->currentX, $this->currentY);
        
        $this->currentCharacters .= $character[0];
        $this->virtualScore += ($character[1] != 1 ? 1 : $character[2]) * $this->boozzle->getCharacterScore($character[0]);
        
        if ($character[1] == 2 && $character[2] > $this->virtualWordMultiplier) {
            $this->virtualWordMultiplier = $character[2];
        }
        
        if ($this->dictionary->containsWord($this->currentCharacters)) {
            $score = $this->virtualScore * $this->virtualWordMultiplier + strlen($this->currentCharacters);
            if (!isset($this->scoredWords[$this->currentCharacters]) || $this->scoredWords[$this->currentCharacters]['score'] < $score) {
                $seconds = $this->boozzle->getGameDuration() - $this->remainingSeconds;
                $this->scoredWords[$this->currentCharacters] = array('word' => $this->currentCharacters, 'score' => $score, 'seconds' => $seconds, 'pps' => $score / $seconds);
            }
        }
    }
    
    protected function getState()
    {
        return array(
                    'currentX'     => $this->currentX,
                    'currentY'     => $this->currentY,
                    'secuence'	   => $this->secuence,
                    'virtualScore' => $this->virtualScore,
                    'remainingSeconds'	=> $this->remainingSeconds,
                    'currentCharacters' => $this->currentCharacters,
                    'virtualWordMultiplier' => $this->virtualWordMultiplier
                );
    }
    
    protected function restoreState($state)
    {
        $this->currentX = $state['currentX'];
        $this->currentY = $state['currentY'];
        $this->secuence = $state['secuence'];
        $this->virtualScore = $state['virtualScore'];
        $this->remainingSeconds = $state['remainingSeconds'];
        $this->currentCharacters = $state['currentCharacters'];
        $this->virtualWordMultiplier = $state['virtualWordMultiplier'];
    }
    
    protected function canMoveTo($xPos, $yPos)
    {
        $char = $this->boozzle->getBoardCharacter($xPos, $yPos);
        return $char !== null && !isset($this->secuence[$xPos.','.$yPos]) 
                && $this->dictionary->containsPreffix($this->currentCharacters.$char[0]) === true;
    }
}