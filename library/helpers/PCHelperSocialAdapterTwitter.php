<?


class PCHelperSocialAdapterTwitter implements PCHelperSocialAdapter{
    
    /**
     * @var array
     */
    private $twitter;
    
    /**
     * @var array
     */
    private $user_info;
    
    /**
     *
     * @var string
     */
    private $username;
    
    /**
     *
     * @var string
     */
    private $email;


    public function __construct($twitter, $user_info, $username = NULL, $email = NULL) {
        if (!isset($twitter)) throw new PCException("internal inconsistency, twitter must not be null", 500);
        if (!isset($user_info)) throw new PCException("internal inconsistency, user_info must not be null", 500);
        $this->twitter = $twitter;
        $this->user_info = $user_info;
        $this->username = $username;
        $this->email = $email;
    }
    
    /** @param PCModelUserOauth $user */
    public function addOauthInfoToUser($user) {
        $bindings = array(
                "oauth_token" => $this->getTokenValue(),
                "oauth_secret" => $this->getSecretValue(),
            );
        $user->setOauthStore($bindings);
    }

    public function getSecretValue() {
        return $this->twitter['oauth_token_secret'];
    }

    public function getServiceType() {
        return PCModelUser::$TYPE_TWITTER;
    }

    public function getServiceUserIdentifier() {
        return $this->user_info->id;
    }

    public function getTokenValue() {
        return $this->twitter['oauth_token'];
    }

    public function getValuesForCreatingUser() {
        list($name, $surname) = explode(" ", $this->user_info->name,2);
        if(!isset($surname)) $surname = $this->user_info->name;
        $date = new DateTime('now',new DateTimeZone('UTC'));
        return  array(
            "username" => $this->username,
            "name" => $name,
            "surname" => $surname,
            "email" => $this->email,
            "password" => "",
            'creation_date' => $date->format('Y-m-d H:i:s'),
            'penalities' => '0',
            'account_type' => $this->getServiceType()
            
        );
       
    }   
    
    /**
     * Posta sulla timeline di twitter
     * @param array $reviewDescription
     * @param PCModelWebsite $onSite
     * @param PCModelUserOauth $user
     * @return boolean
     */
    public static function postReviewToTwitter($reviewDescription, $onSite ,$user){
        PCAutoloader::importLibrary('twitter');
        
        $oauth = $user->getOauthStore();
        if($oauth == null) return FALSE;
        
        $connection = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET, $oauth['oauth_token'], $oauth['oauth_secret']);
        $domain = $onSite->getDomain();
        $usa = $reviewDescription['usability'];
        $rel = $reviewDescription['reliability'];
         $cont = $reviewDescription['contents'];
         $vote = sprintf("$.1f",(($usa+$rel+$cont)/3.0));;
        $text = "I've just reviewed http://$domain (Vote: $vote) using @WebSherpa_me http://websherpa.me/sites/site?id=".$onSite->getIdentifier();
        
        $status = $connection->post('statuses/update', array('status' => $text));
        if(isset($status->errors)) return FALSE;
        return TRUE;
    }

    public function getServiceName() {
        return "Twitter";
    }

    public function hasUsername() {
        return isset($this->username);
    }
}

