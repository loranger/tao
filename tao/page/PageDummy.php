<?php

namespace tao\page;

class PageDummy extends PageXhtml
{

    public function __construct()
    {
        $language = false;
        parent::__construct('1.0', 'utf-8', $language);

        $this->setTitle( 'Dummy page' );
        $this->addCss('static/css/dummy.css');
        $this->addScriptSrc('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js', 'top');
    }

    public function render()
    {
        $footer = tao('<div />')->setId('footer');
        $footer->addContent('This is the footer rendered with every ' . get_class($this));
        $this->addContent($footer);

        return parent::render();
    }

}

