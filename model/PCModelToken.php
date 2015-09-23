<?

/**
 * Description of PCModelToken
 *
 * @author paolo
 */
class PCModelToken extends PCModelObject{
    /**
     * Il token vero e proprio
     * @var string 
     */
    private $string_value;

    /**
     * La data di scadenza del token
     * @var DateTime 
     */
    private $expiration;

    /**
     * L' id dell' utente propietario del token
     * @var string 
     */
    private $user;

    /**
     * L'applicazione a cui è associato il token
     * @var PCModelApplication 
     */
    private $application;
    
    function __construct($string_value, $expiration, $user, $application) {
        parent::__construct(NULL);
        $this->string_value = $string_value;
        $this->user = $user;
        $this->application = $application;

        if ($expiration instanceof DateTime) {
            $this->expiration = $expiration;
        } else {
            $this->expiration = new DateTime($expiration, new DateTimeZone('UTC'));
        }
        
    }

    /**
     * 
     * @return string
     */
    public function getTokenStringValue() {
        return $this->string_value;
    }

    /**
     * 
     * @return DateTime
     */
    public function getExpiration() {
        return $this->expiration;
    }

    /**
     * 
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * 
     * @return PCModelApplication
     */
    public function getApplication() {
        return $this->application;
    }


    /**
     * Restituisce un token generato casualmente
     * @return string
     */
    
    public static function generateToken(){
        return bin2hex(openssl_random_pseudo_bytes(25));
    }

    public static function getMapper() {
        static $mapper = null;
        if($mapper == null){
            $mapper = new PCMapperToken();
        }
        return $mapper;
    }
    
}