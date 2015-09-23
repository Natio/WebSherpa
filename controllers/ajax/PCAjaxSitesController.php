<?

/**
 * Description of PCAjaxSitesController
 *
 * @author paolo
 */
class PCAjaxSitesController extends PCController {

    /**
     * 
     * @param PCRequest $request
     */
    public function getSiteByURLAction($request) {
        $auth = $request->getAuthHandler();
        
        if ($auth->isAuthorized()) {

            $params = $request->getParams();

            $url = $params['url'];
            $parsedUrl = parse_url($url);

            if ($parsedUrl && $parsedUrl['host']) {

                
                $site = PCMapperWebsite::getSiteWithDomain($parsedUrl['host']);
                if ($site != NULL) {
                    
                    if($site->cacheIsExpired()){
                        $site = PCMapperWebsite::recacheSiteReview($site);
                        if(isset($site) == FALSE){
                            return new PCRendererJSON(array("error" => "Something went wrong"), 500);
                        }
                    }
                    
                    $result = array();
                    $result["site"] = $site;
                    $result["last_review"] = PCMapperReview::lastReviewForSite($auth->getUserIdentifier(), $site);
                    return new PCRendererJSON($result);
                    
                    
                }
            }
            
            return new PCRendererJSON(array("error" => "Site not found"), 404);
        }
        throw new PCExceptionAuth("Auth Required", 401);
    }
    
    /**
     * Aggiunge una recensione
     * @param PCRequest $request
     */
    
    public function reportSpamAction($request){
        
        if(! $request->getAuthHandler()->isAuthorized()){
            return new PCRendererJSON(array("error","You must login !"),401);
        }
        
        $params = $request->getParams();
        
        
        
        if(!isset($params['text']) || !isset($params['reviewId'])){
            return new PCRendererJSON(array("error","Missing parameter !"),401);
        }
        
        if(PCMapperReport::createReport(PCModelUser::getCurrentUser()->getIdentifier(), $params['reviewId'], $params['text'])){
            return new PCRendererJSON(array("result","ok"));
        }
        
        
        return new PCRendererJSON(array("error","Missing parameter !"),401);
        
        
    }
    
    /**
     * Aggiunge una recensione
     * @param PCRequest $request
     */
    public function addReviewAction($request){
        
        $auth = $request->getAuthHandler();
        if($auth->isAuthorized() == FALSE){
            throw new PCExceptionAuth("Auth Required", 401);
        }
        
        
        $params = $request->getParams();
      
        $url =  PCHelperInputCleaner::cleanInputString($params['siteUrl']);
        $comment = PCHelperInputCleaner::cleanInputString($params['comment']);
        $contents = PCHelperInputCleaner::cleanInputString($params['contents']);
        $reliability = PCHelperInputCleaner::cleanInputString($params['reliability']);
        $usability = PCHelperInputCleaner::cleanInputString( $params['usability']);
        $category = PCHelperInputCleaner::cleanInputString($params['category']);
        $language = PCHelperInputCleaner::cleanInputString($params['language_code']);
        $siteIdentifier =  PCHelperInputCleaner::cleanInputString($params['site_identifier']);
        
        if((!empty($url) || !empty($siteIdentifier)) && isset($comment) && isset($contents) && isset($reliability) && isset($usability) && isset($category) && isset($language)){
            $error = NULL;
            $user = PCModelUser::getCurrentUser();
            $result = PCMapperWebsite::addSiteWithReview($url, $user, $comment, $usability, $contents, $reliability, $category, $language, $error, $siteIdentifier);
            if($result){
                if(PCConfigManager::sharedManager()->getBoolValue('SOCIAL_POST_ON_REVIEW')){
                    $userName = $user->getUsername();
                    PCHelperNotificationSender::sendPushNotificationToAdmin("Aggiunta Recensione", "User: $userName r($reliability) u($usability) c($contents) url: $url");
                }
                
                return new PCRendererJSON(array("OK"=>"Site Added"));
            }
            else{
                error_log($error);
                return new PCRendererJSON(array("error"=>$error),401);
            }
            
        }
        
        return new PCRendererJSON("Error adding site", 400);
        
    }
    
    
    
    /**
     * Restituisce le recensioni legate ad un sito oppure ad un utente
     * @param PCRequest $request
     */
    public function getSiteReviewsAction($request) {
        $params = $request->getParams();
        
        if( isset($params['offset']) == FALSE) return new PCRendererJSON(array("error" => "missing param 'offset'"), 400);
        
        $offset = $params['offset'];
        
        if( isset($params['site_id'])){
            $site_id = $params['site_id'];
            
            $result = array();
            
            $reviews = PCMapperReview::getReviewsWithSiteIdentifier($site_id, $offset);
           
            foreach ($reviews as $r) {
                $tmp = array();
                $tmp["vote"] = sprintf("%.1f", $r->getVote());
                //XXX pensare ad un modo più efficente per risolvere gli identificativi
                $user = PCModelManager::fetchObjectWithIdentifier(PCModelUser::getMapper(), $r->getUserIdentifier(), NULL, TRUE);
                $tmp["user"] = $user->getUsername();
                $tmp["date_added"] = $r->getDate_added()->format("Y-m-d");
                $tmp["comment"] = $r->getComment();
                $tmp["reviewId"] = $r->getIdentifier();
                $tmp["userId"] = $user->getIdentifier();
                $tmp['reliability'] = sprintf("%.1f", $r->getReliabilityVote());
                $tmp['contents'] = sprintf("%.1f", $r->getContentsVote());
                $tmp['usability'] = sprintf("%.1f", $r->getUsabilityVote());
                $result[] = $tmp;
            }
            return new PCRendererJSON($result);
        }
        
        if (isset($params['user_id'])) {
            $user_id = $params['user_id'];
            $result = array();

            $user = PCModelManager::fetchObjectWithIdentifier(PCModelUser::getMapper(), $user_id, NULL, TRUE);
            if (!isset($user))
                new PCRendererJSON(array("error" => "wrong user identifier"), 400);

           // $user_name = $user->getUsername();

            $reviews = PCMapperReview::getReviewsWithUserIdentifier($user_id, $offset);
           
            foreach ($reviews as $r) {
                $tmp = array();
                
                //XXX pensare ad un modo più efficente per risolvere gli identificativi
                $site = PCModelManager::fetchObjectWithIdentifier(PCModelWebsite::getMapper(), $r->getSiteIdentifier(), NULL, TRUE);

                $tmp["vote"] = sprintf("%.1f",$r->getVote());
                $tmp["site"] = $site->getDomain();
                $tmp["date_added"] = $r->getDate_added()->format("Y-m-d");
                $tmp["comment"] = $r->getComment();
                $tmp["reviewId"] = $r->getIdentifier();
                $tmp["siteId"] = $site->getIdentifier();
                $tmp['reliability'] = sprintf("%.1f",$r->getReliabilityVote());
                $tmp['contents'] = sprintf("%.1f",$r->getContentsVote());
                $tmp['usability'] = sprintf("%.1f",$r->getUsabilityVote());
                $result[] = $tmp;
            }
            return new PCRendererJSON($result);
        }

        return new PCRendererJSON(array("error" => "missing param 'site_id' or 'user_id"), 400);
    }

    public function supportsHTTPMethod($method) {
        if ($method == PCRequest::HTTP_METHOD_GET || $method == PCRequest::HTTP_METHOD_POST) {
            return TRUE;
        }
        return parent::supportsHTTPMethod($method);
    }

}

