<?

class PCSocialController extends PCController {
    
    /**
     * @param PCRequest $request
     */
    public function twitterCallbackAction($request) {
        PCAutoloader::importLibrary("twitter");
        
        $params = $request->getParams();
        
        if (isset($params['reg_username']) && isset($params['reg_email'])) {
            
            if(!isset($_SESSION['access_token'])) throw new PCExceptionRedirection("/page/register");
            
            $access_token = $_SESSION['access_token'];
            
            $connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            $user_info = $connection->get('account/verify_credentials');
            
            
            
            if (PCMapperUser::validateMail($params['reg_email']) == FALSE) {
                $cont = array("title" => "WebSherpa - Insert Username", "text_error" => "Please insert a valid email", "show_email" => TRUE);
                return PCRendererHTML::rendererForView('insertUname', $cont);
            }

            if (PCMapperUser::validateUsername($params['reg_username']) == FALSE) {
                $cont = array("title" => "WebSherpa - Insert Username", "text_error" => "Insert a valid Username; min 6 characters use only characters and numbers and \"_\"", "show_email" => TRUE);
                return PCRendererHTML::rendererForView('insertUname', $cont);
            }

            if (count(PCModelManager::fetchModelObjectInstances(PCModelUser::getMapper(), array("username" => $params['reg_username']))) != 0) {
                $cont = array("title" => "WebSherpa - Insert Username", "text_error" => "Username already used, please choose another username.", "show_email" => TRUE);
                return PCRendererHTML::rendererForView('insertUname', $cont);
            }

            unset($_SESSION['access_token']);

            if (200 == $connection->http_code) {
                $adapter = new PCHelperSocialAdapterTwitter($access_token, $user_info, $params['reg_username'], $params['reg_email']);
                $result = $request->getAuthHandler()->authorizeOauthUser($adapter);
                if($result){
                    throw new PCExceptionRedirection("/");
                }
                throw new PCExceptionRedirection("/page/register");
            }
            else{
                throw new PCExceptionRedirection("/page/register");
            }
            
        } else {
            /* If the oauth_token is old redirect to the connect page. */
            if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
                unset($_SESSION['oauth_token']);
                throw new PCExceptionRedirection("/");
            }
            $connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
            
            /* Request access tokens from twitter */
            $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

            $user_info = $connection->get('account/verify_credentials');

            /* Save the access tokens. Normally these would be saved in a database for future use. */
            $_SESSION['access_token'] = $access_token;

            /* Remove no longer needed request tokens */
            unset($_SESSION['oauth_token']);
            unset($_SESSION['oauth_token_secret']);

            /* If HTTP response is 200 continue otherwise send to connect page to retry */
            if (200 == $connection->http_code) {
                $adapter = new PCHelperSocialAdapterTwitter($access_token, $user_info);
                $result = $request->getAuthHandler()->authorizeOauthUser($adapter);
                if ($result === FALSE) {

                    return PCRendererHTML::rendererForView('insertUname', array("title" => "WebSherpa - Insert Username", "show_email" => TRUE));
                }

                throw new PCExceptionRedirection("/");
            } else {
                /* Save HTTP status for error dialog on connnect page. */
                throw new PCExceptionRedirection("/page/register");
            }
        }
    }

    
    /**
     * @param PCRequest $request
     */
    public function facebookCallbackAction($request) {
        PCAutoloader::importLibrary('facebook');
        $facebook = new Facebook(array(
            "appId" => FB_APP_ID,
            "secret" => FB_APP_SECRET,
            "cookie" => true
        ));
        $params = $request->getParams();
        $user_profile = NULL;
        
        try {
            $user = $facebook->getUser();
            if (isset($user)) {
                $user_profile = $facebook->api('/me');      
            }
        } catch (FacebookApiException $e) {
            c_dump($_GET);
            error_log("AAAA".$e);
            throw new PCExceptionRedirection("/page/register");
        }
        
        if (isset($params['reg_username'])){
            
            if (PCMapperUser::validateUsername($params['reg_username']) == FALSE) {
                $cont = array("title" => "WebSherpa - Insert Username", "text_error" => "Insert a valid Username; min 6 characters use only characters and numbers and \"_\"", "show_email" => TRUE);
                return PCRendererHTML::rendererForView('insertUname', $cont);
            }

            if (count(PCModelManager::fetchModelObjectInstances(PCModelUser::getMapper(), array("username" => $params['reg_username']))) != 0) {
                $cont = array("title" => "WebSherpa - Insert Username", "text_error" => "Username already used, please choose another username.", "show_email" => TRUE);
                return PCRendererHTML::rendererForView('insertUname', $cont);
            }
            
            $adapter = new PCHelperSocialAdapterFacebook($facebook, $user_profile, $params['reg_username']);
            if($request->getAuthHandler()->authorizeOauthUser($adapter)){
                throw new PCExceptionRedirection("/");
            }
            throw new PCExceptionRedirection("/page/register");
        }
        else{
            
            $adapter = new PCHelperSocialAdapterFacebook($facebook, $user_profile);
            if($request->getAuthHandler()->authorizeOauthUser($adapter) === FALSE){
                
                return PCRendererHTML::rendererForView('insertUname', array("title" => "WebSherpa - Insert Username"));
            }
            
            throw new PCExceptionRedirection("/");
        }
        
    }

        /**
     * @param PCRequest $request
     */
    public function facebookLoginAction($request){
        PCAutoloader::importLibrary('facebook');
        
        $facebook = new Facebook(array(
            "appId" => FB_APP_ID,
            "secret" => FB_APP_SECRET,
            "cookie" => true
        ));
        
        $redirectURL = "http://".(PCConfigManager::sharedManager()->getValue('DOMAIN_NAME'))."/social/facebookcallback";
        
        $loginUrl = $facebook->getLoginUrl(array(
		'scope'		=> 'email,publish_actions,offline_access,publish_stream', 
		'redirect_uri'	=> $redirectURL, 
		));
        
        throw new PCExceptionRedirection($loginUrl);
    }


    /**
     * @param PCRequest $request
     */
    public function twitterLoginAction($request){
        PCAutoloader::importLibrary("twitter");
        
        unset($_SESSION['reg_username']);

        $connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET);
        $connection->host = "https://api.twitter.com/1.1/";
        /* Get temporary credentials. */
        $request_token = $connection->getRequestToken(TW_OAUTH_CALLBACK);

        /* Save temporary credentials to session. */
        $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
        /* If last connection failed don't display authorization link. */
        switch ($connection->http_code) {
            case 200:
                /* Build authorize URL and redirect user to Twitter. */
                $url = $connection->getAuthorizeURL($token);
                throw new PCExceptionRedirection($url);
                break;
            default:
                /* Show notification if something went wrong. */
                echo 'Could not connect to Twitter. Refresh the page or try again later. code: '.$connection->http_code;
        }
    }

        public function supportsHTTPMethod($method) {
        if($method == PCRequest::HTTP_METHOD_GET){
            return TRUE;
        }
        return parent::supportsHTTPMethod($method);
    }
}