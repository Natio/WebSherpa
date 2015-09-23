<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCModelApplication
 *
 * @author paolo
 */
class PCModelApplication extends PCModelObject {
    
    const WEBSITE_APP_ID = "eorihfurgtrgb547g30485g720458gb20458gb245gb2045g";
   
    /**
     * The app secret
     * @var string
     */
    private $appSecret;

    /**
     * 
     * @return string
     */
    public function getAppId() {
        return parent::getIdentifier();
    }

    /**
     * @return string
     */
    
    public function getAppSecret() {
        return $this->appSecret;
    }
    
    public function __construct($identifier, $secret) {
        parent::__construct($identifier);
        $this->appSecret = $secret;
    }
    
    /**
     * @return PCMapperApplication 
     */
    public static function getMapper() {
        static $mapper = null;
        if($mapper == null){
            $mapper = new PCMapperApplication();
        }
        return $mapper;
    }  
    
}

