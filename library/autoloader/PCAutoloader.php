<?

/**
 * Class for autoloading classes
 *
 * @author paolo
 */
final class PCAutoloader {

    static $loaded_classes = array();

    /**
     * inclued a class.
     * @param string $class_name
     * @return boolean 
     */
    public static function autoloader($class_name) {

        if (strncmp($class_name, "PCModel", 7) == 0) {
            $file_path = __MODEL__ . '/' . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCResponse", 10) == 0) {

            $file_path = __LIBRARY__ . "/http/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCAuth", 6) == 0) {
            $file_path = __LIBRARY__ . "/auth/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCMapper", 8) == 0) {
            $file_path = __MODEL__ . "/mapper/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCDatabase", 10) == 0) {
            $file_path = __LIBRARY__ . "/database/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCCache", 7) == 0) {
            $file_path = __LIBRARY__ . "/cache/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCException", 11) == 0) {
            $file_path = __LIBRARY__ . "/exceptions/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCRouter", 8) == 0) {
            $file_path = __LIBRARY__ . "/router/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCResponseCookie", 16) == 0) {
            $file_path = __LIBRARY__ . "/http/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp($class_name, "PCRenderer", 10) == 0) {
            $file_path = __LIBRARY__ . "/renderers/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp("PCEmail", $class_name, 7) == 0) {
            $file_path = __LIBRARY__ . "/email/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        } else if (strncmp("PCHelper", $class_name, 8) == 0) {
            $file_path = __LIBRARY__ . "/helpers/" . $class_name . ".php";
            return static::loadClassAtPath($file_path);
        }


        return false;
    }

    /**
     * Requres the file at $file_path if exist
     * @param string $file_path
     * @return boolean 
     */
    private static function loadClassAtPath($file_path) {

        if (file_exists($file_path)) {
            require $file_path;
            return true;
        }
        return false;
    }

    /**
     * 
     * @param string $name
     * @return boolean
     */
    public static function importLibrary($name) {
        if (empty($name))
            return FALSE;
        if (isset(static::$loaded_classes[$name]) && static::$loaded_classes[$name] == TRUE)
            return TRUE;

        $result = FALSE;

        if (strcmp("twitter", $name) == 0) {

            $file_path = __EXTERNAL_LIBRARIES__ . "/twitteroauth/twitteroauth/twitteroauth.php";
            $result = static::loadClassAtPath($file_path);
            if ($result) {
                if (defined('DEBUG')) {
                    define('TW_CONSUMER_KEY', '7rwy02J8TS9Oeh3HT5qplA');
                    define('TW_CONSUMER_SECRET', 'sLvFKLcHlutpCrQoo8yiuUghAj1HY0fell2aq8VI');
                    define('TW_OAUTH_CALLBACK', 'http://websherpa.loc/social/twittercallback');
                } else {
                    define('TW_CONSUMER_KEY', 'TngL02DDRScUbb1XI1xLfA');
                    define('TW_CONSUMER_SECRET', 'cJixhZQaY8dCZ4vOvGzcBRwuUf907wiYzeYqVhbChs');
                    define('TW_OAUTH_CALLBACK', 'https://websherpa.me/social/twittercallback');
                }
            }
        } else if (strcmp("facebook", $name) == 0) {
            $file_path = __EXTERNAL_LIBRARIES__ . "/facebook-php-sdk/src/facebook.php";
            $result = static::loadClassAtPath($file_path);
            if ($result) {
                if (defined('DEBUG')) {
                    define("FB_APP_ID", '477605738987851');
                    define("FB_APP_SECRET", '6b264035fe549dc5ce4784976e0fd807');
                } else {
                    define("FB_APP_ID", '137875069737861');
                    define("FB_APP_SECRET", 'f718e673e7edd563c0964cfabd5486e7');
                }
            }
        }

        static::$loaded_classes[$name] = $result;


        return $result;
    }

}

//Require classes that are rquired every time
require __LIBRARY__ . "/http/PCRequest.php";
require __LIBRARY__ . "/http/PCResponse.php";
require __LIBRARY__ . "/renderers/PCRenderer.php";
require __LIBRARY__ . "/controllers/PCController.php";

// Register the autoloader.
spl_autoload_register(array('PCAutoloader', 'autoloader'));
