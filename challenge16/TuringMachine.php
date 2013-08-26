<?php
/**
 * @author Jose Lorente Martin
 */
class TuringMachine
{
    protected $states = array();
    
    /**
     * @var ArrayObject
     */
    protected $tape;
    
    protected static $tapeMoves = array(
        								'R' => 1,
                                        'L' => -1,
                                        'S' => 0
                                     );
                                 
    public function addScriptLine($line)
    {
        $instructions = explode(',', $line);
        list($read, $write) = explode(':', $instructions[1]);
        
        $this->states[$instructions[0]][$read] = array(
        											'write'     => $write,
                                                    'move'      => self::$tapeMoves[$instructions[2]],
                                                    'nextState' => $instructions[3]
                                                 );
    }
    
    public function setTape(ArrayObject $tape)
    {
        $this->tape = $tape;
    }
    
    public function compute()
    {
        $tapePosition = 0;
        $instruction = $this->states['start'][$this->tape[$tapePosition]];

        while (true) {
            $this->tape[$tapePosition] = $instruction['write'];
            
            $tapePosition += $instruction['move'];
            $symbol = !isset($this->tape[$tapePosition]) ? '_' : $this->tape[$tapePosition];
            if ($tapePosition < 0 || !isset($this->states[$instruction['nextState']]) 
            || !isset($this->states[$instruction['nextState']][$symbol])) {
                break;
            }

            $instruction = $this->states[$instruction['nextState']][$symbol];
        }
    }
    
    public function getTape()
    {
        return $this->tape;
    }
}