<?
/**
 * Description of PCMapperRepass
 *
 * @author paolo
 */
class PCMapperRepass extends PCMapper {
    public function getMappedInstance($attributes) {
        $id = $attributes['identifier'];
        $user = $attributes['user_id'];
        $hash = $attributes['request_hash'];
        $date = $attributes['expiration_date'];
        return new PCModelRepass($id, $user, $date, $hash);
    }

    public function getRequiredAttributes() {
        return array("identifier","user_id","request_hash","expiration_date");
    }

    public function getTableName() {
        return "password_request_tbl";
    } 
    
    /**
     * 
     * @param string $email
     * @param PCModelUser $userValue
     * @param string $error
     * @return string|FALSE
     */
    public static function createRepassRequest($email, &$userValue, &$error){
        if(PCMapperUser::validateMail($email) == FALSE){
            $error = "Please insert a valid email1";
            return FALSE;
        }
        
        $users = PCModelManager::fetchModelObjectInstances(PCModelUser::getMapper(), array('email' => $email), NULL, TRUE);
        if(count($users) == 0){
            $error = "Please insert a valid email";
            return FALSE;
        }
        $user = $users[0];
        $userValue = $user;
        
        
        $token = PCModelToken::generateToken();
        
        $expirationDate = new DateTime("now",new DateTimeZone('UTC'));
        $expirationDate->add(new DateInterval("PT20M"));
        $expiration_mysql_format = $expirationDate->format('Y-m-d H:i:s');
                
        $values = array('expiration_date' => $expiration_mysql_format, "user_id" => $user->getIdentifier(), 'request_hash' => $token);
        
        $result = PCModelManager::insertObject(PCModelRepass::getMapper(), $values, array('expiration_date'));
        
        if($result === FALSE){
            $error = "Please insert a valid email";
            return FALSE;
        }
        
        
        return $token;
    }
    
     /**
     * Crea una nuova password(aggiorna il db) e la restituisce. restituisce false in caso negativo
     * @param PCModelUser $user_id l' id dell' utente
     * @param string $hash l' hash inviato dall'utente
     * @param PCModelUser
     * @return boolean|string
     */
    public static function handleRepassRequest($user_id, $hash, &$user_to_ret) {
        $keys = array('request_hash'=>$hash, 'user_id'=>$user_id);
        $items = PCModelManager::fetchModelObjectInstances(PCModelRepass::getMapper(), $keys, NULL, TRUE);
        if (count($items) <= 0) {
            return FALSE;
        }

        $item = $items[0];

        if ($item == NULL || $item->isExpired()) {
            c_dump("SCADUTA");
            return FALSE;
        }

        $bindigngs = array(":h" => $hash, ":user"=> $user_id);
        
        PCModelManager::deleteObject(PCModelRepass::getMapper(), "request_hash = :h AND user_id = :user", $bindigngs);
        
        $newPwd = PCMapperRepass::rand_password(8); 
        

        $model_user = PCModelManager::fetchObjectWithIdentifier(PCModelUser::getMapper(), $item->getUser_id(), NULL, TRUE);
        
        
        if($model_user == NULL){
            $id = $item->getUser_id();
            error_log("User non presente (user_id: $id )");
            return FALSE;
        }
        
        $newPwdHash = PCAuth::computeHashForString($newPwd);
        
        if(PCMapperUser::changePasswordForUser($model_user, $newPwdHash) == FALSE){
            return FALSE;
        }
        $user_to_ret = $model_user;
        return $newPwd;
        
    }
    
    public static function rand_password($length) {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }
}

