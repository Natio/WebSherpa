<?
/**
 * Description of PCRedirectionException
 *
 * @author paolo
 */
class PCExceptionRedirection extends PCException{
    /**
     * @var string 
     */
    private $location;
    public function __construct($location) {
        $this->location = $location;
    }
    
    public function redirect(){
        
        $response = PCResponse::currentResponse();
        header("location: $this->location");
        //$response->addHeader("location", $this->location);
        $response->setResponseCode(http_response_code());
        $response->sendHeader();
    }
}