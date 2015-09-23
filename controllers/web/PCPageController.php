<?

/**
 * Description of PCPageController
 *
 * @author paolo
 */
class PCPageController extends PCController {
    
    public function supportsHTTPMethod($method) {
        if ($method == PCRequest::HTTP_METHOD_GET)
            return TRUE;
        return parent::supportsHTTPMethod($method);
    }

    public function aboutAction($request) {
        $result = array("title" => "WebSherpa - About");
        $result["hide_user_box"] = TRUE;
        return PCRendererHTML::rendererForView("about", $result);
    }

    public function welcomeAction($request) {
        $result = array("title" => "WebSherpa - Welcome");
        return PCRendererHTML::rendererForView("welcome", $result);
    }

    public function faqAction($request) {
        $result = array("title" => "WebSherpa - F.A.Q.");
        return PCRendererHTML::rendererForView("faq", $result);
    }
    public function downloadAction($request){
        
        throw new PCExceptionRedirection("https://chrome.google.com/webstore/detail/kcjgnccjjgbpgijkifhginjlmddolgob/");
    }
    public function instructionsAction($request){
        $result = array("title" => "WebSherpa - Instructions");
        return PCRendererHTML::rendererForView("instructions", $result);
    }
    
    public function versionAction($request){
        return new PCRendererJSON(array('version'=>'0.2'));
    }
    
    public function tosAction($request){
        return PCRendererHTML::rendererForView("terms", array("title" => "WebSherpa - Terms of Service"));
    }
    
    
    public function registerPageAction($request){
        return PCRendererHTML::rendererForView("register", array("title" => "WebSherpa - Register"));
    }
    
    public function meAction($r){
        c_dump($_COOKIE);
        //PCResponse::currentResponse()->addCookie(PCResponseCookie::lifetimeCookie("CIAO", "CIAO","ajax.localhost"));
    }

        /**
     * 
     * @param PCRequest $request
     */
    public function repassAction($request){
        $params = $request->getParams();
        $user_id = $params['id'];
        $hash = $params['val'];
        $model_user = NULL;
        
        $result = PCMapperRepass::handleRepassRequest($user_id, $hash, $model_user);
        if($result == FALSE){
            return NULL;
        }
        
        $mail = PCEmailBuilder::buildEmailForPasswordNotification($result, $model_user);
        PCEmailSender::sendMail($mail);
       
       
        $content = array();
        $content['title'] = "WebSherpa";
        $content['pageContent']= "<h1>A new password has been sent to your e-mail address (".$model_user->getEmail().")</h1>";
        return PCRendererHTML::rendererForView('flexiblePage', $content);        
    }
    
    /**
     * 
     * @param PCRequest $request
     * @throws PCExceptionRedirection
     */
    public function logoutAction($request){
        
            $auth = $request->getAuthHandler(); 
            $auth->logout();
           
            throw new PCExceptionRedirection("/");
    }
 
}