<?

/**
 * Description of PCWebUserController
 *
 * @author paolo
 */
class PCWebUserController extends PCController {
    
    public function supportsHTTPMethod($method) {
        if($method == PCRequest::HTTP_METHOD_GET) return TRUE;
        return FALSE;
    }

        /**
     * @param PCRequest $request
     */
    public function profileAction($request) {

        $auth = $request->getAuthHandler();
        $auth->authorize();
         $params = $request->getParams();
        
       

        if (isset($params['id']) && $auth->isAuthorized() && (strcmp($params['id'], $auth->getUserIdentifier()) == 0)) {

            $result = array();
            $user = PCModelUser::getCurrentUser();

            $result['username'] = $user->getUsername();
            $result['name'] = $user->getName();
            $result['surname'] = $user->getSurname();
            $result['member_since'] = $user->getCreation_date()->format("Y-m-d");
            $result['user_id'] = $user->getIdentifier();
            $result['email'] = $user->getEmail();
            $result['title'] = "WebSherpa - " . $user->getUsername();
            return PCRendererHTML::rendererForView('user', $result);
        }


        return null;
    }

}