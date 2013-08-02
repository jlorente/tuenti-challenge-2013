<?php
/**
 * @author Jose Lorente Martin
 */
namespace Util;

use \Util\Trie\Node;

require_once 'Node.php';

/**
 * Trie data structure implementation to store dictionaries and 
 * perform fast retrievals of preffixes and full words.
 * 
 * @author Jose Lorente Martin
 * @package Trie
 */
class Trie
{
    protected $hashMap = array();
    
    public function __construct(array $wordArray = array())
    {
        foreach ($wordArray as $word) {
            $this->addWord($word);
        }
    }
    
    public function addWord($word)
    {
        $charWord = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
        
        if (!isset($this->hashMap[$charWord[0]])) {
            $this->hashMap[$charWord[0]] = new Node($charWord[0]);
        }
        
        $currentNode = $this->hashMap[$charWord[0]];
        $stringCompount = $charWord[0];
        for ($i = 1, $l = count($charWord); $i < $l; ++$i) {
            $stringCompount .= $charWord[$i];
            if ($currentNode->isChild($stringCompount) === false) {
                $currentNode->addChild(new Node($stringCompount));
            }
            $currentNode = $currentNode->getChild($stringCompount);
        }
        $currentNode->setIsWord(true);
    }
    
    public function containsPreffix($preffix)
    {
        $charWord = preg_split('//u', $preffix, -1, PREG_SPLIT_NO_EMPTY);
        return $this->contains($charWord);
    }
    
    public function containsWord($word)
    {
        $charWord = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
        return $this->contains($charWord, true);
    }
    
    protected function contains(array $charWord, $isWord = false)
    {
        $node = $this->getNode($charWord);
        return ($isWord !== true && $node !== null) || ($isWord === true && $node !== null && $node->isWord() === true);
    }
    
    protected function getNode(array $charWord)
    {
        $node = null;
        if (isset($this->hashMap[$charWord[0]])) {
            $node = $this->hashMap[$charWord[0]];
            $stringCompount = $charWord[0];
            for ($i = 1, $l = count($charWord); $i < $l; ++$i) {
                $stringCompount .= $charWord[$i];
                if ($node->isChild($stringCompount) === false) {
                    $node = null;
                    break;
                }
                $node = $node->getChild($stringCompount);
            }
        }
        
        return $node;
    }
}