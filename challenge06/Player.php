<?php
/**
 * @author Jose Lorente Martin
 */
namespace IceCave;

use IceCave;

class Player
{
    private static $validMovements = array(
                                        'N' => array('to' => 'N', 'from' => 'S', 'x' => -1, 'y' => 0),
                                        'E' => array('to' => 'E', 'from' => 'W', 'x' =>  0, 'y' => 1),
                                        'S' => array('to' => 'S', 'from' => 'N', 'x' =>  1, 'y' => 0),
                                        'W' => array('to' => 'W', 'from' => 'E', 'x' =>  0, 'y' => -1)
                                       );
    protected $xPos;
    
    protected $yPos;
    
    protected $iceCave;
    
    protected $visitedPositions;
    
    protected $movements;
    
    protected $secondsSpent = 0;
    
    public function __construct(IceCave $iceCave)
    {
        $this->movements = self::$validMovements;
        $this->iceCave = $iceCave;
    }
    
    public function __clone()
    {
        $this->movements = self::$validMovements;
    }
    
    public function setCoords($xCoord, $yCoord)
    {
        $this->xPos = $xCoord;
        $this->yPos = $yCoord;
        
        $this->visitedPositions[$this->xPos.','.$this->yPos] = 1;
    }
    
    public function unsetMovement($cardinalDirection)
    {
        if (isset($this->movements[$cardinalDirection])) {
            unset($this->movements[$cardinalDirection]);
        }
    }
    
    public function addSecondsSpent($seconds)
    {
        $this->secondsSpent += $seconds;
    }
    
    public function getCoords()
    {
        return array($this->xPos, $this->yPos);
    }
    
    public function hasVisitedPosition($xCoord, $yCoord)
    {
        return isset($this->visitedPositions[$xCoord.','.$yCoord]);
    }
    
    public function getVisitedPositions()
    {
        return $this->visitedPositions;
    }
    
    public function getMove()
    {
        list($exitX, $exitY) = $this->iceCave->getExitPosition();
        if (isset($this->movements['S']) && $exitX > $this->xPos) {
            $movement = $this->movements['S'];
            unset($this->movements['S']);
        } elseif (isset($this->movements['N']) && $exitX <= $this->xPos) {
            $movement = $this->movements['N'];
            unset($this->movements['N']);
        } elseif (isset($this->movements['E']) && $exitY > $this->yPos) {
            $movement = $this->movements['E'];
            unset($this->movements['E']);
        } elseif (isset($this->movements['W']) && $exitY <= $this->yPos) {
            $movement = $this->movements['W'];
            unset($this->movements['W']);
        } else {
            $movement = array_pop($this->movements);
        }
        return $movement;
    }
    
    public function getSecondsSpent()
    {
        return $this->secondsSpent;   
    }
}