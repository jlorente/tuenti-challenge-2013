<?php
/**
 * @author Jose Lorente Martín
 */
namespace PixelIsland;

use SplQueue;
use SplStack;

require_once 'Node.php';

class PixelTree
{
    /**
     * @var Node
     */
    protected $root;
    
    /**
     * @var int
     */
    protected $height;
    
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
                $nodeBlock = $queue->dequeue();
                
                foreach ($nodeBlock as $node) {
                    $string .= $node->getValue();
                    if ($node->getValue() == 'p') {
                        $queue->enqueue($node->getChildren());
                    }
                }
            }
        }
        
        return $string;
    }
    
    public function getHeight()
    {
        if ($this->height === null) {
            $this->height = 0;
            if ($this->root->getValue() == 'p') {
                $stack = new SplStack();
                
                $this->root->getChildren()->rewind();
                $stack->push($this->root->getChildren());
                
                while ($stack->isEmpty() !== true) {
                    $nodeBlock = $stack->pop();
                      
                    while ($nodeBlock->valid()) {
                        $height = $stack->count() + 1;
                        if ($this->height < $height) {
                            $this->height = $height;
                        }
                        
                        $node = $nodeBlock->current();
                        $nodeBlock->next();
                        
                        if ($node->getValue() == 'p') {
                            $nodeBlock = $node->getChildren();
                            $nodeBlock->rewind();
                            $stack->push($nodeBlock);
                        }
                    }
                }
            }
        }
        
        return $this->height;
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
    
    public function draw($imageName)
    {
        $size = pow(2, $this->getHeight());
        $image = imagecreate($size, $size);

        imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
        
        $blackColor = imagecolorallocate($image, 0x00, 0x00, 0x00);
        
        $node = $this->root;
        
        if ($this->root->getValue() == 'p') {
            $this->drawRecursive($this->root->getChildren(), $image, 0, 0, $blackColor);
        } elseif ($node->getValue() == 'b') {
            imagefilledrectangle($image, 0, 0, $this->getHeight(), $this->getHeight(), $blackColor);
        }
        
        imagegif($image, $imageName);
    }
    
    protected function drawRecursive($nodeBlock, $image, $x, $y, $blackColor)
    {
        static $height = 0;
        
        $height++;
        $pixelSize = pow(2, $this->getHeight() - $height);
        foreach ($nodeBlock as $key => $node) {
            switch ($key) {
                case 0:
                    $x += $pixelSize;
                    break;
                case 2:
                    $y += $pixelSize;
                    break;
                case 3:
                    $x += $pixelSize;
                    $y += $pixelSize;
                    break;
            }
            
            if ($node->getValue() == 'p') {
                $this->drawRecursive($node->getChildren(), $image, $x, $y, $blackColor);
            } elseif ($node->getValue() == 'b') {
                for ($i = 0; $i < $pixelSize; ++$i) {
                    for ($j = 0; $j < $pixelSize; ++$j) {
                        imagesetpixel($image, $x + $j, $y + $i, $blackColor);
                    }
                }
            }
            
            switch ($key) {
                case 0:
                    $x -= $pixelSize;
                    break;
                case 2:
                    $y -= $pixelSize;
                    break;
                case 3:
                    $x -= $pixelSize;
                    $y -= $pixelSize;
                    break;
            }
        }
        $height--;
    }
    
    public function decode()
    {
        $tmpfname = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ptree_'.date('YmdHis').'_'.uniqid().'.gif';

        $this->draw($tmpfname);
        
        $ch = curl_init();
        $data = array('name' => 'QR', 'file' => '@'.$tmpfname);
        curl_setopt($ch, CURLOPT_URL, 'http://zxing.org/w/decode');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        unlink($tmpfname);
        
        $data = array();
        preg_match('/Parsed Result(<.+?>)+(.*?)<.*?>/', $response, $data);  
        if (isset($data[2])) {
            return substr(trim(utf8_decode($data[2])), 1);
        } else {
            return 'Failed!';
        }
    }
}