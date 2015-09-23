<?php



/**
 * Description of PCModelReport
 *
 * @author paolo
 */
class PCModelReport extends PCModelObject {
    
    /**
     *
     * @var string
     */
    private $user_identifier;
    
    /**
     *
     * @var string
     */
    private $site_identifier;
    
    
    /**
     *
     * @var string
     */
    private $text;
    
    
    /**
     *
     * @var DateTime 
     */
    private $date_added;
    
    
    
    function __construct($user_identifier, $site_identifier, $text, $date_added) {
        $this->user_identifier = $user_identifier;
        $this->site_identifier = $site_identifier;
        $this->text = $text;
        
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
    public function getUserIdentifier() {
        return $this->user_identifier;
    }

    /**
     * 
     * @return string
     */
    public function getSiteIdentifier() {
        return $this->site_identifier;
    }

    /**
     * 
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * 
     * @return DateTime
     */
    public function getDate_added() {
        return $this->date_added;
    }

        
    public static function getMapper() {
        static $mapper = null;
        if ($mapper == null) {
            $mapper = new PCMapperReport();
        }
        return $mapper;
    }    
    
}

?>
