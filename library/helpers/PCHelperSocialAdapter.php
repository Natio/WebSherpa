<?

interface PCHelperSocialAdapter {
    
    /**
     * Restituisce il tipo del servizio
     * @return int 
     */
    public function getServiceType();
    
    /**
     * Restituisce l'identificativo dell'utente nello specifico servizio
     */
    public function getServiceUserIdentifier();
    
    /**
     * 
     * @param PCModelUserOauth $user
     */
    public function addOauthInfoToUser($user);
    
    /**
     * @return array
     */
    public function getValuesForCreatingUser();
    
    public function getTokenValue();
    public function getSecretValue();
    
    /**
     * @return string
     */
    public function getServiceName();
    
    /**
     * @return boolean
     */
    public function hasUsername();
    
}

