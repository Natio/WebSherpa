<?


final class PCConfigManager {
    private static $instance = null;
    
    /**
     *
     * @var array
     */
    private $storage;

    private function __construct() {
        $path =  __ROOT__ . '/config/php/'.( defined('DEBUG') ? 'configuration_debug.ini' : 'configuration.ini' );
        if(file_exists($path)) $this->storage = parse_ini_file($path, TRUE);
        if($this->storage == NULL) $this->storage = array();
    }
    
    public function getValue($key){
        return isset($this->storage[$key]) ? $this->storage[$key] : $this->storage['override_allowed'][$key];
    }
    
    /**
     * @param string $key
     * @return integer
     */
    public function getIntegerValue($key){
        return intval($this->getValue($key));
    }

    /**
     * @param string $key
     * @return float
     */
    public function getFloatValue($key){
        return floatval($this->getValue($key));
    }
    /**
     * 
     * @param string $key
     * @return boolean
     */
    public function getBoolValue($key){
        $val = isset($this->storage[$key]) ? $this->storage[$key] : $this->storage['override_allowed'][$key];
        if(isset($val)){
            return $val === TRUE || $val === "1";
        }
        return FALSE;
    }
    /**
     * 
     * @return PCConfigManager
     */
    public static function sharedManager() {
        if (static::$instance == null) {
            static::$instance = new PCConfigManager;
        }
        return static::$instance;
    }
    
    /**
     * Legge la configurazione da parametri GET
     * @param boolean $shouldOverride se specificato sovrascrive i valori precedenti
     */
    public function readConfigurationFromGET($shouldOverride = TRUE) {
        foreach ($_GET as $key => $value) {

            if (strncmp($key, "D_", 2) == 0) {
                $key = substr($key, 2);
                if (strcmp($value, "TRUE") == 0)
                    $value = TRUE;
                if ($shouldOverride) {
                    $this->storage['override_allowed'][$key] = $value;
                } else if (isset($this->storage['override_allowed'][$key]) === FALSE) {
                    $this->storage['override_allowed'][$key] = $value;
                }
            }
        }
    }

}

