<?

/**
 * Description of PCWebSitesController
 *
 * @author paolo
 */
class PCWebSitesController extends PCController {
    
    public function supportsHTTPMethod($method) {
        if(PCRequest::HTTP_METHOD_GET == $method) return TRUE;
        return FALSE;
    }
    
    /**
     * 
     * @param PCRequest $request
     */
    public function addSiteAction($request){
        return PCRendererHTML::rendererForView("add_site", array("title"=>"Websherpa - Add website"));
    }


    /**
     * 
     * @param PCRequest $request
     */
    public function searchAction($request) {
        
        
        $param = $request->getParams();
        if (isset($param['host_name'])) {
            $host = $param['host_name'];
            $sites = PCMapperWebsite::searchSiteWithDomainLike($host, 0);
            $sitesStore = array();
            if ($sites !== FALSE) {
                foreach ($sites as $s) {
                    $resultS = array();
                    $resultS['siteCategory'] = PCMapperCategory::nameFromIdentifier($s->getCategory());
                    $resultS['site_id'] = $s->getIdentifier();
                    $resultS['siteHost'] = $s->getUrl();
                    $resultS['dateAdded'] = $s->getDate_added()->format("d-m-y");
                    $sitesStore[] = $resultS;
                }
            }

            $result = array();
            $result['sites'] = $sitesStore;
            $result['title'] = "WebSherpa - Search: " . $host;

            return PCRendererHTML::rendererForView("search", $result);
        }
        
        throw new PCExceptionController("Page not found", 404);
    }
    
    /**
     * 
     * @param PCRequest $request
     */
    public function userAction($request) {
        $param = $request->getParams();
        if (isset($param['id']) == FALSE)
            throw new PCExceptionController("Page not found", 404);

        $user = PCModelManager::fetchObjectWithIdentifier(PCModelUser::getMapper(), $param['id'], NULL, TRUE);

        if (isset($user) == FALSE)
            throw new PCExceptionController("Page not found", 404);


        $result = array();

        $user_identifier = $user->getIdentifier();
        $user_name = $user->getUsername();
        $result['user_id'] = $user_identifier;
        $result['userName'] = $user_name;
        $result['userFrom'] = $user->getCreation_date()->format("Y-m-d H:i");
        
        
        $avgTotReviews = PCMapperReview::getUserAverageAndCount($user_identifier);
        $result['averageVote'] = sprintf("%.1f",$avgTotReviews['avg']);
        $result['votesCount'] = $avgTotReviews['tot'];
        $result['reliability'] = $avgTotReviews['reliability'];
        $result['contents'] = $avgTotReviews['contents'];
        $result['usability'] = $avgTotReviews['usability'];

        $reviewsList = array();
        $reviews = PCMapperReview::getReviewsWithUserIdentifier($user_identifier, 0); 

        foreach ($reviews as $r) {
            $reviewArray = array();

            $site = PCModelManager::fetchObjectWithIdentifier(PCModelWebsite::getMapper(), $r->getSiteIdentifier(), NULL, TRUE);

            $reviewArray["vote"] = sprintf("%.1f",$r->getVote());
            $reviewArray["site"] = $site->getUrl();
            $reviewArray["date_added"] = $r->getDate_added()->format("Y-m-d");
            $reviewArray["comment"] = $r->getComment();
            $reviewArray["siteId"] = $site->getIdentifier();
            $reviewArray['reliability'] = sprintf("%.1f", $r->getReliabilityVote());
            $reviewArray['contents'] = sprintf("%.1f", $r->getContentsVote());
            $reviewArray['usability'] = sprintf("%.1f", $r->getUsabilityVote());
            $reviewsList[] = $reviewArray;
        }

        $result['reviews'] = $reviewsList;
        $result['title'] = "WebSherpa - " . $user_name;
        return PCRendererHTML::rendererForView('host', $result);
    }
    
    /**
     * 
     * @param PCRequest $request
     */
    public function siteAction($request) {
        $param = $request->getParams();

        $hostName = $request->dequeuePathComponent();
        if (isset($hostName)) {
            $site = PCMapperWebsite::getSiteWithDomain($hostName);
            if (isset($site) == FALSE) {
                throw new PCExceptionController("Page not found", 404);
            }
            $identifier = $site->getIdentifier();
            throw new PCExceptionRedirection("/sites/site?id=$identifier");
        }


        if (isset($param['id']) == FALSE)
            throw new PCExceptionController("Page not found", 404);
        /** @value  PCModelWebsite $site */
        $site = PCModelManager::fetchObjectWithIdentifier(PCModelWebsite::getMapper(), $param['id'], NULL, TRUE);

        if (!isset($site)) {
            throw new PCExceptionController("Page not found", 404);
        }

        if ($site->cacheIsExpired()) {
            $site = PCMapperWebsite::recacheSiteReview($site);
            if (isset($site) == FALSE) {
                throw new PCExceptionController('Error caching', 500);
            }
        }

        $result = array();

        $result['siteCategory'] = PCMapperCategory::nameFromIdentifier($site->getCategory());
        $result['site_id'] = $site->getIdentifier();
        $result['siteHost'] = $site->getUrl();

        $result['reliability'] = $site->getReliability();
        $result['contents'] = $site->getContents();
        $result['usability'] = $site->getUsability();

        $result['averageVote'] = $site->getVote();
        $result['votesCount'] = $site->getNumber_of_votes();
        $result['dateAdded'] = $site->getDate_added()->format("Y-m-d");

        $reviews = PCMapperReview::getReviewsWithSiteIdentifier($site->getIdentifier(), 0);
        $reviewsList = array();

        foreach ($reviews as $r) {
            $reviewArray = array();
            $reviewArray["vote"] = sprintf("%.1f", $r->getVote());

            $user = PCModelManager::fetchObjectWithIdentifier(PCModelUser::getMapper(), $r->getUserIdentifier(), NULL, TRUE);

            $reviewArray["user"] = $user->getUsername();
            $reviewArray["date_added"] = $r->getDate_added()->format("Y-m-d");
            $reviewArray["comment"] = $r->getComment();
            $reviewArray["reviewId"] = $r->getIdentifier();
            $reviewArray["userId"] = $r->getUserIdentifier();
            $reviewArray['reliability'] = sprintf("%.1f", $r->getReliabilityVote());
            $reviewArray['contents'] = sprintf("%.1f", $r->getContentsVote());
            $reviewArray['usability'] = sprintf("%.1f", $r->getUsabilityVote());
            $reviewsList[] = $reviewArray;
        }

        $result['reviews'] = $reviewsList;


        $result['title'] = "WebSherpa - " . $site->getUrl();
        return PCRendererHTML::rendererForView('host', $result);
    }

}

