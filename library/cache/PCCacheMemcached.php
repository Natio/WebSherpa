<?php


/**
 * Description of PCCacheMemcached
 *
 * @author paolo
 */
class PCCacheMemcached implements PCCacheProvider {
    /**
     * the Memcached instance
     * @var Memcached
     */
    private $mem;
    
    /**
     * la scadenza di un valore (5 ore)
     * @var int 
     */
    private static $DEFAULT_EXPIRATION_TIME = 18000;
    
    /**
     *
     * @var string 
     */
    private static $servers_id = "memcached_servers";
    
    public function __construct() {
        $this->mem = new Memcached(static::$servers_id);
        if(count($this->mem->getServerList()) == 0){
            $this->mem->addServer("", 11211);
            
        }
    }

    public function getItem($key) {
        return $this->mem->get($key);
    }

    public function removeItem($key) {
        return $this->mem->delete($key);
    }

    public function setItem($item, $key, $expiration = NULL) {
        return $this->mem->set($key, $item, ($expiration < 0) ? static::$DEFAULT_EXPIRATION_TIME : $expiration);
    }

    
    
}
