<?php


/**
 * Description of PCMapperWebsite
 *
 * @author paolo
 */
class PCMapperWebsite extends PCMapper {
    
     public function getRequiredAttributes() {
        return array("identifier","number_of_votes","domain","category",'usability','reliability','contents','cached');
    }

    public function getTableName() {
        return "website";
    } 
    
    public function getMappedInstance($properties) {
        $identifier = $properties["identifier"];
        $tld = isset($properties["tld"]) ? $properties["tld"] : NULL;
        $date_added = isset($properties["date_added"]) ? $properties["date_added"] : NULL;
        $domain = $properties["domain"];
        $user = isset($properties["user"]) ? $properties['user'] : NULL;
        $category = $properties["category"];
        $no_votes = $properties['number_of_votes'];
        $usability = $properties['usability'];
        $reliability = $properties['reliability'];
        $contents = $properties['contents'];
        $cache_date = $properties['cached'];
        
        return new PCModelWebsite($identifier, $domain, $date_added, $user, $tld, $category, $reliability, $contents, $usability, $cache_date, $no_votes);
    }
    
    /**
     * Cerca all'interno del db 
     * @param string $domain
     * @return array
     */
    public static function searchSiteWithDomainLike($domain, $offset){
        
        
        $pdo = PCDatabase::getSharedDatabaseConnection();
        $mapper = PCModelWebsite::getMapper();
        $domain .= "%";
        
        $elementsToReturn = 5;
        $select = "SELECT * FROM ".$mapper->getTableName()." WHERE domain LIKE :dom LIMIT ".($offset*$elementsToReturn)." , ".$elementsToReturn." ";
        
        $prepared = $pdo->prepare($select);
        $prepared->bindParam(":dom", $domain);
        
        $result = $prepared->execute();
        
        if($result === FALSE){
            error_log($prepared->queryString);            
            return FALSE;
        }
        
        $toReturn = array();
        while(($result = $prepared->fetch(PDO::FETCH_ASSOC)) != NULL){
            
            $toReturn[] = $mapper->getMappedInstance($result);
        }
        return $toReturn;
        
    }
    
    /**
     * Returns the website with a specific domain
     * @param string $domain
     * @return PCModelWebsite
     */
    public static function getSiteWithDomain($domain){
        $sites = PCModelManager::fetchModelObjectInstances(PCModelWebsite::getMapper(), array('domain'=>$domain));
        if(count($sites)<=0){
            return NULL;
        }
        return $sites[0];
    }
    
