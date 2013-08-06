<?php
/**
 * @author Jose Lorente Martín
 */
namespace PixelIsland;

use SplQueue;

require_once 'Node.php';

class PixelTree
{
    /**
     * @var Node
     */
    protected $root;
    
    /**
     * @var Node[]
     */
    protected $nodeNumber;

    public function __construct(Node $root)
    {
        $this->root = $root;
    }

    public static function createFromString($string)
    {
        $pTree = new PixelTree(new Node($string{0}));

        if ($pTree->getRoot()->getValue() == 'p') {
            $splitedString = str_split(substr($string, 1), 4);
            $parents = array($pTree->getRoot());
            $pCount = 0;
            
            for ($i = 0, $t = count($splitedString); $i < $t; ++$i) {
                for ($j = 0; $j < 4; ++$j) {
                    $node = new Node($splitedString[$i]{$j});
                    $parents[$i]->addChild($node);
                    
                    if ($node->getValue() == 'p') {
                        $parents[++$pCount] = $node;
                    }
                }
            }
        }
        
        return $pTree;
    }
    
    public function getRoot()
    {
        return $this->root;    
    }
    
    public function getString()
    {
        $string = $this->getRoot()->getValue();
        
        if ($this->getRoot()->getValue() == 'p') {
            $queue = new SplQueue();
            $queue->enqueue($this->getRoot()->getChildren());
            
            while ($queue->isEmpty() !== true) {
                $node = $queue->dequeue();
                
                foreach ($node as $internalNode) {
                    $string .= $internalNode->getValue();
                    if ($internalNode->getValue() == 'p') {
                        $queue->enqueue($internalNode->getChildren());
                    }
                }
            }
        }
        
        return $string;
    }
    
    public function __toString()
    {
        return $this->getString();
    }
    
    /**
     * @param PixelTree $a
     * @param PixelTree $b
     * @return PixelTree
     */
    public static function sum(PixelTree $a, PixelTree $b)
    {
        if (Node::compare($a->getRoot(), $b->getRoot()) > 0) {
            $root = clone $a->getRoot();
        } elseif (Node::compare($a->getRoot(), $b->getRoot()) < 0) {
            $root = clone $b->getRoot();
        } elseif (Node::compare($a->getRoot(), $b->getRoot()) == 0 && $a->getRoot()->getValue() != 'p') {
            $root = clone $a->getRoot();
        } else {
            $root = new Node('p');
            self::doNodeSum($root, $a->getRoot()->getChildren(), $b->getRoot()->getChildren());
        }

        $newPTree = new PixelTree($root);
        return $newPTree;
    }
    
    public static function doNodeSum(Node $parentNode, NodeBlock $a, NodeBlock $b)
    {
        $colorControl = array('b' => 0, 'w' => 0, 'p' => 0);
        for ($i = 0; $i < 4; ++$i) {
            if (Node::compare($a[$i], $b[$i]) > 0) {
                $parentNode->addChild(clone $a[$i]);
                $colorControl[$a[$i]->getValue()]++;
            } elseif (Node::compare($a[$i], $b[$i]) < 0) {
                $parentNode->addChild(clone $b[$i]);
                $colorControl[$b[$i]->getValue()]++;
            } elseif (Node::compare($a[$i], $b[$i]) == 0 && $a[$i]->getValue() != 'p') {
                $parentNode->addChild(clone $a[$i]);
                $colorControl[$a[$i]->getValue()]++;
            } else {
                $node = new Node('p');
                $parentNode->addChild($node);
                self::doNodeSum($node, $a[$i]->getChildren(), $b[$i]->getChildren());
               
                $colorControl[$node->getValue()]++;
            }
        }
        
        if ($colorControl['b'] == 4) {
            $parentNode->changeValue('b');
        } elseif ($colorControl['w'] == 4) {
            $parentNode->changeValue('w');
        }
    }
    
    public function draw()
    {
        
    }
}