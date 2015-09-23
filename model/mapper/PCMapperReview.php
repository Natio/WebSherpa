<?

/**
 * Description of PCMapperReview
 *
 * @author paolo
 */
class PCMapperReview extends PCMapper{
    
    public function getMappedInstance($attributes) {
        $identifier = $attributes['identifier'];
        $site = $attributes['site_identifier'];
        $user = $attributes['user_identifier'];
        $comment = $attributes['comment'];
        $usability = floatval($attributes['usability']);
        $reliability = floatval($attributes['reliability']);
        $contents = floatval($attributes['contents']);
        $date_added = $attributes['date_added'];
        $language_code = $attributes['language_code'];
        
        return new PCModelReview($identifier, $comment, $site, $user, $usability, $reliability, $contents, $language_code, $date_added);
    }

    public function getRequiredAttributes() {
        return array("identifier","site_identifier","user_identifier","comment","usability","reliability","contents","date_added","language_code");
    }

    public function getTableName() {
        return "reviews_tbl";
    }
    
    /**
     * 
     * @param PCModelWebsite $site
     * @param PCModelUser $user
     * @param string $comment
     * @param string $usability
     * @param string $contents
     * @param string $reliability
     * @param string $language
     * @param string $error
     * @return bool
     */
    public static function addReviewForSite($site, $user, $comment, $usability, $contents, $reliability, $language, &$error) {
        if (!isset($site) || !isset($site)){
            die("Internal Inconsistency");
        }
        
        if(!PCHelperValidator::validateLanguageCode($language)){
            $error = "Invalid language code";
            return FALSE;
        }
        $language = strtoupper($language);
        $commentLen = strlen($comment);
        if ($commentLen < 5 || $commentLen > PCModelReview::maxCommentLength) {
            
            $error = "Invalid comment length, ".( ($commentLen < 5) ? " minumum length is 6 characters" : "maximum length is 200 characters");
            return FALSE;
        }
        
        $usa = floatval($usability);
        $cont = floatval($contents);
        $rel = floatval($reliability);
        
        if($usa < PCModelReview::minVote ||$usa > PCModelReview::maxVote){
            $error = "usability is not valid";
            return FALSE;
        }
        if($cont < PCModelReview::minVote ||$cont > PCModelReview::maxVote){
             $error = "contents is not valid";
            return FALSE;
        }
        if($rel < PCModelReview::minVote ||$rel > PCModelReview::maxVote){
             $error = "reliability is not valid";
            return FALSE;
        }
        
        $date_added = new DateTime('now', new DateTimeZone('UTC'));
        $date_added_mysql = $date_added->format('Y-m-d H:i:s');
        
        $values = array(
            'site_identifier' => $site->getIdentifier(),
            'user_identifier' => $user->getIdentifier(),
            'comment' => $comment,
            'usability' => $usa,
            'reliability' => $rel,
            'contents' => $cont,
            'language_code' => $language,
            'date_added' => $date_added_mysql
        );
        
        if($user->getAccountType() !== PCModelUser::$TYPE_DEFAULT && PCConfigManager::sharedManager()->getBoolValue('SOCIAL_POST_ON_REVIEW') ){
            $user->postReviewToTimeline($values,$site);
        }
        
        $dupUpdate = array('language_code','date_added','contents','reliability','usability','comment');
        
        if(PCModelManager::insertObject(PCModelReview::getMapper(), $values, $dupUpdate)){
            return TRUE;
        }
        $error = "Error adding review please try later";
        return FALSE;
    }
    
    /**
     * 
     * @param string $user_id
     * @param PCModelWebsite $site
     * @return PCModelReview
     */
    public static function lastReviewForSite($user_id, $site){
        if($user_id == NULL) return NULL;
        $keys = array('user_identifier' => $user_id, 'site_identifier'=> $site->getIdentifier());
        $reviews = PCModelManager::fetchModelObjectInstances(PCModelReview::getMapper(), $keys);
        
        if(count($reviews) <= 0){
            return NULL;
        }
        return $reviews[0];
    }
    
    /**
     * 
     * @param string $site
     * @param int $offset
     * @return array An array of reviews
     */
    public static function getReviewsWithSiteIdentifier($site, $offset){
        $offset = (int)$offset;
        if($offset<0){
            return NULL;
        }
        $keys = array('site_identifier' => $site);
        //$elementsToReturn = 10;
        $elementsToReturn = PCConfigManager::sharedManager()->getIntegerValue('REVIEWS_PER_CALL');
        
        $limit = ($offset*$elementsToReturn)." , ".$elementsToReturn." ";
        $elements = PCModelManager::fetchModelObjectInstances(PCModelReview::getMapper(), $keys, NULL, FALSE, $limit);
        return $elements;
    }
    
    /**
     * 
     * @param string $user
     * @param int $offset
     * @return array
     */
    public static function getReviewsWithUserIdentifier($user, $offset){
        $offset = (int)$offset;
        if($offset<0){
            return NULL;
        }
        $keys = array('user_identifier' => $user);
        //$elementsToReturn = 10;
        $elementsToReturn = PCConfigManager::sharedManager()->getIntegerValue('REVIEWS_PER_CALL');
        $limit = ($offset*$elementsToReturn)." , ".$elementsToReturn." ";
        $elements = PCModelManager::fetchModelObjectInstances(PCModelReview::getMapper(), $keys, NULL, FALSE, $limit);
        return $elements;
    }
    
    /**
     * XXX rimuovere utilizzo diretto db
     * @param string $user_identifier
     * @return array
     */
    public static function getUserAverageAndCount($user_identifier){
        
        $mapper = PCModelReview::getMapper();
        
        $pdo = PCDatabase::getSharedDatabaseConnection();
        $select = "SELECT avg(usability) as usability, avg(reliability) as reliability,";
        $select .= " avg(contents) as contents, count(contents) as tot FROM ".$mapper->getTableName();
        $select .= " WHERE user_identifier = :id ;";
        
        $prepared = $pdo->prepare($select);
        
        
        $result = $prepared->execute(array(':id'=> $user_identifier));
        
        if($result === FALSE){
            
            return NULL;
        }
        
        $item = $prepared->fetch(PDO::FETCH_ASSOC);
        
        if(!isset($item)){
           
            return NULL;
        }
        
        $usability =(double) $item['usability'];
        $reliability = (double)$item['reliability'];
        $contents = (double)$item['contents'];
        $tot = (int)$item['tot'];
        $avg = ($usability + $reliability + $contents )/3.0;
        
        return array(
            'avg'=>$avg, 'tot'=>$tot,
            'usability'=>$usability,
            'reliability'=>$reliability,
            'contents'=>$contents
                );
        
    }
    
}