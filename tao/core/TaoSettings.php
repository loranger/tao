<?php

namespace tao\core;

/**
 * Configuration and initialization
 *
 * @package tao
 * @author loranger
 **/
class TaoSettings
{
    /**
     * Autoload switch
     *
     * @var boolean
     **/
    private $autoload = false;

    /**
     * Autoload pathes to lookup
     *
     * @var array
     **/
    private $autoloadpath = array('core/', 'core/page/');

    /**
     * Overriding featuring name
     *
     * @var string
     **/
    private $featuring = false;

    /**
     * Default locale
     *
     * @var string
     **/
    private $defaultLocale = 'en_EN';

    /**
     * Locale path
     *
     * @var string
     **/
    private $localePath = 'i18n/';

    public function __construct()
    {
        foreach ($this->autoloadpath as &$path) {
            $path = TAO_PATH.$path;
        }

        // TODO : Patch in a clever and universal way (sprintf, ngettext, dgettext, dcgettext, etc...)
        if ( !function_exists('gettext') ) {
            function _($string)
            {
                return $string;
            }
            function gettext($string)
            {
                return $string;
            }
        }
    }

    /**
     * Enable or disable autoload
     *
     * @return void
     * @param boolean
     **/
    public function useAutoload($boolean = false)
    {
        if ( is_bool($boolean) && $boolean ) {
            $this->autoload = true;
            spl_autoload_register(array($this, 'autoload'));
        } else {
            if ($this->autoload == true) {
                spl_autoload_unregister(array($this, 'autoload'));
            }
            $this->autoload = false;
        }
    }

    /**
     * Autoload handler
     *
     * @param Class
     **/
    public function autoload($object)
    {
        if (!class_exists($object)) {
            $exists = false;

            foreach (TaoSettings()->get('autoloadpath') as $path) {
                if ($exists = !class_exists($object) && file_exists($class = $path . $object . '.php')) {
                    require_once($class);
                    break;
                }
            }

            if (!$exists) {
                eval('class '.$object.' extends Exception {}');

                $msg = sprintf(_('The <strong>%s</strong> class doesn\'t exists'), $object)."<br />\n";
                $msg .= _('Tested paths :')."<br />\n";
                $msg .= implode("<br />\n", TaoSettings()->get('autoloadpath'));

                throw new $object($msg);
            }
        }
    }

    /**
     * Add a path for autoload to lookup
     *
     * @return void
     * @param string path
     **/
    public function addAutoloadPath($path)
    {
        if (!$this->autoload) {
            $this->useAutoload(true);
        }
        if ( preg_match('/\*/', $path)) {
            array_walk(glob($path, GLOB_BRACE), array($this, 'addAutoloadPath'));
        } elseif (!in_array(trim($path), $this->autoloadpath)) {
            array_push($this->autoloadpath, trim($path));
        }
    }

    /**
     * Define the featuring layer
     *
     * @return void
     * @param boolean
     **/
    public function setFeaturing($featuring=false)
    {
        $this->featuring = $featuring;
    }

    /**
     * Define the default locale
     *
     * @return void
     * @param string
     **/
    public function setLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * Define the gettext path
     *
     * @return void
     * @param string
     **/
    public function setLocalePath($path)
    {
        $this->localePath = $path;
    }

    /**
     * Get the conf value if exists
     *
     * @return mixed conf value or false
     * @param string
     **/
    public function get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return false;
    }

}
