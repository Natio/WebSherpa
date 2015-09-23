<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCCacheAPC
 *
 * @author paolo
 */
class PCCacheAPC implements PCCacheProvider{
    
    
    public function __construct() {
        if(!extension_loaded('apc')){
            throw new Exception("APC is not loaded", 500);
        }
    }
    
    public function getItem($key) {
        $success = FALSE;
        $result = apc_fetch($key, $success);
        return ($success ? $result : NULL);
    }

    public function removeItem($key) {
        return apc_delete($key);
    }

    public function setItem($item, $key, $expiration = 0) {
        return apc_add($key, $item, $expiration);
    }    //put your code here
}

?>
