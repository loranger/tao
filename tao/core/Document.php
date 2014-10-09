<?php

namespace tao\core;

/**
 * Dom abstract layer
 *
 * @package tao
 * @author loranger
 **/
class Document
{

    /**
     * Current DOM instance
     *
     * @var object
     **/
    public static $dom = false;

    /**
     * Root DOM Element
     *
     * @var string
     **/
    public static $root = false;

    /**
     * Current document system name
     *
     * @var string
     **/
    public static $systemName;

    /**
     * Current charset
     *
     * @var string
     **/
    public static $charset;

    /**
     * Document constructor
     *
     * @example Document new Document(
     * 				'xml',
     * 				'utf-8',
     * 				'-//W3C//DTD XHTML 1.0 Strict//EN',
     * 				'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd',
     * 				'http://www.w3.org/1999/xhtml'
     * 				);
     * @return object $this
     * @param  string $name          of the document
     * @param  string $charset
     * @param  string $public_id     of the document
     * @param  string $doctype_uri
     * @param  string $namespace_uri
     **/
    public function __construct($name = 'xml', $charset = null, $public_id = null, $doctype_uri = null, $namespace_uri = null)
    {

        self::$systemName = strtolower($name);

        $domImplementation = new \DOMImplementation;

        $dtd = $domImplementation->createDocumentType(strtolower(self::$systemName), $public_id, $doctype_uri);

        self::$dom = $domImplementation->createDocument($namespace_uri, self::$systemName, $dtd);
        if ($charset) {
            self::$charset = strtolower($charset);
            self::$dom->encoding = strtoupper(self::$charset);
        }
        self::$dom->preserveWhiteSpace = false;
        self::$dom->formatOutput = true;
        self::$dom->validateOnParse = true;

        self::$root = self::$dom->getElementsByTagName($name)->item(0);

        return $this;
    }

    /**
     * Find an element based on a css expression
     *
     * @return Element or array of Element
     * @author loranger
     * @param string css expression
     * @param Element or DOMElement as relative context node
     **/
    public static function find($expression, $context = false)
    {
        $context = ($context) ? $context : self::$root;
        $xpath = new \DOMXPath(self::$dom);
        if (!preg_match('/\//', $expression)) {
            $sel = new Selector($expression);
            $expression = $sel->toXpath();
        }
        $entries = $xpath->query($expression, $context);

        $list = array();
        foreach ($entries as $entry) {
            $list[] = new Element($entry);
        }

        /*
        // Baaaaaaad performance
        if (count($list)) {
            return new Elements($list);
        }
        /**/

        if (count($list)>1) {
            return new Elements($list);
        } elseif(count($list) == 1)
        {
            return $list[0];
        }

        return false;
    }

    /**
     * Convert current Document to string
     *
     * @return string
     **/
    public function __toString()
    {
        if (isset($this->rendered)) {
            return '';
        } else {
            $this->rendered = true;
            if (method_exists($this, 'render')) {
                return $this->render();
            } else {
                return self::$dom->saveXML();
            }
        }

    }

    /**
     * Document destructor
     **/
    public function __destruct()
    {
        // Only Page renders document
    }

}

