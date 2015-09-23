<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCRequest
 *
 * @author paolo
 */



class PCRequest {
    
    const HTTP_METHOD_GET = "GET"; 
    const HTTP_METHOD_POST = "POST";
    const HTTP_METHOD_PUT = "PUT";
    const HTTP_METHOD_TRACE = "TRACE";
    const HTTP_METHOD_DELETE = "DELETE";
    const HTTP_METHOD_CONNECT = "CONNECT";
    const HTTP_METHOD_HEAD = "HEAD";
    const HTTP_METHOD_OPTIONS = "OPTIONS";

    const TYPE_WEB = 'web';
    const TYPE_AJAX = 'ajax';
    const TYPE_API = 'api';

    /**
     *
     * @var the complete path of the request 
     */
    private $completePath;
    
    /**
     * the domain name
     * @var string
     */
    private $domainName;
    
     /**
     * the HTTP method
     * @var string
     */
    private $http_method;
    /**
     * the language
     * @var string 
     */
    private $acceptLanguage;
    /**
     * the encoding
     * @var string 
     */
    private $acceptEncoding;
    /**
     * the user agent
     * @var string 
     */
    private $userAgent;
    
    /**
     * the cookies passed with the request
     * @var array
     */
    private $cookies;
    
    /**
     * GET or POST paramaters
     * @var array
     */
    private $params;
    
    /**
     * an array of path components
     * @var array
     */
    private $pathComponents;
    
    /**
     * a queue of path components
     * @var array 
     */
    private $pathQueue;
    
    /**
     *
     * @var PCAuth
     */
    private $authHandler;
    
    /**
     * The type of the request
     * @var string
     */
    private $requestType;

    /**
     * Se utilizza HTTPS
     * @var bool
     */
    private $isSecure;
    /**
     * the shared PCRequest instance
     * @var PCRequest 
     */
    private static $current = NULL;
    
    private function __construct() {    }

    /**
     * Returns the current request
     * 
     * @return PCRequest
     */
    public static function currentRequest() {
       
        if (static::$current == null) {
            static::$current = static::parseRequestFromServer();
        }
        return static::$current;
    }

    /**
     * Available only if the request have been routed
     * @return string
     */
    public function getRequestType() {
        return $this->requestType;
    }

    /**
     * 
     * @param string $requestType
     */
    public function setRequestType($requestType) {
        if($requestType == PCRequest::TYPE_AJAX || $requestType == PCRequest::TYPE_WEB || PCRequest::TYPE_APY == $requestType){
            $this->requestType = $requestType;
        }
        else{
            throw new Exception("$requestType is not a valid PCRequest type",500);
        }
    }

    /**
     * 
     * @return bool
     */
    public function getIsSecure() {
        return $this->isSecure;
    }

    
    /**
     * 
     * @return PCAuth
     */
    public function getAuthHandler() {
        return $this->authHandler;
    }

    /**
     * 
     * @param PCAuth $authHandler
     */
    public function setAuthHandler($authHandler) {
        $this->authHandler = $authHandler;
    }

        
    /**
     * The request complete path (including GET parameters)
     * @return string
     */
    public function getCompletePath() {
        return $this->completePath;
    }

    /**
     * The domain name
     * @return string
     */
    public function getDomainName() {
        return $this->domainName;
    }
    /**
     * the HTTP method
     * @return string
     */
    public function getHttp_method() {
        return $this->http_method;
    }
    /**
     * The accept language
     * @return string
     */
    public function getAcceptLanguage() {
        return $this->acceptLanguage;
    }
    /**
     * The encoding
     * @return string
     */
    public function getAcceptEncoding() {
        return $this->acceptEncoding;
    }
    /**
     * The user agent
     * @return string
     */
    public function getUserAgent() {
        return $this->userAgent;
    }
    /**
     * an array of cookies
     * @return array
     */
    public function getCookies() {
        return $this->cookies;
    }
    /**
     * The request parameters
     * @return array
     */
    public function getParams() {
        return $this->params;
    }
    /**
     * Path components
     * @return array
     */
    public function getPathComponents() {
        return $this->pathComponents;
    }

    /**
     * dequeues a path component
     * @return string|null
     */
    public function dequeuePathComponent(){
        return array_shift($this->pathQueue);
    }
    
    
    
    
    /**
     * @return PCRequest
     */
    private static function parseRequestFromServer(){
        
       
        $uri = $_SERVER['REQUEST_URI'];
        $method = static::getHTTPMethodWithName($_SERVER['REQUEST_METHOD']);
        
        $request = new PCRequest;
        
        $request->cookies = $_COOKIE;
        $request->acceptLanguage = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : NULL;
        $request->acceptEncoding = $_SERVER['HTTP_ACCEPT_ENCODING'];
        $request->domainName = $_SERVER['HTTP_HOST'];
        $request->completePath = $uri;
        $request->http_method = $method;
        $request->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $request->isSecure = isset($_SERVER['HTTPS']);
        
        if($method == static::HTTP_METHOD_GET){
            $request->params = $_GET;
        }
        else if($method == static::HTTP_METHOD_POST){
            
            $contentType = $_SERVER['CONTENT_TYPE'];
            
            if($contentType == "application/json"){
                $body = file_get_contents("php://input");
                $request->params = json_decode($body, true);
            }
            else{
                $request->params = $_POST;
            }
        }
        
        
        $markPosition = strpos($uri, "?");
        
        if($markPosition !== FALSE){
            
            $uri = substr($uri, 0, $markPosition);
        }
        
        $request->pathComponents = array_values(array_filter( explode("/", $uri) , function($item){

            return strcmp($item, "") != 0;
            
        }));
        
        $request->pathQueue = array();
        
        $count = count($request->pathComponents);
        
        for($i = 0 ; $i < $count ; $i++){
            $request->pathQueue[] = $request->pathComponents[$i];
        }
        
        if($count == 0){
            $request->pathQueue[] = "home";
            $request->pathQueue[] = "home";
            $request->pathComponents[] = "home";
            $request->pathComponents[] = "home";
        }
        
        
        return $request;
       
        
    }

    
    
    private static function getHTTPMethodWithName($name){
        $name = strtoupper($name);
        switch ($name) {
            case "GET" :
                return static::HTTP_METHOD_GET;
                break;
            case "POST" :
                return static::HTTP_METHOD_POST;
                break;
            case "TRACE" :
                return static::HTTP_METHOD_TRACE;
                break;
            case "OPTIONS" :
                return static::HTTP_METHOD_OPTIONS;
                break;
            case "CONNECT" :
                return static::HTTP_METHOD_CONNECT;
                break;
            case "HEAD" :
                return static::HTTP_METHOD_HEAD;
                break;
            case "PUT" :
                return static::HTTP_METHOD_PUT;
                break;
            case "DELETE" :
                return static::HTTP_METHOD_DELETE;
                break;

            default:
                throw new PCException("Method not allowed",405);
                break;
        }
    }
    
    
}

