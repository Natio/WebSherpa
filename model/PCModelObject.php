<?

/**
 * Description of PCModelManager
 *
 * @author paolo
 */
abstract class PCModelObject implements JsonSerializable {
    
    /**
     *  The object identifier
     * @var string 
     */
    private $identifier;
    
    
    public function __construct($identifier) {
        $this->identifier = $identifier;
    }


    
    /**
     * The instance identifier
     * @return string instance identifier
     */
    public function getIdentifier(){
        return $this->identifier;
    }
    
    
   public static function getMapper() {
        throw new PCException("Illegal call", 500);
    }


   public function jsonSerialize() {
        return array("identifier"=> $this->identifier);
    }
}