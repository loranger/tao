<?php

namespace tao\core;

/**
 * Web page layer
 *
 * @package tao
 * @author loranger
 * @todo Implements frameset page methods
 **/
class Page extends Document
{
    /**
     * Current page schema
     *
     * @var array
     **/
    protected $schema = array('version'=>array('1.0'));

    /**
     * Current page head
     *
     * @var object
     **/
    public static $head;

    /**
     * Current page body
     *
     * @var object
     **/
    public static $body;

    /**
     * Used scripts src
     *
     * @var array
     **/
    private $scripts = array();

    /**
     * Script elements to add at the bottom of the page
     *
     * @var array
     **/
    private $bottomScripts = array();

    /**
     * Used css src
     *
     * @var array
     **/
    private $css = array();

    public function __construct($name = 'xml', $charset = 'utf-8', $public_id = null, $doctype_uri = null, $namespace_uri = null)
    {
        parent::__construct($name, $charset, $public_id, $doctype_uri, $namespace_uri);

        self::$head = new Element('head');
        self::$root->appendChild(self::$head->getElement());

        self::$body = new Element('body');
        self::$root->appendChild(self::$body->getElement());

        return $this;
    }

    /**
     * Set the title of the current page or replace it
     *
     * @return object $this
     * @param string title
     **/
    public function setTitle($titleText=false)
    {
        if ($titleText && !empty($titleText)) {
            $title = self::$head->find('title');
            if (!$title) {
                $title = new Element('title');
                self::$head->addContent($title);
            }
            $title->setContent($titleText);
        }

        return $this;
    }

    /**
     * Set the base uri of the current page or replace it
     *
     * @return object $this
     * @param string uri
     * @param string target
     **/
    public function setBase($uri, $target = false)
    {
        $base = self::$head->find('base');
        if (!$base) {
            $base = new Element('base');
            self::$head->addContent($base);
        }
        $base->setAttribute('href', $uri);

        if ($target) {
            $base->setAttribute('target', $target);
        } elseif($base->hasAttribute('target'))
        {
            $base->removeAttribute('target');
        }

        return $this;
    }

    /**
     * Add a meta tag to the current page or replace an existing one
     *
     * @return object $this
     * @param string name of the meta
     * @param string content of the 'content' attribute
     * @param string type of the meta ('name' or 'http-equiv')
     **/
    public function addMeta($name, $content, $type = 'name')
    {
        $meta = self::$head->find('meta['.$type.'="'.$name.'"]');
        if (!$meta) {
            $meta = new Element('meta');
            $meta->setAttribute($type, $name);
            self::$head->addContent($meta);
        }
        $meta->setAttribute('content', $content);

        return $this;
    }

    /**
     * Add a http-equiv meta tag to the current page or replace an existing one
     *
     * @return object $this
     * @param string name of the http-equiv
     * @param string content of the 'content' attribute
     **/
    public function addHTTPMeta($name, $content)
    {
        $this->addMeta($name, $content, 'http-equiv');

        return $this;
    }

    /**
     * Add a css link to the current Page
     *
     * @return object $this
     * @param string url of the css
     **/
    public function addCss($url)
    {
        $url = trim($url);
        if ( !in_array($url, $this->css) ) {
            array_push($this->css, $url);

            $css = new Element('link');
            $css->setAttribute('rel', 'stylesheet');
            $css->setAttribute('type', 'text/css');
            $css->setAttribute('href', $url);
            self::$head->addContent($css->getElement());
        }

        return $this;
    }

    /**
     * Add style definitions for a specified style tag (or create it if not exists)
     *
     * @return object $this
     * @param string style definition
     * @param string media (screen, print, ...)
     **/
    public function addStyle($string, $media = false)
    {
        if ($string && !empty($string)) {
            $style = ($media) ? self::$head->find('style[media="'.$media.'"]') : self::$head->find('style[not(@media)]');
            if (!$style) {
                $style = new Element('style');
                $style->setAttribute('type', 'text/css');
                if ($media) {
                    $style->setAttribute('media', trim($media));
                }
                self::$head->addContent($style);
            }
            $style->addContent( "\n".trim($string)."\n" );

            return $this;
        }

        return $this;
    }

    /**
     * Add a javascript src to the current Page
     *
     * @return object $this
     * @param string url of the script
     * @param string position of the script definition (top, head, bottom, body, false)
     **/
    public function addScriptSrc($url, $position = 'bottom')
    {
        $url = trim($url);
        if ( !in_array($url, $this->scripts) ) {
            array_push($this->scripts, $url);

            $script = new Element('script');
            $script->setAttribute('type', 'text/javascript');
            $script->setAttribute('src', $url);

            switch ($position) {
                case 'top':
                case 'head':
                    self::$head->addContent($script->getElement());
                    break;
                case 'bottom':
                case 'body':
                    array_push($this->bottomScripts, $script);
                    break;
                default:
                    $this->addContent($script);
                    break;
            }
        }

        return $this;
    }

    /**
     * Add a content to the current Page
     *
     * @return Page
     * @param  mixed (string, array, Elements, Element or DOMElement) Exception will be thrown if Page cannot use the $content
     **/
    public function addContent($content)
    {

        if (is_object($content)) {
            if (is_a($content, 'Element')) {
                $content = $content->getElement();
            }
            if (is_a($content, 'Elements')) {
                $content = iterator_to_array($content);
            }
        }

        if (is_array($content)) {
            foreach ($content as $item) {
                $this->addContent($item);
            }

            return $this;
        }

        if (is_string($content)) {
            $content = self::$dom->createTextNode($content);
        }

        try {
            self::$body->addContent($content);
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }

    /**
     * Page destructor
     *
     * @return mixed string or overriden render method
     **/
    public function __destruct()
    {
        foreach ($this->bottomScripts as $script) {
            $this->addContent($script);
        }
        echo $this;
    }

}
