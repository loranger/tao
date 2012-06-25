<?php

/**
 * Tao singleton
 *
 * @return object Tao
 **/
function tao($expression)
{
    if ( preg_match('/<((\S+).*)>/', $expression, $match) ) {
        return new \tao\core\CodeImporter($expression);
    } else {
        return Page()->find($expression);
    }
}


/**
 * Page singleton
 *
 * @return object Page
 * @param string type of the page
 * @param string charset of the page
 * @param string locale of the page
 **/
function Page($type = 'xhtml', $charset = null, $locale = null)
{
    static $page;

    if (!$page) {
        $args = func_get_args();
        if (count($args)) {
            preg_match('/^([a-z]+)?( .+)?/i', @$args[0], $matches);
            $type = @$matches[1];
            if (array_key_exists(2, $matches)) {
                $args[0] = trim($matches[2]);
            } else {
                array_shift($args);
            }
        }

        $className = '\tao\core\page\Page'.ucfirst($type);
        $reflectionObj = new ReflectionClass($className);
        $page = $reflectionObj->newInstanceArgs($args);
    }

    return $page;
}

/**
 * TaoSettings singleton
 *
 * @return object TaoSettings
 **/
function TaoSettings()
{
    static $taoSettings;

    if (!$taoSettings) {
        $taoSettings = new \tao\core\TaoSettings();
    }

    return $taoSettings;
}
