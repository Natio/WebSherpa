<?

/**
 * Description of PCModelUser
 *
 * @author paolo
 */
class PCModelUser extends PCModelObject {
    
    public static $TYPE_DEFAULT = 0;
    public static $TYPE_TWITTER = 1;
    public static $TYPE_FACEBOOK = 2;
    /*
     * @var string
     */

    protected $username;

    /*
     * @var string
     */
    protected $email;
    /*
     * @var string
     */
    protected $name;

    /*
     * @var string
     */
    protected $surname;
    /*
     * @var string
     */
    protected $password;
    /*
     * @var DateTime
     */
    protected $creation_date;
    /*
     * @var string
     */
    protected $penalties;
    
    /**
     * The current user identifier
     * @var string 
     */
    private static $current_user_identifier = NULL;
    
    protected $account_type;
    
    /**
     * the current user instance
     * @var PCModelUser
     */
    private static $current_user = NULL;

    function __construct($identifier, $username,  $email, $name, $surname, $password, $creation_date, $penalties, $account_type = null ) {
        parent::__construct($identifier);
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->password = $password;
        $this->account_type = $account_type;
        if ($creation_date instanceof DateTime) {
            $this->creation_date = $creation_date;
        } else {
            $this->creation_date = new DateTime($creation_date, new DateTimeZone('UTC'));
        }
        $this->penalties = $penalties;
    }
    
    /**
     * 
     * @return int
     */
    public function getAccountType(){
        return static::$TYPE_DEFAULT;
    }
    
   

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return DateTime
     */
    public function getCreation_date() {
        return $this->creation_date;
    }

    /**
     * @return float
     */
    public function getPenalties() {
        return $this->penalties;
    }

    public function jsonSerialize() {
        $arr = parent::jsonSerialize();
        $arr['name'] = $this->name;
        $arr['username'] = $this->username;
        //$arr['email'] = $this->email;
        $arr['surname'] = $this->surname;
        $arr['creation_date'] = $this->creation_date->getTimestamp();
        $arr['penalties'] = $this->penalties;
        return $arr;
    }

    public static function getMapper() {
        
        static $mapper = null;
        if($mapper == null){
            $mapper = new PCMapperUser();
        }
        return $mapper;
    }

    
    
    /**
     * Restituisce l'utente connesso attualmente (se disponibile)
     * @return ModelUser 
     */
    public static function getCurrentUser(){
        if(static::$current_user != NULL) return static::$current_user;
        

        if(isset(static::$current_user_identifier)){
            $user = PCModelManager::fetchObjectWithIdentifier(PCModelUser::getMapper(), static::$current_user_identifier, NULL, TRUE);
            
            static::$current_user = $user;
            
            
            return $user;
        }
        
        return NULL;
    }
    /**
     * Imposta l'utente connesso attualmente (se disponibile)
     * @param string $identifier 
     */
    public static function setCurrentUserID($identifier){
        if(static::$current_user != null) return;
        static::$current_user_identifier = $identifier;
    }
    
    /**
     * Imposta l'utente connesso attualmente (se disponibile)
     * @param ModelUser $identifier  
     */
    public static function setCurrentUser($user){
        if(static::$current_user != null) return;
        static::$current_user = $user;
    }
}

