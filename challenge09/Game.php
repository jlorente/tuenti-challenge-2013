<?php
namespace DefendersOfGalaxy;

class Game
{
    protected $width;
    
    protected $length;
    
    protected $soldierPrice;
    
    protected $crematoriumPrice;
    
    protected $goldAmount;
    
    public function __construct($gameRules)
    {
        $normalized = array();
        preg_match_all('/[0-9]+/', $gameRules, $normalized);
        
        $this->width = $normalized[0][0];
        $this->length = $normalized[0][1];
        $this->soldierPrice = $normalized[0][2];
        $this->crematoriumPrice = $normalized[0][3];
        $this->goldAmount = $normalized[0][4];
    }
    
    public function analyze()
    {
        return $this->analyzeSoldiersFirs();
    }
    
    protected function analyzeSoldiersFirs()
    {
        $total = $this->width * $this->length;
        $total -= $this->width;
        
        $nCrematoriums = 0;
        $nSoldiers = floor($this->goldAmount / $this->soldierPrice);
        $goldAmount = $this->goldAmount % $this->soldierPrice;
        
        if ($nSoldiers >= $this->width)  {
            $result = -1;
        } else {
            $result = 0;
            $controlGt = $controlLt = 0;
            while ($nSoldiers >= 0) {
                if ($goldAmount >= $this->crematoriumPrice) {
                    $nCrematoriums += floor($goldAmount / $this->crematoriumPrice);
                    $goldAmount = $goldAmount % $this->crematoriumPrice;
                }
                
                $seconds = floor($total / ($this->width - $nSoldiers)) + 1;
                $seconds *= ($nCrematoriums + 1);

                if ($seconds > $result) {
                    $result = $seconds;
                    if ($goldAmount == 0) {
                        $controlGt++;
                        $controlLt = 0;
                        if ($controlGt > 1) {
                            return $this->analyzeCrematoriumsFirst();
                        }
                    }
                } elseif ($seconds < $result)  {
                    if ($goldAmount == 0) {
                        $controlLt++;
                        $controlGt = 0;
                        if ($controlLt > 1) {
                            break;
                        }
                    }
                }
                
                $nSoldiers--;
                $goldAmount += $this->soldierPrice;
            }
        }
        return $result;
    }
    
    protected function analyzeCrematoriumsFirst()
    {
        $total = $this->width * $this->length;
        $total -= $this->width;
        
        $nSoldiers = 0;
        $nCrematoriums = floor($this->goldAmount / $this->crematoriumPrice);
        $goldAmount = $this->goldAmount % $this->crematoriumPrice;
        
        $result = 0;
        $controlLt = 0;
        while ($nCrematoriums >= 0) {
            if ($goldAmount >= $this->soldierPrice) {
                $nSoldiers += floor($goldAmount / $this->soldierPrice);
                $goldAmount = $goldAmount % $this->soldierPrice;
            }
            
            $seconds = floor($total / ($this->width - $nSoldiers)) + 1;
            $seconds *= ($nCrematoriums + 1);

            if ($seconds > $result) {
                $result = $seconds;
            } else {
                if ($goldAmount == 0) {
                    $controlLt++;
                    if ($controlLt > 1) {
                        break;
                    }
                }
            }
            
            $nCrematoriums--;
            $goldAmount += $this->crematoriumPrice;
        }
        
        return $result;
    }
}