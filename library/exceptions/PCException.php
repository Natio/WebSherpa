<?php

class PCException extends Exception{
    public function __construct($message, $code) {
        parent::__construct($message, $code, NULL);
    }
}