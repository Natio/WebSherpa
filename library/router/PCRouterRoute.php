<?

/**
 * Description of PCRouterRoute
 *
 * @author paolo
 */
class PCRouterRoute {
    /**
     *
     * @var string 
     */
    private $controllerPath;
    
    /**
     *
     * @var string 
     */
    private $name;
    
    /**
     *
     * @var string 
     */
    private $className;
    
    /**
     * an associative array of subroutes
     * @var array
     */
    private $subroutes;
    
    /**
     *
     * @var string
     */
    private $authHandlerClassName;
    
    /**
     * The type of the request, is different from the 'domain' of the request. It's usefou for creating a request on a different domain
     * @var string
     */
    private $type;
            
    /**
     * 
     * @param string $controllerPath
     * @param string $name
     * @param string $className
     * @param string $auth
     */
    function __construct( $controllerPath,  $name, $className, $auth = NULL, $type = NULL) {
        $this->controllerPath = $controllerPath;
        $this->name = $name;
        $this->className = $className;
        $this->subroutes = array();
        $this->authHandlerClassName = ($auth == NULL) ?  'PCAuthCookies' : $auth;
        $this->type = ($type == NULL) ? PCRequest::TYPE_WEB : $type;
    }
    
    /**
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * 
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

        
    /**
     * @return PCAuth
     */
    public function getAuthHandlerClassName() {
        
        return $this->authHandlerClassName;
    }

        
    /**
     * 
     * @param PCRouterSubroute $subroute
     */
    public function addSubroute($subroute){
        $this->subroutes[$subroute->getName()] = $subroute;
    }
    
    /**
     * 
     * @param string $subrouteName
     * @return PCRouterSubroute
     */
    public function getSubroute($subrouteName){
        return $this->subroutes[$subrouteName];
    }
        
    /**
     * 
     * @return string
     */
    public function getClassName() {
        return $this->className;
    }

        /**
     * 
     * @return string
     */
    public function getControllerPath() {
        return $this->controllerPath;
    }

    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
}