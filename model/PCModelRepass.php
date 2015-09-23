<?

/**
 * Description of PCModelRepass
 *
 * @author paolo
 */
class PCModelRepass extends PCModelObject {
    
    /**
     * L' id dell'utente
     * @var string 
     */
    private $user_id;

    /**
     * La data di scadenza della richiesta
     * @var DateTime
     */
    private $expiration_date;

    /**
     * L'hash della richiesta
     * @var string 
     */
    private $request_hash;

    /**
     * Indice de la richiesta è stata eliminata dal database
     * @var boolean 
     */
    private $deleted = FALSE;
    
    
    function __construct($identifier,$user_id, $expiration_date, $request_hash) {
        parent::__construct($identifier);
        
        $this->user_id = $user_id;
         
        $this->request_hash = $request_hash;
        
        if ($expiration instanceof DateTime) {
            $this->expiration_date = $expiration_date;
        } else {
            $this->expiration_date = new DateTime($expiration_date, new DateTimeZone('UTC'));
        }
    }

    /**
     * Se la richiesta è scaduta
     * @return boolean
     */
    public function isExpired() {
        return $this->expiration_date < new DateTime();
    }

    /**
     * 
     * @return string
     */
    public function getUser_id() {
        return $this->user_id;
    }

    /**
     * 
     * @return DateTime
     */
    public function getExpiration_date() {
        return $this->expiration_date;
    }

    /**
     * 
     * @return string
     */
    public function getRequest_hash() {
        return $this->request_hash;
    }

    /**
     * 
     * @return boolean
     */
    public function isDeleted() {
        return $this->deleted;
    }
    
    
    public static function getMapper() {
        static $mapper = null;
        if($mapper == null){
            $mapper = new PCMapperRepass();
        }
        return $mapper;
    }    
}

