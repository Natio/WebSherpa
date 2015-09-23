<?

/**
 * Description of PCRouterSubroute
 *
 * @author paolo
 */
class PCRouterSubroute {
     
    
    /**
     * The controller action
     * @var string
     */
    
    private $controllerAction;
    /**
     * The HTTP method
     * @var string 
     */
    private $httpMethod;
    
    /**
     *
     * @var string 
     */
    private $name;
    
    function __construct($name, $controllerAction, $httpMethod = PCRequest::HTTP_METHOD_GET) {
        
        $this->name = $name;
        $this->controllerAction = $controllerAction;
        $this->httpMethod = $httpMethod;
    }
    
  

    public function getControllerAction() {
        return $this->controllerAction;
    }

    public function getHttpMethod() {
        return $this->httpMethod;
    }


    public function getName(){
        return $this->name;
    }

}

