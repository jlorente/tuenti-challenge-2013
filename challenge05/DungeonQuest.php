<?php
/**
 * @author Jose Lorente Martin
 */
class DungeonQuest
{
    protected $grid = array();
    
    protected $virtualValue = 0;
    
    protected $maxValue;
    
    protected $maxValueGrid;
    
    protected $seconds;
    
    protected $currentX;
    
    protected $currentY;
    
    public function __construct($size, $initCoords, $seconds, $gemCoords)
    {
        list($mSize, $nSize) = explode(',', $size);
        list($initX, $initY) = explode(',', $initCoords);
        
        $gems = explode('#', $gemCoords);
        
        for ($i = 0; $i < $nSize; ++$i) {
            for ($j = 0; $j < $mSize; ++$j) {
                $this->grid[$i][$j] = 0;
            }
        }
        for ($i = 0, $total = count($gems); $i < $total; ++$i) {
            list($iGem, $jGem, $value) = explode(',', $gems[$i]);
            $this->grid[$iGem][$jGem] = $value;
        }
        
        $this->grid[$initX][$initY] = 'x';
        $this->seconds = $seconds;
        list($this->currentX, $this->currentY) = explode(',', $initCoords);
    }
    
    public function getGrid()
    {
        return $this->grid;    
    }
    
    public function getMaxValue()
    {
        if ($this->maxValue === null) {
            $this->calculate();
        }
        return $this->maxValue;
    }
    
    public function displayFullSolution()
    {
        echo 'MaxValue: '.$this->getMaxValue().PHP_EOL;
        echo 'Grid:'.PHP_EOL;
        $this->display();
        
        echo 'MaxValuePath:'.PHP_EOL;
        $n = count($this->maxValueGrid);
        $m = count($this->maxValueGrid[1]);
        for ($i = 0; $i < $n; ++$i) {
            $separator = '';
            for ($j = 0; $j < $m; ++$j) {
                echo $separator. $this->maxValueGrid[$i][$j];
                $separator = ' - ';
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }
    
    public function calculate()
    {   
        if ($this->seconds == 0) {
            if ($this->virtualValue > $this->maxValue) {
                $this->maxValue = $this->virtualValue;
                $this->maxValueGrid = $this->grid;
            }
            return;
        }
        
        $this->currentY--;
        $this->moveToCurrentPosition();
        $this->currentY++;

        $this->currentY++;
        $this->moveToCurrentPosition();
        $this->currentY--;

        $this->currentX--;
        $this->moveToCurrentPosition();
        $this->currentX++;

        $this->currentX++;
        $this->moveToCurrentPosition();
        $this->currentX--;
    }
    
    protected function moveToCurrentPosition()
    {
        if ($this->canMoveToCurrent() === false) {
            return;
        }
        
        $xValue = $this->grid[$this->currentX][$this->currentY];
        
        $this->seconds--;
        $this->virtualValue += $xValue;
        $this->grid[$this->currentX][$this->currentY] = 'x';
        
        $this->calculate();
        
        $this->seconds++;
        $this->grid[$this->currentX][$this->currentY] = $xValue;
        $this->virtualValue -= $xValue;
    }
    
    public function canMoveToCurrent()
    {
        return isset($this->grid[$this->currentX][$this->currentY]) && $this->grid[$this->currentX][$this->currentY] !== 'x';
    }
    
    public function display()
    {
        $n = count($this->grid);
        $m = count($this->grid[1]);
        for ($i = 0; $i < $n; ++$i) {
            $separator = '';
            for ($j = 0; $j < $m; ++$j) {
                echo $separator.$this->grid[$i][$j];
                $separator = ' - ';
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }
}