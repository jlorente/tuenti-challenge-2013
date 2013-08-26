<?php
/**
 * @author Jose Lorente Martin
 */
namespace TuringMachine;

class State
{
    protected $instructions;
    
    public function __construct($read, $write, $moveTape, State $nextState = null)
    {
        $this->instructions[read] = array(
        								'write'     => $write,
                                        'moveTape'  => $moveTape,
                                        'nextState' => $nextState
                                    );
    }
    
    
    public function getInstructions($symbol)
    {
        if (!isset($this->instructions[$symbol])) {
            return;
        }
        return $this->instructions[$symbol];
    }
}