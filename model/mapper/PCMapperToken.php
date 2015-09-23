<?
/**
 * Description of PCMapperToken
 *
 * @author paolo
 */
class PCMapperToken extends PCMapper{
    
    public function getMappedInstance($attributes) {
        $app_id = $attributes['app_id'];
        $secret = $attributes['app_secret'];
        $token = $attributes['token_string'];
        $expiration = $attributes['token_expiration'];
        $user = $attributes['user_id'];
        
        return new PCModelToken( $token, $expiration, $user, new PCModelApplication($app_id, $secret));
    }

    public function getRequiredAttributes() {
        return array("app_id", "app_secret", "token_string", "token_expiration", "user_id");
    }

    public function getTableName() {
        return "view_app_tokens";
    }
    
    public function getTableForInsertUpdate(){
        return "token_tbl";
    }
    
    
    /**
     * 
     * @param string $identifier
     * @param string $application
     * @param string $token
     * @param DateTime $date
     */
    
    public static function setTokenForUserWithIdentifier($identifier, $application, $token, $date){
        
        $values = array();
        $values['app_identifier'] = $application;
        $values['user_identifier'] = $identifier;
        $values['token_string'] = $token;
        $values['token_expiration'] = $date->format("Y-m-d H:i:s");

        $onUpdate = array('token_string','token_expiration');
        
        return PCModelManager::insertObject(PCModelToken::getMapper(), $values, $onUpdate);
                
    }
    
    /**
     * Elimina il token relativo ad un certo utente e applicazione
     * @param string $user_id l'id dell' utente
     * @param string $app_id l'id dell' app
     * @return boolean
     */
    
    public static function removeTokenForUser($user_id, $app_id){
        
        $conditions = "user_identifier = :user AND 	app_identifier = :app";
        $bindings = array(":user" => $user_id, ":app"=> $app_id );
        
        return PCModelManager::deleteObject(PCModelToken::getMapper(), $conditions, $bindings);
    }
}

