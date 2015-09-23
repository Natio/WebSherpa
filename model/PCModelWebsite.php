<?php



/**
 * Description of PCModelWebsite
 *
 * @author paolo
 */
class PCModelWebsite extends PCModelObject {
    
    /**
     * Il dominio
     * @var string
     */
    protected $domain;
    
    /**
     * La data di aggiunta
     * @var DateTime 
     */
    protected $date_added;
    
    /**
     * The identifier of the user that added this site
     * @var string 
     */
    protected $user;
    
    /**
     * Top Level Domain
     * @var string 
     */
    
    protected $tld;
    
    /**
     * The identifier of the PCModelCategory
     * @var string
     */
    protected $category;
    
    
    /**
     *  The reliability
     * @var double 
     */
    public $reliability;
    
    /**
     * The contents vote
     * @var double
     */
    public $contents;
    
    /**
     * The usability vote
     * @var double 
     */
    public $usability;

    /**
     *
     * @var DateTime  
     */
    public $cached_date;
    
    /**
     * il numero di voti su cui è basata la media
     * @var int
     */
    public $number_of_votes = 0;

    /**
     * 
     * @param string $identifier
     * @param string $domain
     * @param DateTime|integer $date_added
     * @param string $user
     * @param string $tld
     * @param string $category
     */
    function __construct($identifier, $domain, $date_added, $user, $tld, $category, $reliability, $contents, $usability, $cache_date, $no_votes) {
        parent::__construct($identifier);
       
        $this->domain = $domain;
        if ($date_added instanceof DateTime) {
            $this->date_added = $date_added;
        } else {
            $this->date_added = new DateTime($date_added, new DateTimeZone('UTC'));
        }

        $this->user = $user;
        $this->tld = $tld;
        $this->category = $category;
        $this->contents = (float)$contents;
        $this->reliability = (float)$reliability;
        $this->usability = (float)$usability;
        $this->number_of_votes = (int)$no_votes;
        if ($cache_date instanceof DateTime) {
            $this->cached_date = $cache_date;
        } else {
            $this->cached_date = (isset($cache_date)) ? new DateTime($cache_date, new DateTimeZone('UTC')) : NULL;
        }
    }
    
    /**
     * 
     * @return int
     */
    public function getNumber_of_votes() {
        return $this->number_of_votes;
    }

        
    /**
     * 
     * @return float
     */
    public function getReliability() {
        return $this->reliability;
    }
    /**
     * 
     * @return float
     */
    public function getContents() {
        return $this->contents;
    }
    /**
     * 
     * @return float
     */
    public function getUsability() {
        return $this->usability;
    }

    /**
     * 
     * @return DateTima
     */
    public function getCached_date() {
        return $this->cached_date;
    }

    /**
     * 
     * @return boolean
     */
    public function cacheIsExpired(){
        if($this->cached_date == NULL) return TRUE;
        $now = new DateTime('now', new DateTimeZone('UTC'));
        
        $diff = $now->getTimestamp() - $this->cached_date->getTimestamp();
        
        if($diff === NULL) return TRUE;
        //1/2 ora, cioè la durate della cache
        $chacheTime = PCConfigManager::sharedManager()->getIntegerValue('WEBSITE_CACHE_TIME');
        
        return $diff > $chacheTime ;
    }
    
    /**
     * 
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }
    
    /**
     * Same as getDomain, only for compatibility
     * @return string
     */
    public function getUrl(){
        return $this->getDomain();
    }

    /**
     * 
     * @return DateTime
     */
    public function getDate_added() {
        return $this->date_added;
    }

    /**
     * 
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * 
     * @return string
     */
    public function getTld() {
        return $this->tld;
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @return float
     */
    public function getVote(){
        $sum = (float)( $this->usability + $this->reliability + $this->contents);
        return $sum / 3.0;
    }
    
    public function jsonSerialize() {
        $arr = parent::jsonSerialize();
        $arr['category'] = $this->category;
        $arr['date_added'] = $this->date_added->getTimestamp();
        if($this->tld != NULL) $arr['tld'] = $this->tld;
        $arr['url'] = $this->domain;
        $arr['vote'] = $this->getVote();
        $arr['voteNum'] = $this->number_of_votes;
        $arr['usability'] = $this->usability;
        $arr['reliability'] = $this->reliability;
        $arr['contents'] = $this->contents;
       
        
        return $arr;
    }
    /**
     * 
     * @staticvar null $mapper
     * @return \PCMapperWebsite
     */
    public static function getMapper() {
        static $mapper = null;
        if($mapper == null){
            $mapper = new PCMapperWebsite();
        }
        return $mapper;
       
    }
 
}
