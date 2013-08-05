<?php
/**
 * @author Jose Lorente Martin
 */
class IceCave
{                            
    protected $rows;
    
    protected $columns;
    
    protected $map = array();
    
    protected $speed;
    
    protected $freezeTime;
    
    protected $initX;
    
    protected $initY;
    
    protected $exitX;
    
    protected $exitY;
    
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
    
    public function getInitPosition()
    {
        return array($this->initX, $this->initY);
    }
    
    public function getExitPosition()
    {
        return array($this->exitX, $this->exitY);     
    }
    
    public function getMap()
    {
        return $this->map;
    }
    
    public function getFreezeTime()
    {
        return $this->freezeTime;    
    }
    
    public function getSpeed()
    {
        return $this->speed;
    }
    
    public function addMapLine($line)
    {
        $line = preg_split('//u', $line, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < $this->columns; ++$i) {
            $arr[$i] = $line[$i];
            if ($arr[$i] === 'X') {
                $this->initX = count($this->map);
                $this->initY = $i;
            } elseif ($arr[$i] === 'O') {
                $this->exitX = count($this->map);
                $this->exitY = $i;
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
}