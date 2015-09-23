<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCCache
 *
 * @author paolo
 */



final class PCCache {
    
    /**
     * La vera implementazione della cache
     * @var PCCacheProvider 
     */
    private static $chacheProvider = NULL;

    
    /**
     *
     * @return PCCacheProvider 
     */
    public static function cacheProvider(){
        return static::$chacheProvider;
    }
    
    /**
     * 
     * @param PCCacheProvider $cp
     */
    public static function setDefaultCacheProvider($cp){
        if(static::$chacheProvider == null){
            static::$chacheProvider = $cp;
        }
    }
    
   
    /**
     * @return PCCacheAPC
     */
    public static function getAPCCacheProvider(){
        static $c = NULL;
        if($c == NULL){
            $c = new PCCacheAPC();
        }
        return $c;
    }
    
    /**
     * @return PCCacheMemcached
     */
    public static function getMemcachedProvider(){
        static $c = NULL;
        if($c == NULL){
            $c = new PCCacheMemcached();
        }
        return $c;
    }
    
    /**
     * @return PCCacheTest
     */
    public static function getTestCacheProvider(){
        static $c = NULL;
        if($c == NULL){
            $c = new PCCacheTest();
        }
        return $c;
    }
    
}

