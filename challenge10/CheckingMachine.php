<?php
class CheckingMachine
{
    protected $hash;
    
    protected $multiplier = 1;
    
    protected $string;
    
    public function __construct()
    {
        $this->hash = hash_init('md5');
    }
    
    public function setString($string)
    {
        $this->string = $string;
    }
    
    public function getHash()
    {
        
    }
    
    protected function recursive()
    {
        $state = $this->getState();
        
        $length = strlen($this->string);
        $string = '';
        for ($i = 0; $i < $length; ++$i) {
            if (preg_match('/[a-z][A-Z]/', $line{$i}) !== false) {
                $string .= $line{$i};
            }
            if ($line{$i} == '[') {
                $k = $i;
                $counter = 1;
                while ($counter > 0) {
                    ++$k;
                    if ($line{$k} == '[') {
                        $counter++;
                    }
                    if ($line{$k} == ']') {
                        $counter--;
                    }
                }
            }
        }
        $length = strlen($this->string);
    }
    
    protected function getState()
    {
        return array(
        			'string'     => $this->string,
                    'multiplier' => $this->multiplier
                );
    }
    
    protected function restoreState(array $state)
    {
        $this->string = $state['string'];
        $this->multiplier = $state['multiplier'];
    }
}