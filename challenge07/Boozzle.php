<?php
/**
 * @author Jose Lorente Martin
 */
namespace Boozzle;

class Boozzle
{
    protected $characterScores;
    
    protected $gameDuration;
    
    protected $rows;
    
    protected $columns;
    
    protected $board;
    
    public function __construct($characterScores, $gameDuration, $rows, $columns, $board = null)
    {
        $this->characterScores = json_decode(str_replace("'", '"', $characterScores), true);
        
        $this->gameDuration = (int) $gameDuration;
        
        $this->rows = (int) $rows;
        
        $this->columns = (int) $columns;
        
        $this->board = $board;
    }
    
    public function addBoardRow($strRow)
    {
        $normalized = array();
        preg_match_all('/[\w+]+/', $strRow, $normalized);
        
        $i = count($this->board);
        for ($j = 0; $j < $this->columns; ++$j) {
            $this->board[$i][$j] = str_split($normalized[0][$j]);
        }
    }
    
    public function getRows()
    {
        return $this->rows;
    }
    
    public function getColumns()
    {
        return $this->columns;    
    }
    
    public function getCharacterScore($char)
    {
        return !isset($this->characterScores[$char]) ? 0 : $this->characterScores[$char];
    }
    
    public function getGameDuration()
    {
        return $this->gameDuration;    
    }
    
    public function getBoardCharacter($i, $j)
    {
        return isset($this->board[$i][$j]) ? $this->board[$i][$j] : null;
    }
    
    public function display()
    {
        for ($i = 0; $i < $this->rows; ++$i) {
            $separator = '';
            for ($j = 0; $j < $this->columns; ++$j) {
                echo $separator.'['.$this->board[$i][$j][0] . ': '.($this->board[$i][$j][1] == 2 ? 'W' : 'L').'x'.$this->board[$i][$j][2].']';
                $separator = ' ';
            }
            echo PHP_EOL;
        }
        echo PHP_EOL;
    }
}