<?php

class PCResponseCookie{
    
    /**
     *
     * @var string 
     */
    private $value;
    
    /**
     *
     * @var string
     */
    private $domainName;
    /**
     *
     * @var DateTime
     */
    private $expirationDate;
    /**
     *
     * @var string
     */
    private $name;
    
    /**
     *
     * @var bool 
     */
    private $httpOnly;
    
    /**
     *
     * @var bool 
     */
    private $secure;
    
    /**
     *
     * @var string
     */
    private $path;
    
    /**
     *
     * @var DateTime 
     */
    private static $distantFuture;


    /**
     * 
     * @param string $name
     * @param string $value
     * @param string $domainName
     * @param DateTime $expirationDate
     */
    function __construct( $name, $value, $domainName = null, $expirationDate = null) {
        $this->value = $value;
      
        
        $this->domainName = $domainName;
        
        if($expirationDate == NULL){
            $expirationDate = new DateTime();
            $expirationDate->setTimestamp(time() - 70000);
            $this->expirationDate =  $expirationDate;
        }
        else{
            $this->expirationDate =  $expirationDate;
        }
        
        
        $this->name = $name;
        $this->httpOnly = FALSE;
        $this->path = "/";
        $this->secure = FALSE;
    }
    
    /**
     * 
     * @return bool
     */
    public function getSecure() {
        return $this->secure;
    }

    /**
     * 
     * @param bool $secure
     */
    public function setSecure($secure) {
        $this->secure = $secure;
    }

    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }

        
    /**
     * 
     * @return bool
     */
    public function getHttpOnly() {
        return $this->httpOnly;
    }
    /**
     * 
     * @param bool $httpOnly
     */
    public function setHttpOnly($httpOnly) {
        $this->httpOnly = $httpOnly;
    }

        
    /**
     * return the cookie value
     * @return string
     */
    public function getValue() {
        return $this->value;
    }
    /**
     * returns the cookie domain
     * @return string
     */
    public function getDomainName() {
        return $this->domainName;
    }
    /**
     * return the expiration date
     * @return DateTime
     */
    public function getExpirationDate() {
        return $this->expirationDate;
    }
    /**
     * returns the cookie name
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Returns a cookie that expires from 10 years
     * @param string $name
     * @param string $value
     * @param string $domainName
     * @return PCResponseCookie
     */
    public static function lifetimeCookie($name, $value, $domainName = null){
        $date = static::getDistantFuture();
        return new PCResponseCookie($name, $value, $domainName ,$date);
    }
    
    /**
     * An expired cookie
     * @param string $name
     * @return PCResponseCookie
     */
    public static function expiredCookie($name){
        
        return new PCResponseCookie($name, "", NULL, NULL);
    }
   

    /**
     * returns the distant future Date
     * @return DateTime
     */
    public static function getDistantFuture() {
        if (static::$distantFuture == NULL) {
            //Aggiungo 25 anni alla data attuale
            static::$distantFuture = new DateTime("@".  (time() + 315360000), new DateTimeZone('UTC'));
            
        }
        return static::$distantFuture;
    }
    
    /**
     * Sends the cookie in the http header 
     * N.B. non è reversibile
     * @return bool If output exists prior to calling this function, send() will fail and return FALSE. If send() successfully runs, it will return TRUE. This does not indicate whether the user accepted the cookie
     */
    public function send(){
       return setcookie($this->name, $this->value, $this->expirationDate->getTimestamp(), $this->path, $this->domainName, $this->secure, $this->httpOnly);
    }
}
