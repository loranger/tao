<?php

namespace tao\core\page;

/**
 * Html page layer
 *
 * @package tao
 * @author loranger
 **/
class PageHtml extends \tao\core\Page
{

    /**
     * Current html document schema
     *
     * @var array
     **/
    protected $schema = array('variant'=>'transitional','version'=>array('4.01'));

    /**
     * PageHtml constructor
     *
     * @example PageHtml new PageHtml('html 4.01 strict', 'iso8859-1', 'fr');
     * @example Page Page('html 4.01 strict', 'iso8859-1', 'fr');
     *
     * @return object $this
     * @param  string $version  of the current html page
     * @param  string $charset
     * @param  string $language
     **/
    public function __construct($version = '4.01', $charset = false, $language = false)
    {

        $args = func_get_args();

        $public_id = null;
        $doctype_uri = null;

        preg_match('/^([0-9.]+)( [a-z]+)?$/', strtolower(trim($version)), $parts);

        if (count($parts)) {

            $public_id = '-//W3C//DTD HTML';
            foreach ($parts as $key => $value) {
                switch ($key) {
                    case 1:
                        $this->schema['version'] = explode('.', trim($value));
                        if (array_key_exists(1, $this->schema['version']) && $this->schema['version'][1] == 0) {
                            unset($this->schema['version'][1]);
                        }
                        $public_id .= ' '.trim($value);
                        break;
                    case 2:
                        $this->schema['variant'] = trim($value);
                        break;
                }
            }
            $public_id .= ' '.ucfirst($this->schema['variant']);
            $public_id .= '//EN';

            if ($this->schema['version'][0] == 4) {
                if (array_key_exists('variant', $this->schema)) {
                    $variant = ($this->schema['variant'] == 'transitional') ? 'loose' : $this->schema['variant'];
                } else {
                    $variant = 'loose';
                }
                $doctype_uri = sprintf('http://www.w3.org/TR/%s/%s.dtd',
                                        'html'.$this->schema['version'][0],
                                        (($variant) ? $variant : '')
                                        );
            } else {
                $public_id = null;
                $doctype_uri = null;
            }

        }

        parent::__construct('html', $charset, $public_id, $doctype_uri);

        if ($charset) {
            $meta = self::$dom->createElement('meta');
            $meta->setAttribute('http-equiv', 'Content-Type');
            $meta->setAttribute('content', 'text/html; charset='.strtoupper($charset));
            self::$head->getElement()->appendChild($meta);
        }

        if ($language) {
            self::$root->setAttribute('lang', $language);
        }

        return $this;
    }

    /**
     * Rendering method (called by Document)
     *
     * @return string page source code
     **/
    public function render()
    {
        return self::$dom->saveHTML();
    }

}

