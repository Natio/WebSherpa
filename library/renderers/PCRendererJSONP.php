<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCRendererJSONP
 *
 * @author paolo
 */
class PCRendererJSONP extends PCRenderer{
    
    /**
     * The function name
     * @var string
     */
    protected $callback;
    
    
    public function __construct($result, $callback = null, $code = 200) {
        parent::__construct($result, $code);
        $this->callback = $callback;
    }

    

    public function render() {
        
        echo $this->callback.'('.  json_encode($this->result) .');';
        
    }    
}

?>
