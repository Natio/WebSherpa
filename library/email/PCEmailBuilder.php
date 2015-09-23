<?

class PCEmailBuilder {

    
    /**
     * Restituisce il messaggio email renderizzato sul template dello smarrimento password
     * @param string $addr
     * @param string $link
     * @param ModelUser $user
     * @return PCEmail
     */
    public static function buildEmailForPasswordLost($link, $user) {
        $result = array("title" => "WebSherpa password request",
                        "username" => $user->getUsername(),
                        "link" => $link);
        $renderer = new PCRendererEmail($result, "repass");
        $body = $renderer->render();
        
        return new PCEmail("Websherpa password Reset", "noreply@websherpa.me", $user->getEmail(), $body,"WebSherpa", TRUE);
    }
    /**
     * Restituisce il messaggio email renderizzato sul template per la notifica del cambiamento password
     * @param string $password
     * @param ModelUser $user
     * @return PCEmail
     */
    public static function buildEmailForPasswordNotification($password, $user){
        $result = array("title" => "WebSherpa password request",
                        "username" => $user->getUsername(),
                        "pass" => $password);
        $renderer = new PCRendererEmail($result, "passNotif");
        $body = $renderer->render();
        
        return new PCEmail("Websherpa password Reset Completed", "noreply@websherpa.me", $user->getEmail(), $body,"WebSherpa", TRUE);
    }
    /**
     * 
     * @param array $info
     * @return \PCEmail
     */
    public static function buildContactUsEmail($info){
        
        $info['title'] = "Websherpa User Contact";
       
        $renderer = new PCRendererEmail($info, "contactMail");
        $body = $renderer->render();
        return new PCEmail("Websherpa User Contact", "noreply@websherpa.me", "paolo.coronati@gmail.com", $body,"WebSherpa", TRUE);
    }

}

