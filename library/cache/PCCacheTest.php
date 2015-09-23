<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCCacheTest
 *
 * @author paolo
 */
class PCCacheTest  implements PCCacheProvider{
    public function getItem($key) {
        return FALSE;
    }

    public function removeItem($key) {
        return TRUE;
    }

    public function setItem($item, $key, $expiration = NULL) {
        return TRUE;
    }
}


