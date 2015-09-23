<?
/**
 * Description of PCMapperUser
 *
 * @author paolo
 */
class PCMapperUser extends PCMapper {

    public function getMappedInstance($attributes) {
        $identifier = $attributes['identifier'];
        $username = $attributes['username'];
        $password = $attributes['password'];
        $email = $attributes['email'];
        $name = $attributes['name'];
        $surname = $attributes['surname'];
        $creation_date = $attributes['creation_date'];
        $penalties = $attributes['penalities'];

        if ($attributes['account_type'] == PCModelUser::$TYPE_DEFAULT) {
            return new PCModelUser($identifier, $username, $email, $name, $surname, $password, $creation_date, $penalties);
        } 
        
        return new PCModelUserOauth($identifier, $username, $email, $name, $surname, $password, $creation_date, $penalties, $attributes['account_type']);
        
    }

    public function getRequiredAttributes() {
         
        return array("identifier", "name", "surname", "username", "email", "password", "creation_date", "penalities",'account_type');
    }

    public function getTableName() {
        return "users_tbl";
    }
    
    
    

    /**
     * Canghes the password only if the 
     * @param PCModelUser $user
     * @param string $newPwdHash
     * @return bool
     */
    public static function changePasswordForUser($user, $newPwdHash, $oldPwsHash) {
        $mapper = PCModelUser::getMapper();
        $keys = array('password' => $newPwdHash);
        $conditions = "identifier = :id AND password = :pwd";
        $pwd = $oldPwsHash == NULL ? $user->getPassword() : $oldPwsHash;
        $bindings = array(":id" => $user->getIdentifier(), ":pwd" => $pwd );
        return PCModelManager::updateObject($mapper, $keys, $conditions, $bindings);
    }

    /**
     * Returns true if $username is valid
     * @param string $username
     * @return boolean 
     */
    public static function validateUsername($username) {
        $regex = "/^[a-zA-Z0-9_\-\.]{3,15}$/";
        $len = strlen($username);
        return $len >= 5 && $len <= 20 && preg_match($regex, $username) == 1;
    }
    
    /**
     * XXX evitare utilizzo diretto del database
     * @param array $attributes
     * @param string $error
     * @return boolean
     */
    public static function createUserWithAttributes($attributes, &$error) {
        $username = $attributes['username'];
        $name = $attributes['name'];
        $surname = $attributes['surname'];
        $email = $attributes['email'];
        $password = $attributes['password'];

        if (static::validateName($name) == false) {
            $error = "Invalid name";
            return false;
        }
        if (static::validateSurname($surname) == false) {
            $error = "Invalid surname";
            return false;
        }
        if (static::validateUsername($username) == FALSE) {
            $error = "username is not valid (min 5, max 20 chars)";
            return false;
        }
        if (static::validateMail($email) == FALSE) {
            $error = "email already registered";
            return FALSE;
        }

        $mapper = PCModelUser::getMapper();

        $pdo = PCDatabase::getSharedDatabaseConnection();

        $select = "SELECT username ,email FROM " . $mapper->getTableForInsertUpdate() . " WHERE (username = :uname OR email = :mail) AND account_type = :type;";
        $prepared = $pdo->prepare($select);

        if ($prepared === FALSE) {
            c_dump($prepared->errorInfo());
            return FALSE;
        }

        $result = $prepared->execute(array(':uname' => $username, ':mail' => $email, ':type' => PCModelUser::$TYPE_DEFAULT));

        if ($result === FALSE) {
            ob_start();
            print_r($prepared->errorInfo());
            $prepared->debugDumpParams();
            $contents = ob_get_contents();
            ob_end_clean();
            error_log($contents);
            return FALSE;
        }

        while ($item = $prepared->fetch(PDO::FETCH_ASSOC)) {

            if (strcmp($item['email'], $email) == 0) {
                $error = "email already registered";
                return FALSE;
            } else if (strcmp($item['username'], $username) == 0) {
                $error = "username already registered";
                return FALSE;
            }
        }

        $date = new DateTime('now', new DateTimeZone('UTC'));
        

        $keys = array(
            'creation_date' => $date->format('Y-m-d H:i:s'),
            'username' => $username,
            'penalities' => '0',
            'surname' => $surname,
            'name' => $name,
            'email' => $email,
            'password' => $password
        );



        return PCModelManager::insertObject($mapper, $keys);
    }

    /**
     * Returns true if $mail is valid
     * @param string $mail
     * @return boolean 
     */
    public static function validateMail($mail) {
        return PCHelperValidator::validateEmail($mail);
    }

    public static function validateName($name) {
        return PCHelperValidator::validateName($name);
    }

    public static function validateSurname($surname) {
        return PCHelperValidator::validateSurname($surname);
    }

    public static function validatePassword($password) {
        return PCHelperValidator::validatePassword($password);
    }

}
