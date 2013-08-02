<?php
/**
 * @author Jose Lorente Martin
 */
class IceCave
{
    protected static $nestingControl = 100;
    
    protected static $movements = array(
                                    'N'   => array('x' => -1, 'y' =>  0),
                                    'E'   => array('x' =>  0, 'y' =>  1),
                                    'S'	  => array('x' =>  1, 'y' =>  0),
                                    'W'   => array('x' =>  0, 'y' => -1)
                                 );
                                     
    protected $rows;
    
    protected $columns;
    
    protected $map = array();
    
    protected $speed;
    
    protected $freezeTime;
    
    protected $virtualSeconds;
   
    protected $minSeconds;
    
    protected $currentX;
    
    protected $currentY;
    
    protected $lastCoords;
    
    protected $secuence;
    
    public function __construct($physics)
    {
        $normalized = array();
        preg_match_all('/[0-9]+/', $physics, $normalized);
        
        $this->columns = $normalized[0][0];
        $this->rows = $normalized[0][1];
        $this->speed = $normalized[0][2];
        $this->freezeTime = $normalized[0][3];
    }
    
    public function getRows()
    {
        return $this->rows;
    }
    
    public function getCurrentPosition()
    {
        return $this->currentX.','.$this->currentY;
    }
    
    public function addMapLine($line)
    {
        $line = preg_split('//u', $line, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < $this->columns; ++$i) {
            $arr[$i] = $line[$i];
            if ($arr[$i] === 'X') {
                $this->currentX = count($this->map);
                $this->currentY = $i;
            }
        }
        $this->map[] = $arr;
    }
    
    public function display()
    {
        for ($i = 0; $i < $this->rows; ++$i) {
            for ($j = 0; $j < $this->columns; ++$j) {
                echo $this->map[$i][$j];
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }

    public function getMinSeconds()
    {
        if ($this->minSeconds === null) {
            $this->searchExit();
        }
        return (int) round($this->minSeconds, 0);
    }
    
    protected function searchExit()
    {
        if (($this->minSeconds !== null && $this->virtualSeconds > $this->minSeconds) || isset($this->secuence[$this->lastCoords])) {
            return;
        }
        
        if ($this->map[$this->currentX][$this->currentY] === 'O') {
            $this->minSeconds = $this->virtualSeconds;
            return;
        }

        $this->secuence[$this->lastCoords] = 1;

        foreach (self::$movements as $movement) {
            $this->move($movement['x'], $movement['y']);
        }
    }

    protected function move($xInc, $yInc)
    {
        if ($this->canMoveToPosition($this->currentX + $xInc, $this->currentY + $yInc) === true) {
            $xStore = $this->currentX;
            $yStore = $this->currentY;
            $lastCoords = $this->lastCoords;
            $virtualSeconds = $this->virtualSeconds;
            $secuence = $this->secuence;
            
            $this->virtualSeconds += $this->freezeTime;

            $i = 0;
            while ($this->canMoveToPosition($this->currentX + $xInc, $this->currentY + $yInc) === true) {
                $this->lastCoords = $this->currentX.','.$this->currentY;
                
                ++$i;
                $this->currentX += $xInc;
                $this->currentY += $yInc;
            }
            
            $this->virtualSeconds += $i/$this->speed;
            
            $this->searchExit();
   
            $this->virtualSeconds = $virtualSeconds;
            $this->currentX = $xStore;
            $this->currentY = $yStore;
            $this->lastCoords = $lastCoords;
            $this->secuence = $secuence;
        }
        return;
    }

    public function canMoveToPosition($posX, $posY)
    {
        return isset($this->map[$posX][$posY]) && $this->map[$posX][$posY] !== '#' && $posX.','.$posY !== $this->lastCoords;
    }
}