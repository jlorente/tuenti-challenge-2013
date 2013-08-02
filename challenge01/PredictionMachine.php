<?php
/**
 * @author Jose Lorente Martin
 */
namespace BitCoinToTheFuture;

class PredictionMachine
{
    protected $amount;
    
    protected $predictions = array();
    
    protected $currentBitCoins = 0;
    
    public function __construct($amount, $predictions)
    {
        $this->amount = $amount;
        $this->predictions = $predictions;
    }
    
    protected function buy($price)
    {
        $this->currentBitCoins = (int) ($this->amount / $price);
        $this->amount = $this->amount % $price;
    }

    protected function sell($price)
    {
        $this->amount += $this->currentBitCoins * $price;
        $this->currentBitCoins = 0;
    }
    
    public function calculateMaximumAmount()
    {
        for ($i = 0, $total = count($this->predictions); $i < $total; ++$i) {
            if (isset($this->predictions[$i + 1]) && $this->predictions[$i] <= $this->amount 
            && $this->predictions[$i + 1] > $this->predictions[$i]) {
                $this->buy($this->predictions[$i]);
            } elseif ($this->currentBitCoins > 0 && (!isset($this->predictions[$i + 1]) 
            || $this->predictions[$i + 1] < $this->predictions[$i])) {
                $this->sell($this->predictions[$i]);
            }
        }
        
        return $this->amount;
    }
}