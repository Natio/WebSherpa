<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCResponse
 *
 * @author paolo
 */



class PCResponse {
    
    /**
     * An array of PCResponseCookies
     * @var array
     */
    private $cookies;
    
    
    /**
     * the resonse data that must be rendered
     * @var PCRenderer
     */
    private $renderer;


    /**
     * the http response code
     * @var integer
     */
    private $responseCode;
    
    /**
     * Associative array of headers
     * @var array
     */
    private $responseHeaders;
    
    /**
     *
     * @var PCResponse 
     */
    private static $currentResponse = NULL;
    
    public function __construct() {
        $this->responseCode = 200;
        $this->cookies = array();
        $this->responseHeaders = array();
    }
    
    /**
     * returns the current response
     * @return PCResponse
     */
    public static function currentResponse(){
        if(static::$currentResponse == NULL){
            static::$currentResponse = new PCResponse();
        }
        return static::$currentResponse;
    }
    /**
     * 
     * @param PCResponseCookies $c
     */
    public function addCookie($c){
        $this->cookies[] = $c;
    }
    
    
    
    /**
     * Adds an header to the response
     * @param string $name
     * @param string $value
     */
    public function addHeader($name, $value){
        $this->responseHeaders[$name] = $value;
    }


    /**
     * Sends the HTTP header to the client
     */
    public function sendHeader(){
        
        
        if (isset($this->renderer)) {
            $code = $this->renderer->getHttp_status_code();
            if (isset($code)) {
                http_response_code($this->renderer->getHttp_status_code());
            } else {
                http_response_code($this->responseCode);
            }
        } else {
            http_response_code($this->responseCode);
        }
        
        
        
        foreach ($this->cookies as $c) {
            $c->send();
        }
        
        //sends the headers
        foreach ($this->responseHeaders as $key => $value) {
            header("$key: $value");
        }
    }
    
    public function sendResponse(){
        
        $this->sendHeader();
        if($this->renderer != NULL){
            $this->renderer->render();
        }
    }
    
    /**
     * 
     * @return PCRenderer
     */
    public function getRenderer() {
        return $this->renderer;
    }
    /**
     * 
     * @param mixed $renderer
     */
    public function setRenderer($renderer) {
        $this->renderer = $renderer;
    }
    /**
     * 
     * @return int
     */
    public function getResponseCode() {
        return $this->responseCode;
    }
    /**
     * 
     * @param int $responseCode
     */
    public function setResponseCode($responseCode) {
        $this->responseCode = $responseCode;
    }


}
