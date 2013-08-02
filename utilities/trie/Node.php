<?php
/**
 * @author Jose Lorente Martin
 */
namespace Util\Trie;

/**
 * Class that represents a single node of a Trie
 * 
 * @author Jose Lorente Martin
 * @package Util\Trie
 */
class Node
{
    protected $value;
    
    protected $children = array();
    
    protected $isWord = false;
    
    public function __construct($key)
    {
        $this->value = $key;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function isChild($key)
    {
        return isset($this->children[$key]);
    }
    
    public function addChild(Node $node)
    {
        $this->children[$node->getValue()] = $node;
    }
    
    public function getChild($key)
    {
        return $this->children[$key];
    }
    
    public function setIsWord($isWord)
    {
        $this->isWord = $isWord ? true : false;
    }
    
    public function isWord()
    {
        return $this->isWord;
    }
}