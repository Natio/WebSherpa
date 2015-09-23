<?

class PCMapperUserOauth extends PCMapper {
    
    public function getMappedInstance($attributes) {
        return $attributes;
    }

    public function getRequiredAttributes() {
        return array("identifier", "oauth_provider", "oauth_uid", "oauth_token", "oauth_secret", "user_identifier","oauth_expiration");
    }

    public function getTableName() {
        return "users_oauth_tbl";
    }    
    
    /**
     * 
     * @param int $type il tipo dell'account
     * @param string $identifier l'identificativo dell'utente (nel social network)
     * @param string $token il token oauth
     * @param string $secret il secret oauth
     * @param string $user_identifier l' identificativo nel database alla riga utente
     * @return bool 
     */
    public static function insertUserOauth($type, $identifier, $token, $secret, $user_identifier){
        $bindings = array(
            "oauth_provider" => $type,
            "oauth_uid" => $identifier,
            "oauth_token" => $token,
            "oauth_secret" => $secret,
            "user_identifier" => $user_identifier
        );
        $mapper = PCModelUserOauth::getMapper();
        return PCModelManager::insertObject($mapper, $bindings);
    } 
    
    /**
     * @param PCHelperSocialAdapter $adapter
     * @return PCModelUserOauth
     */
    public static function createUserForOauthServiceWithAdapter($adapter){
        $values = $adapter->getValuesForCreatingUser();
        $mapper = PCModelUser::getMapper();
        //creo il 'vero' utente nel db
        if (PCModelManager::insertObject($mapper, $values) == FALSE)
            return NULL;
        
        $instances = PCModelManager::fetchModelObjectInstances($mapper, $values);
        if(count($instances) == 0) return NULL;
        $user = $instances[0];
        
        $user_identifier = $user->getIdentifier();
        
        $token = $adapter->getTokenValue();
        $secret = $adapter->getSecretValue();
        $service_u_id = $adapter->getServiceUserIdentifier();
        $service_type = $adapter->getServiceType();
        
        if(PCMapperUserOauth::insertUserOauth($service_type, $service_u_id, $token, $secret, $user_identifier)){
            $adapter->addOauthInfoToUser($user);
            
            if (PCConfigManager::sharedManager()->getBoolValue('NOTIF_ON_REGISTER')) {
                $email = $values['email'];
                $surname = $values['surname'];
                $name = $values['name'];
                $username = $values['username'];
                $serv = $adapter->getServiceName();
                PCHelperNotificationSender::sendPushNotificationToAdmin("User Registered", "uname: $username Name: $name Sur: $surname mail: $email service_id: $service_u_id via: $serv");
            }
            
            return $user;
        }
        return NULL;
    }

    /**
     * 
     * @param PCHelperSocialAdapter $service_adapter
     * @return PCModelUserOauth
     */
    public static function getOauthUserWithIdentifier($service_adapter) {
        $keys = array(
            "oauth_provider" => $service_adapter->getServiceType(),
            "oauth_uid" => $service_adapter->getServiceUserIdentifier()
        );
        $instances = PCModelManager::fetchModelObjectInstances(PCModelUserOauth::getMapper(), $keys);
        if (count($instances) == 0)
            return NULL;

        $result = $instances[0];

        if(isset($result['oauth_uid']) && $result['user_identifier'] == '0') return FALSE;
        
        $user = PCModelManager::fetchObjectWithIdentifier(PCModelUser::getMapper(), $result['user_identifier'], NULL, TRUE);
        if (isset($user)) {
            
            $bindings = array(
                "oauth_token" =>  $service_adapter->getTokenValue(),
                "oauth_secret" => $service_adapter->getSecretValue(),
            );

            if( PCModelManager::updateObject(PCModelUserOauth::getMapper(), $bindings, "identifier = :iddd",array(":iddd"=>$result['identifier'])) === FALSE) return NULL; 
            
            $service_adapter->addOauthInfoToUser($user);
        }
        return $user;
    }
    
    /**
     * 
     * @param string $user_identifier
     * @param int $service il codice servizio (es Facebook o Twitter)
     * @return array
     */
    public static function getOauthConfig($user_identifier, $service ){
        $val = array(
            "user_identifier" => $user_identifier,
            "oauth_provider" => $service
        );

        $inst = PCModelManager::fetchModelObjectInstances(PCModelUserOauth::getMapper(), $val);
        if(count($inst) == 0 ) return NULL;
        return $inst[0];
    }
    
}

