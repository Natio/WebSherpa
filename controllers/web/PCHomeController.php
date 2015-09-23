<?

class PCHomeController extends PCController {

    /**
     * 
     * @param PCRequest $request
     */
    public function homeAction($request) {
       
        $result = array();
        $result['title'] = "WebSherpa - Home";
        
        $response = PCResponse::currentResponse();
        $renderer = PCRendererHTML::rendererForView("home", $result);
        $response->setRenderer($renderer);
        
        
        return $response;
    }

   /* public function handleRequest($request) {
        return $this->homeAction($request);
    }*/

    public function supportsHTTPMethod($method) {
        if ($method == PCRequest::HTTP_METHOD_GET) {
            return TRUE;
        }
        return parent::supportsHTTPMethod($method);
    }

}