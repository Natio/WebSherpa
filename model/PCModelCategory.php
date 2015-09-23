<?

/**
 * Description of PCModelCategory
 *
 * @author paolo
 */
class PCModelCategory extends PCModelObject {
    
    /**
     * category name
     * @var string
     */
    private $name;

    function __construct($identifier, $name) {
        parent::__construct($identifier);
        $this->name = $name;
    }
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    
    
    public static function getMapper() {
        static $mapper = null;
        if ($mapper == null) {
            $mapper = new PCMapperCategory();
        }
        return $mapper;
    }

    public function jsonSerialize() {
        $res = parent::jsonSerialize();
        $res['name'] = $this->name;
        return $res;
    }
}

