<?
class PCEmail {
    /**
     * L'ogetto del messaggio
     * @var string
     */
    private $subject;
    /**
     * Il mittente
     * @var string
     */
    private $sender;
    /**
     * il destinatatio
     * @var string
     */
    private $recipient;
    
    /**
     * Il corpo del messaggio
     * @var string 
     */
    private $body;
    
    /**
     * indica se il corpo del messaggio è in HTML
     * @var boolean 
     */
    private $isHTML;
    
    /**
     *
     * @var string
     */
    private $senderName;
    
    function __construct($subject, $sender, $recipient, $body, $senderName, $html = FALSE) {
        $this->subject = $subject;
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->body = $body;
        $this->isHTML = $html;
    }

    /**
     * Restituisce l' ogetto del messaggio
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }
    /**
     * il mittente
     * @return string
     */
    public function getSender() {
        return $this->sender;
    }
    /**
     * il destinatario
     * @return string
     */
    public function getRecipient() {
        return $this->recipient;
    }
    /**
     * il corpo del messaggio
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * se il corpo del messaggio è html
     * @return boolean
     */
    public function isHTML(){
        return $this->isHTML;
    }
    
    /**
     * il nome del mittente
     * return string
     */
    public function getSenderName(){
        return $this->senderName;
    }
    
}

