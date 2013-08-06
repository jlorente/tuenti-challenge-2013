<?php
/**
 * @author Jose Lorente Martín
 */
namespace PixelIsland;

use SplFixedArray;
use Exception;

class NodeBlock extends SplFixedArray
{
    public function offsetSet($index, $newval)
    {
        if (!($newval instanceof Node)) {
            throw new Exception('NodeBlock only accepts objects of class Node');
        }
        parent::offsetSet($index, $newval);
    }
}