#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 3 
 * Lost in Lost
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');
$scriptsNumber = fgets($stdin);

for ($j = 0; $j < $scriptsNumber; ++$j) {
    $scenes = array();
    preg_match_all('/[\.<>]{1}[^\.<>]+/', trim(fgets($stdin)), $scenes);
    $scenes = $scenes[0];
   
    $pivot = Scene::get(substr($scenes[0], 1));
    for ($i = 1, $total = count($scenes); $i < $total; ++$i) {
        $order = substr($scenes[$i], 0, 1);
        $value = substr($scenes[$i], 1);
        
        $scene = Scene::get($value);   
        if ($order == '<') {
            $scene->addNext($pivot);
        } else {
            $pivot->addNext($scene);
            if ($order == '.') {
                $pivot = $scene;
            }
        }
    }
    
    $queue = new SplQueue();
    $stack = new SplStack();
    $i = 0;
    $invalid = false;
    foreach (Scene::getScenes() as $scene) {
        if (Edge::getPreviousCount($scene) == 0) {
            $i++;
            if ($i > 1) {
                $invalid = true;
                break;
            }
            $queue->enqueue($scene);
            $stack->push($scene);
        }
    }
   
    if ($invalid !== true) {
        $orderedScenes = array();
        $nonUnique = false;
        while ($queue->isEmpty() !== true) {
            $scene = $queue->dequeue();
            $sceneCheck = $stack->pop();
            $orderedScenes[] = $scene;
            
            if ($sceneCheck !== $scene) {
                $nonUnique = true;
            }
    
            while (($nextScene = Edge::popNext($scene)) !== null) {
                if (Edge::getPreviousCount($nextScene) <= 0) {
                    $queue->enqueue($nextScene);
                    $stack->push($nextScene);
                }
            }
        }
    }
    
    if ($invalid === true || Edge::getEdgeCount() > 0) {
        echo 'invalid';
    } elseif ($nonUnique === true) {
        echo 'valid';
    } else {
        $separator = '';
        foreach ($orderedScenes as $scene) {
            echo $separator . $scene->getText();
            $separator = ',';
        }
    }
    
    echo PHP_EOL;
    
    Edge::clear();
    Scene::clear();
}

class Edge
{
    protected static $directed = array();

    protected static $reverse = array();
    
    public static function create(Scene $from, Scene $to)
    {
        self::$directed[$from->getHash()][$to->getHash()] = $to;
        self::$reverse[$to->getHash()][$from->getHash()] = $from;
    }
    
    public function popNext(Scene $from)
    {
        $pop = null;
        if (isset(self::$directed[$from->getHash()]) && count(self::$directed[$from->getHash()]) > 0) {
            $pop = array_pop(self::$directed[$from->getHash()]);
            unset(self::$reverse[$pop->getHash()][$from->getHash()]);
        } else {
            unset(self::$directed[$from->getHash()]);
        }
        
        return $pop;
    }
    
    public static function getEdgeCount()
    {
        return count(self::$directed);
    }
    
    public static function getNextsCount(Scene $from)
    {
        return isset(self::$directed[$from->getHash()]) ? count(self::$directed[$from->getHash()]) : 0;
    }
    
    public static function getPreviousCount(Scene $to)
    {
        return isset(self::$reverse[$to->getHash()]) ? count(self::$reverse[$to->getHash()]) : 0;
    }
    
    public static function clear()
    {
        self::$directed = array();
        self::$reverse = array();
    }
    
    public static function dump()
    {
        var_dump(self::$directed);
    }
}

class Scene
{
    protected static $scenes = array();
    
    protected $hash;
    
    protected $text;
    
    private function __construct($hash, $text) 
    {
        $this->hash = $hash;
        $this->text = $text;
    }
    
    public static function get($value)
    {
        $hash = md5($value);
        if (!isset(self::$scenes[$hash])) {
            self::$scenes[$hash] = new Scene($hash, $value);
        }
        return self::$scenes[$hash];
    }
    
    public static function getScenes()
    {
        return self::$scenes;
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function getHash()
    {
        return $this->hash;    
    }
    
    public function addNext(Scene $scene)
    {
        Edge::create($this, $scene);
    }
    
    public static function clear()
    {
        self::$scenes = array();
    }
}