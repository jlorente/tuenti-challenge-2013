<?php
/**
 * @author Jose Lorente Martin
 */
namespace IceCave;

use IceCave;
use SplStack;

require_once 'Player.php';
require_once 'IceCave.php';

class Robot
{
    /**
     * @var IceCave
     */
    protected $iceCave;
    
    protected $minSecondsSpent;
    
    /**
     * @var SplStack
     */
    protected $playerStates;
    
    public function __construct(IceCave $iceCave)
    {
        $this->iceCave = $iceCave;
        $this->playerStates = new SplStack();
        
        $initialPlayer = new Player($iceCave);
        list($xInit, $yInit) = $this->iceCave->getInitPosition();
        $initialPlayer->setCoords($xInit, $yInit);
        $this->playerStates->push($initialPlayer);
    }
    
    public function start()
    {
        while ($this->playerStates->isEmpty() === false) {
            $player = $this->playerStates->top();
            
            list($xCoord, $yCoord) = $player->getCoords();
            $map = $this->iceCave->getMap();
            if ($map[$xCoord][$yCoord] == 'O') {
                if ($this->minSecondsSpent === null || $player->getSecondsSpent() < $this->minSecondsSpent) {
                    $this->minSecondsSpent = $player->getSecondsSpent();
                    $this->playerStates->pop();
                    continue;
                }
            }
            
            if ($this->minSecondsSpent === null || $player->getSecondsSpent() < $this->minSecondsSpent) {
                while (($movement = $player->getMove()) !== null) {
                    $xModifier = $movement['x'];
                    $yModifier = $movement['y'];
                    if ($this->canMoveToPosition($xCoord + $xModifier, $yCoord + $yModifier)) {
                        $newPlayer = clone $player;
                        $newPlayer->unsetMovement($movement['from']);
                        $newPlayer->unsetMovement($movement['to']);
                        $i = 0;
                        while ($this->canMoveToPosition($xCoord + $xModifier, $yCoord + $yModifier) === true) {
                            ++$i;
                            $xCoord += $xModifier;
                            $yCoord += $yModifier;
                        }
                        
                        if ($newPlayer->hasVisitedPosition($xCoord, $yCoord) !== true) {
                            $newPlayer->addSecondsSpent($this->iceCave->getFreezeTime() + $i / $this->iceCave->getSpeed());
                            $newPlayer->setCoords($xCoord, $yCoord);
                            $this->playerStates->push($newPlayer);
                            continue 2;
                        }
                        
                    }
                }
            }
            
            $this->playerStates->pop();
        }
    }

    public function getMinSecondsSpent()
    {
        return (int) round($this->minSecondsSpent, 0);
    }
    
    public function canMoveToPosition($posX, $posY)
    {
        $map = $this->iceCave->getMap();
        return isset($map[$posX][$posY]) && $map[$posX][$posY] !== '#';
    }
}