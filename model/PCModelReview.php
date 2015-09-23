<?

/**
 * Description of PCModelReview
 *
 * @author paolo
 */
class PCModelReview extends PCModelObject{
    
    const maxCommentLength = 200;

    const maxVote = 10.0;
    const minVote = 0.0;

        /**
     * Il testo della recensione
     * @var string 
     */
    protected $comment;
    
    
    /**
     * l'id del sito della recensione
     * @var string 
     */
    protected $site;
    
    
    /**
     * l'id dell' utente che ha lasciato la recensione
     * @var  string
     */
    protected $user;
    
    /**
     * il punteggio lasciato dall' utente 
     *@var float 
     */
    protected $usability;
    
    /**
     * il punteggio lasciato dall' utente 
     *@var float 
     */
    protected $reliability;
    
    /**
     * il punteggio lasciato dall' utente 
     *@var float 
     */
    protected $contents;


    /**
     * il codice della lingua della recensione
     * @var string 
     */
    
    protected $language_code;
    
    /**
     * La data in cui è stata lasciata la recensione
     * @var DateTime
     */
    protected $date_added;
    
    
    function __construct($identifier, $comment, $site, $user, $usability, $reliability, $contents, $language_code, $date_added) {
        parent::__construct($identifier);
        $this->comment = $comment;
        $this->site = $site;
        $this->user = $user;
        $this->usability = $usability;
        $this->reliability = $reliability;
        $this->contents = $contents;
        $this->language_code = $language_code;
        
        if ($date_added instanceof DateTime) {
            $this->date_added = $date_added;
        } else {
            $this->date_added = new DateTime($date_added, new DateTimeZone('UTC'));
        }
        
    }
    
    /**
     * 
     * @return string
     */
    public function getComment() {
        return $this->comment;
    }

    /**
     * 
     * @return string
     */
    public function getSiteIdentifier() {
        return $this->site;
    }

    /**
     * 
     * @return string
     */
    public function getUserIdentifier() {
        return $this->user;
    }

    /**
     * 
     * @return float
     */
    public function getUsabilityVote() {
        return $this->usability;
    }

    /**
     * 
     * @return float
     */
    public function getReliabilityVote() {
        return $this->reliability;
    }
    /**
     * 
     * @return float
     */
    public function getContentsVote() {
        return $this->contents;
    }

    /**
     * 
     * @return string
     */
    public function getLanguage_code() {
        return $this->language_code;
    }

    /**
     * 
     * @return DateTime
     */
    public function getDate_added() {
        return $this->date_added;
    }

    /**
     * @return float
     */
    public function getVote(){
        $sum = (float)( $this->usability + $this->reliability + $this->contents);
        return $sum / 3.0;
    }
    
    
    public function jsonSerialize() {
        $ret = parent::jsonSerialize();
        $ret['language_code'] = $this->language_code;
        $ret['text'] =$this->comment ;
        $ret['site'] =$this->site ;
        $ret['user'] =$this->user ;
        $ret['points'] =$this->getVote() ;
        $ret['uasbility'] = $this->usability;
        $ret['reliability'] = $this->reliability;
        $ret['contents'] = $this->contents;
        $ret['date_added'] = $this->date_added->format(DateTime::RFC1123);
        //$ret['evaluations'] = $this->evaluations;
        return $ret;
    }

    public static function getMapper() {
        static $mapper = null;
        if ($mapper == null) {
            $mapper = new PCMapperReview();
        }
        return $mapper;
    }

}