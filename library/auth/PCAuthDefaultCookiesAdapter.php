<?php

/**
 * Description of PCAuthDefaultCookiesAdapter
 *
 * @author paolo
 */




class PCAuthDefaultCookiesAdapter implements PCAuthCookiesAdapter {
    
     /**
     * The user identifier
     * @var string 
     */
    protected $user_id = NULL;
    
   
    
    

            /**
     * 
     * @param array $cokies
     * @param PCModelApplication $application
     * @return boolean
     */
    public function autorizeWithCookies($cokies, $application, &$user_reference) {

        if (isset($_SESSION['user'])) {
            if(!isset($cokies["presence_c"]) || !isset($cokies["user"])){
                unset($_SESSION['user']);
                return FALSE;
            }
            $user_identifier = $_SESSION['user'];
            PCModelUser::setCurrentUserID($user_identifier);
            $this->user_id = $user_identifier;
            $user_reference = $user_identifier;
            return TRUE;
        } 
        else if (isset($cokies["presence_c"]) && isset($cokies["user"])) {

            $presence = $cokies["presence_c"];
            $user = $cokies["user"];
            
            $token = PCModelManager::fetchModelObjectInstances(PCModelToken::getMapper(), array("user_id" => $user ,"app_id" => $application->getAppId()), NULL, TRUE);
            
            
            
            $count = count($token);
                    
            if ($count > 0 ) {
                $aToken = $token[0];
                if(strcmp($aToken->getTokenStringValue(), $presence) == 0) {
                    $_SESSION['user'] = $user;
                    $this->user_id = $user;
                    $user_reference = $user;
                    PCModelUser::setCurrentUserID($user);
                    return TRUE;
                }
                
            }
            $response = PCResponse::currentResponse();
            $response->addCookie(PCResponseCookie::expiredCookie("user"));
            $response->addCookie(PCResponseCookie::expiredCookie("presence_c"));
        }
        
        return FALSE;
    }

    

    /**
     * 
     * @param PCRequest $request
     * @param PCModelApplication $application
     * @return bool
     */
    public function doLogin($request, $application) {
        
        $param = $request->getParams();
        
        $userName =  $param['uname'];
        $pwd = $param['pwd'];
        
        $keys = array('username' => $userName, "account_type" => PCModelUser::$TYPE_DEFAULT);
        
        $user_array = PCModelManager::fetchModelObjectInstances(PCModelUser::getMapper(), $keys, NULL, TRUE);
        $user = $user_array[0];
        
        if(isset($user) && ( strcmp($pwd, $user->getPassword()) == 0)){

            $secret = $application->getAppSecret();
            $appId = $application->getIdentifier();
            
            $time = time();
            
            $cookieValue = PCAuth::computeHashForString($userName.$time.$secret);
            
            $distantFuture = PCResponseCookie::getDistantFuture();
            
                       
            if( PCMapperToken::setTokenForUserWithIdentifier($user->getIdentifier(), $appId, $cookieValue, $distantFuture)){
                
                $_SESSION['user'] = $this->user_id = $user->getIdentifier();
                
                $presence_cookie  = PCResponseCookie::lifetimeCookie("presence_c", $cookieValue);
                //setcookie("presence_c", $cookieValue, $expirationTime,"/");
                $user_cookie = PCResponseCookie::lifetimeCookie("user", $user->getIdentifier());
                //setcookie("user",$user->getIdentifier(), $expirationTime,"/"); 
                $response = PCResponse::currentResponse();
                $response->addCookie($presence_cookie);
                $response->addCookie($user_cookie);
                
            }
            else{
                return FALSE;
            }

            
            
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     * @param PCModelApplication $application
     */
    public function doLogout($application) {

        session_destroy();
        $response = PCResponse::currentResponse();
        $response->addCookie(PCResponseCookie::expiredCookie("user"));
        $response->addCookie(PCResponseCookie::expiredCookie("presence_c"));
       
        PCMapperToken::removeTokenForUser($this->user_id, $application->getAppId());
        
        
    }
    

    
}

