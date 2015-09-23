<?php


/**
 *
 * @author paolo
 */
interface PCCacheProvider {
    /**
     * Stores an item in cache
     * @param mixed $item
     * @param string $key
     * @param int $expiration seconds
     */
    public function setItem($item, $key, $expiration = NULL);
    
    /**
     * Returns an item in cache
     * @param string $key
     * @return mixed|boolean
     */
    public function getItem($key);
    
    /**
     * Removes an item in cache
     * @param string $key
     * @return boolean
     */
    public function removeItem($key);
}