   /**
    * 
    * @param string $url
    * @param PCModelUser $user
    * @param string $comment
    * @param string $usability
    * @param string $contents
    * @param string $reliability
    * @param string $categoty
    * @param string $language
    * @param string $error
    * @param string $siteIdentifier
    * @return bool
    */
    public static function addSiteWithReview($url, $user, $comment, $usability, $contents, $reliability, $category, $language, &$error , $siteIdentifier = null) {
        //estraggo l' hostname dell'URL
        
        //Se è stato specificato l' identificativo del sito
        if(!empty($siteIdentifier)){
            $site = PCModelManager::fetchObjectWithIdentifier(PCModelWebsite::getMapper(), $siteIdentifier, NULL, TRUE);
            if(isset($site) == FALSE){
                $error = "Site not found";
                return FALSE;
            }
            $error3 = NULL;
            if(PCMapperReview::addReviewForSite($site, $user, $comment, $usability, $contents, $reliability, $language, $error3)){
                return TRUE;
            }
            $error = $error3;
            return FALSE;
        }
        //se p stato specificato l'URL del sito
        
        $parsedUrl = parse_url($url);
        $scheme = $parsedUrl['scheme'];

        if (($scheme == NULL) || ((strcmp($scheme, "http") != 0) && (strcmp($scheme, "https") != 0))) {
            $scheme = 'http';
        }

        $host = $parsedUrl['host'];
        if ($host == NULL) {
            $error = "URL not valid";
            return FALSE;
        }

        $site = static::getSiteWithDomain($host);

        //costruisco l'URL da verificare(reggiungibilità)
        $buildedUrl = $scheme . "://" . $host;

        //se il sito non è presente lo aggiungo
        if ($site == NULL) {
            
            
            if(PCMapperCategory::existsCategoryWithIdentifier($category) == FALSE){
                $error = "Category is not valid";
                return FALSE;
            }

            $tld = static::getTLDFromURL($buildedUrl);

            if (static::checkForWebsiteReachability($buildedUrl) == FALSE) {
                $error = "Error: site not reachable";
                return FALSE;
            }
            $date_added = new DateTime('now', new DateTimeZone('UTC'));
            $date_added_mysql = $date_added->format('Y-m-d H:i:s');
            $values = array(
                'domain' => $host,
                'date_added' => $date_added_mysql,
                'tld' => $tld,
                'category' => $category,
                'user' => $user->getIdentifier()
            );
            if( PCModelManager::insertObject(PCModelWebsite::getMapper(), $values)){
                $site = static::getSiteWithDomain($host);
            }
            else{
                $error = "Error adding website, please try later";
                return FALSE;
            }
        }
        
        if($site == NULL){
            $error = "Error adding website, please try later";
            return FALSE;
        }
        
        
        $error2 = NULL;
        
        if(PCMapperReview::addReviewForSite($site, $user, $comment, $usability, $contents, $reliability, $language, $error2)){
            return TRUE;
        }
        
        $error = $error2;
        
        return FALSE;
        
    }
    
    
    /**
     * Estrae il top level domain da un url
     * @param string $url l' url da cui estrarre il tol level domain
     * @return string  
     */
    private static function getTLDFromURL($url) {
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'];
        $components = explode(".", $host);

        return $components[count($components) - 1];
    }
    
    /**
     * Controlla che un sito internet esiste sul serio
     * @param string $url l'url del sito
     * @return boolean 
     */
    private static function checkForWebsiteReachability($url) {

       
       
        if ($url == NULL || empty($url)){
            return false;
        }
            
        $userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
       
        

        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300 || $httpcode == 405) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @XXX remove direct database interaction
     * @param PCModelWebsite $site
     */
    public static function recacheSiteReview($site){
        //error_log('RECACHING SITE INFO: '.$site->getIdentifier());
        $select = "SELECT avg(usability) as usability, avg(reliability) as reliability,";
        $select .= " avg(contents) as contents, count(identifier) as count ";
        
        $mapper = PCModelReview::getMapper();
        
        $select .= " FROM ".$mapper->getTableName()." WHERE site_identifier = :id";
        
        $pdo = PCDatabase::getSharedDatabaseConnection();
        
        $prepared = $pdo->prepare($select);
        
        $result = $prepared->execute(array(":id" => $site->getIdentifier()));

        if ($result === FALSE) {
            
            return NULL;
        }

        $item = $prepared->fetch(PDO::FETCH_ASSOC);
        if(!isset($item)){
           
            return NULL;
        }
        
        $usability = (double)$item['usability'];
        $reliability = (double)$item['reliability'];
        $contents = (double)$item['contents'];
        $count = (double)$item['count'];
        
        $cache_time  = new DateTime('now', new DateTimeZone('UTC'));
        
        $keys = array(
            'usability' => $usability,
            'reliability' => $reliability,
            'contents' => $contents,
            'number_of_votes' => $count,
            'cached' => $cache_time->format('Y-m-d H:i:s')
        );
        $condition = "identifier = :id";
        $bindings = array(':id' => $site->getIdentifier());
        $websiteMapper = PCModelWebsite::getMapper();
        if(PCModelManager::updateObject($websiteMapper, $keys, $condition, $bindings)){
            
            $site->cached_date = $cache_time;
            $site->contents = $contents;
            $site->number_of_votes = $count;
            $site->reliability = $reliability;
            $site->usability = $usability;
            
            PCCache::cacheProvider()->setItem($site, $websiteMapper->getTableName().$site->getIdentifier());
            return $site;
        }
        
        return NULL;
    }

      
}