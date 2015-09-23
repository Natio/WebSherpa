<?php

/**
 * Description of PCRenderer
 *
 * @author paolo
 */
abstract  class PCRenderer {
    /**
     *
     * @var mixed
     */
    protected $result;
    
    /**
     *
     * @var int
     */
    protected $http_status_code;


    public function __construct($result, $code = 200) {
        $this->result = $result;
        $this->http_status_code = $code;
    }

    public abstract function render();
    
    public function getHttp_status_code() {
        return $this->http_status_code;
    }


}

