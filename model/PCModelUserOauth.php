<?
/**
 * Description of PCModelUserTwitter
 *
 * @author paolo
 */
class PCModelUserOauth extends PCModelUser{
    
    private $oauth_store;


    public function getAccountType() {
        return $this->account_type;
    }
    
    /**
     * @return array
     */
    public function getOauthStore() {
        static $loaded  = FALSE;
        if($this->oauth_store == NULL && $loaded == FALSE){
            error_log("AAA");
            $this->oauth_store = PCMapperUserOauth::getOauthConfig($this->getIdentifier(), $this->getAccountType());
            $loaded = TRUE;
        }
        return $this->oauth_store;
    }
    
    /**
     * @param array $oauth_store
     */
    public function setOauthStore($oauth_store) {
        $this->oauth_store = $oauth_store;
    }

    public static function getMapper() {

        static $mapper = null;
        if ($mapper == null) {
            $mapper = new PCMapperUserOauth();
        }
        return $mapper;
    }
    
    /**
     * Posta sulla timeline del servizio (se possibile)
     * @param array $reviewDescription
     * @param PCModelWebsite $onSite
     * @return boolean
     */
    public function postReviewToTimeline($reviewDescription, $onSite) {
        switch ($this->getAccountType()) {
            case PCModelUser::$TYPE_TWITTER:
                return PCHelperSocialAdapterTwitter::postReviewToTwitter($reviewDescription, $onSite, $this);
            case PCModelUser::$TYPE_FACEBOOK:
                return PCHelperSocialAdapterFacebook::postReviewToFacebook($reviewDescription, $onSite, $this);
            default:
                break;
        }

        return FALSE;
    } 

}

