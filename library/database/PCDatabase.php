<?


class PCDatabase{
    
    
    private static $databaseHost = "localhost";//"masterdb.crq5xrxogxeo.eu-west-1.rds.amazonaws.com";
    private static $databaseUser = "root";
    private static $databasePassword = "photosmart";
    private static $databaseName = "websherpa_db";
    
    /**
     * database connection
     * @var PDO 
     */
    private $connection = null;
    
    /**
     * Shared instance 
     * @var PCDatabase 
     */
    private static $db = null;
    
    
    private function __construct() {
        
        if(static::$db != null){
            throw new Exception("Runtime error, double PCDatabse instances");
        }
        
        if(defined('DEBUG')){
            static::$databaseHost = "localhost";
            static::$databaseUser = "root";
            static::$databasePassword = "root";
            static::$databaseName =  "websherpa_db";
        }

        
        try {
            $host = static::$databaseHost;
            $dbname = static::$databaseName;
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", static::$databaseUser, static::$databasePassword,array(PDO::MYSQL_ATTR_INIT_COMMAND =>  'SET NAMES utf8'));
        }
        catch(PDOException $e) {  
            
            error_log("Failed to connect to MySQL: " . $e->getMessage());
            die("Failed to connect to Database");
        }

        $this->connection = $pdo;
       
    }
    
    public function __destruct() {
        $this->connection = NULL;
    }
    
    
    /**
     * Returns the shared database connection
     * @return PDO
     */
    public static function getSharedDatabaseConnection(){
        if(static::$db == null){
            static::$db = new PCDatabase();
        }
        return static::$db->connection;
    }
    
}
