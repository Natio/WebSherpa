<?php

// VERY RELAXED! Shouldn't cause problems, not even Firefox checks if the
// email is valid, but be careful!

/**
 * Validates mailto (for E-mail) according to RFC 2368
 *
 */

class HTMLPurifier_URIScheme_mailto extends HTMLPurifier_URIScheme {

    public $browsable = false;
    public $may_omit_host = true;

    public function doValidate(&$uri, $config, $context) {
        $uri->userinfo = null;
        $uri->host     = null;
        $uri->port     = null;
        // we need to validate path against RFC 2368's addr-spec
        return true;
    }

}

// vim: et sw=4 sts=4
