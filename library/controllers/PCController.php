<?php

/**
 * Description of PCController
 *
 * @author paolo
 */
abstract class PCController {
    //put your code here
    
    public function handleRequest($request){
       
        throw new PCExceptionRoute("",404);
    }

    /**
     * 
     * @param string $method HTTP method name
     * @return boolean
     */
    public function supportsHTTPMethod($method){
        return FALSE;
    }
    
    public static function require_ssl(){
        if(defined('DEBUG')) return;
        if (isset($_SERVER["HTTPS"]) == false || strcasecmp($_SERVER["HTTPS"], "OFF") == 0) {
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit();
        }
    }
}

