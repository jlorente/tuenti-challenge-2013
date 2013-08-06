<?php
/**
 * @author Jose Lorente Martín
 */
namespace PixelIsland;

use Exception;

require_once 'NodeBlock.php';

class Node
{
    private static $values = array('b' => 3, 'p' => 2, 'w' => 1);
    
    protected $value;
    
    protected $children;
    
    public function __construct($value)
    {
        if (!isset(self::$values[$value])) {
            throw new Exception('Invalid value given');
        }
        
        $this->value = $value;
        if ($value == 'p') {
            $this->children = new NodeBlock(4);
        }
    }
    
    public function __clone()
    {
        if ($this->children !== null) {
            $newArray = new NodeBlock(4);
            
            $i = 0;
            foreach ($this->children as $child) {
                $newArray[$i++] = clone $child;
            }
            
            $this->children = $newArray;
        }    
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function changeValue($value)
    {
        if (!isset(self::$values[$value])) {
            throw new Exception('Invalid value given');
        }
        
        if ($value == 'p') {
            $this->children = new NodeBlock(4);
        } else {
            $this->children = null;
        }
        $this->value = $value;
    }
    
    public function addChild(Node $node)
    {
        if ($this->value != 'p') {
            throw new Exception('Only node with p value could have children');
        }
        
        $this->children[$this->children->key()] = $node;
        $this->children->next();
    }
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public static function compare(Node $a, Node $b)
    {
        if (self::$values[$a->getValue()] > self::$values[$b->getValue()]) {
            return 1;
        } elseif (self::$values[$a->getValue()] == self::$values[$b->getValue()]) {
            return 0;
        } else {
            return -1;
        }
    }
}