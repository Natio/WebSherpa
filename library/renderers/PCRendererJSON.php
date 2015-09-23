<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PCRendererJSON
 *
 * @author paolo
 */
class PCRendererJSON extends PCRenderer{
    
    public function render() {
        echo json_encode($this->result);
    }
    
}

