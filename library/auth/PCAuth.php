<?

/**
 *
 * @author paolo
 */
abstract class PCAuth {
    
    const PCAuthUnknownState = 0;
    const PCAuthAllowedState = 1;
    const PCAuthDeniedState = 2;
    
    /**
     * @var int
     */
    protected $auth_state;
    /**
     * @var PCRequest
     */
    protected $request;
    
    
    /**
     *
     * @var PCModelApplication 
     */
    protected $application;

    /**
     *
     * @var string 
     */
    protected $userIdentifier = NULL;


    /**
     * 
     * @param PCRequest $request
     */
    public function __construct($request) {
        $this->request = $request;
        $this->auth_state = static::PCAuthUnknownState;
    }
    
    /**
     * 
     * @return string
     */
    public function getUserIdentifier() {
        return $this->userIdentifier;
    }

        
    /**
     * Returns true if is authorized
     * @return boolean
     */
    public function isAuthorized(){
        
        return $this->auth_state == static::PCAuthAllowedState;
    }
    
    /**
     * @return PCModelApplication
     */
    public function getApplication(){
        return $this->application;
    }
    
    /**
     * restituisce l' hash per la stringa passata come argomento
     * @param string $string
     * @return string 
     */
    public static function computeHashForString($string){
        return hash("sha512", $string);
    }
    
    public abstract function authorize();
    
    /**
     * @return bool
     */
    public abstract function authorizeLogin();
    public abstract function logout();
    
}
