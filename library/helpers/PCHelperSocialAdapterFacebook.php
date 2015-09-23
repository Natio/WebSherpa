<?

/**
 * Description of PCHelperSocialAdapterFacebook
 *
 * @author paolo
 */
class PCHelperSocialAdapterFacebook implements PCHelperSocialAdapter {
    /**
     * @var Facebook
     */
    private $facebook;
    
    /**
     *
     * @var array 
     */
    private $user_info;
    
    /**
     *
     * @var string
     */
    private $username;

    public function __construct($facebook, $user_info, $username = NULL) {
        if (!isset($facebook)) throw new PCException("internal inconsistency, facebook must not be null", 500);
        if (!isset($user_info)) throw new PCException("internal inconsistency, user_info must not be null", 500);
        $this->facebook = $facebook;
        $this->user_info = $user_info;
        $this->username = $username;
    }

    /**
     * Aggiunge i parametri oauth all'utente
     * @param PCModelUserOauth $user
     */
    public function addOauthInfoToUser($user) {
        $user->setOauthStore(array('oauth_token'=>  $this->getTokenValue()));
    }

    public function getSecretValue() {
        return "";
    }

    public function getServiceType() {
        return PCModelUser::$TYPE_FACEBOOK;
    }

    public function getServiceUserIdentifier() {
        return $this->user_info['id'];
    }

    public function getTokenValue() {
        return $this->facebook->getAccessToken();
    }

    public function getValuesForCreatingUser() {
        $date = new DateTime('now',new DateTimeZone('UTC'));
        
        $values = array(
            "username" => $this->username,
            "name" => $this->user_info['first_name'],
            "surname" => $this->user_info['last_name'],
            "email" => $this->user_info['email'],
            "password" => "",
            'creation_date' => $date->format('Y-m-d H:i:s'),
            'penalities' => '0',
            'account_type' => $this->getServiceType()
            
        );
        return $values;
    }   
    
    /**
     *  * Posta sulla timeline di Facebook (se possibile)
     * @param array $reviewDescription
     * @param PCModelWebsite $onSite
     * @param PCModelUserOauth $user
     * @return boolean
     */
     public static function postReviewToFacebook($reviewDescription, $onSite, $user){
        PCAutoloader::importLibrary('facebook');
        
        $oauth = $user->getOauthStore();
        if($oauth == null){
           
            return FALSE;
        }
       
        $domain = $onSite->getDomain();
        $usa = $reviewDescription['usability'];
        $rel = $reviewDescription['reliability'];
        $cont = $reviewDescription['contents'];
        $vote = sprintf("%.1f",(($usa+$rel+$cont)/3.0));
        $text = "I've just reviewed $domain (Vote: $vote) using http://websherpa.me : ".$reviewDescription['comment'];
        
        $facebook = new Facebook(array(
            "appId" => FB_APP_ID,
            "secret" => FB_APP_SECRET,
            "cookie" => true
        ));
        
        $facebook->setAccessToken($oauth['oauth_token']);
        
        try {
            $result = $facebook->api("/me/feed", 'post', array(
                'message' => $text,
                'name' => 'WebSherpa',
                'link' =>  "http://websherpa.me/sites/site?id=".$onSite->getIdentifier(),
                'picture' => 'http://websherpa.me/public/fresh/img/logo_footer.png',
            ));
           
            return isset($result['id']);
            
        } catch (FacebookApiException $exc) {
            c_dump($exc);
            return FALSE;
        }
        return FALSE;
    }

    public function getServiceName() {
        return "Facebook";
    }

    public function hasUsername() {
        return isset($this->username);
    }
}