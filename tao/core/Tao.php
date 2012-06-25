<?php

namespace tao\core;

class Tao extends CodeImporter
{
    public function __construct($expression)
    {
        if ( preg_match('/<((\S+).*)>/', $expression, $match) ) {
            parent::__construct($expression);
        } else {
            Element::__construct($expression);
        }
    }
}
