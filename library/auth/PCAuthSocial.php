<?

class PCAuthSocial extends PCAuthCookies{
    
    
    
    public function __construct($request) {
        $this->request = $request;
        $this->application = PCModelManager::fetchObjectWithIdentifier(PCModelApplication::getMapper(), PCModelApplication::WEBSITE_APP_ID, NULL, TRUE);
        $this->setupSession();
    }

    
    public function authorize() {
        return FALSE;
    }

   
    public function authorizeLogin() {
        
        return FALSE;
    }
    
    /**
     * Setta i cookie dell'utente
     * @param PCModelUserOauth $user
     * @return boolean
     */
    private function authorizeUser($user) {
        if(isset($user) === FALSE) return FALSE;
        $_SESSION['user'] = $user->getIdentifier();

        $secret = $this->application->getAppSecret();
        $appId = $this->application->getIdentifier();
        $time = time();
        $cookieValue = PCAuth::computeHashForString($user->getUsername() . $time . $secret);
        $distantFuture = PCResponseCookie::getDistantFuture();

        if (PCMapperToken::setTokenForUserWithIdentifier($user->getIdentifier(), $appId, $cookieValue, $distantFuture)) {

            $_SESSION['user'] = $user->getIdentifier();

            $presence_cookie = PCResponseCookie::lifetimeCookie("presence_c", $cookieValue);
            $user_cookie = PCResponseCookie::lifetimeCookie("user", $user->getIdentifier());

            $response = PCResponse::currentResponse();
            $response->addCookie($presence_cookie);
            $response->addCookie($user_cookie);
            PCModelUser::setCurrentUser($user);
            return TRUE;
        }
        return FALSE;
    }
    
    
    /**
     * 
     * @param PCHelperSocialAdapter $adapter
     * @return boolean
     */
    public function authorizeOauthUser($adapter){
        $user = PCMapperUserOauth::getOauthUserWithIdentifier($adapter);
        if(isset($user) == FALSE && $adapter->hasUsername()){
            $user = PCMapperUserOauth::createUserForOauthServiceWithAdapter($adapter);
        }
        return $this->authorizeUser($user);
    }

    public function logout() {
        throw new PCExceptionAuth("Internal Inconsistency",500);
    }    
}

