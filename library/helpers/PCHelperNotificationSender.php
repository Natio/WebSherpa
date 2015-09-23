<?

/**
 * Description of PCHelperNotificationSender
 *
 * @author paolo
 */
class PCHelperNotificationSender {
    
    
   /**
    * 
    * @param string $event
    * @param string $details
    * @param string $application
    */
   public static function sendPushNotificationToAdmin($event, $details, $application = "WebSherpa") {

        $post_val = array(
            "apikey" => "1c01ced8897de4582962fab91ee7efb62c13d249",
            "application" => $application,
            "event" => $event,
            "description" => $details
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.prowlapp.com/publicapi/add");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_val));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_exec($ch);
        curl_close($ch);
    }
}
