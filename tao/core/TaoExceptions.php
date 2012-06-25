<?php

namespace tao\core;

/**
 * Exceptions handler
 *
 * @package tao
 * @author loranger
 **/
class TaoExceptions
{

    /**
     * Known errors
     *
     * @var array
     **/
    public static $errors = array();

    /**
     * Define getError as exception_handler
     *
     **/
    public static function handle()
    {
        self::$errors[E_ERROR]['string'] = _('error');
        self::$errors[E_WARNING]['string'] = _('warning');
        self::$errors[E_PARSE]['string'] = _('parsing error');
        self::$errors[E_NOTICE]['string'] = _('notice');
        self::$errors[E_CORE_ERROR]['string'] = _('php error');
        self::$errors[E_CORE_WARNING]['string'] = _('php warning');
        self::$errors[E_COMPILE_ERROR]['string'] = _('compilation error');
        self::$errors[E_COMPILE_WARNING]['string'] = _('compilation warning');
        self::$errors[E_USER_ERROR]['string'] = _('user error');
        self::$errors[E_USER_WARNING]['string'] = _('user warning');
        self::$errors[E_USER_NOTICE]['string'] = _('user notice');
        self::$errors[E_STRICT]['string'] = _('strict error');
        self::$errors[E_RECOVERABLE_ERROR]['string'] = _('recoverable error');
        self::$errors[E_ALL]['string'] = _('generic error');

        set_exception_handler(array('tao\core\TaoExceptions', "getException"));
        set_error_handler(array('tao\core\TaoExceptions', "getError"), E_ALL);
    }

    /**
     * Restore default handlers
     *
     **/
    public static function restore()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * Exception handler, parse exception and return a human readable report
     *
     * @return string
     * @param  Exception $exception
     **/
    public static function getException($exception)
    {
        if (!headers_sent()) {
            header('Content-type: text/html; charset=UTF-8');
        }

        $obj = $exception->getTrace();
        $time = time();

        $src = (array_key_exists('class', $obj[0])) ? $obj[0]['class'] : $obj[0]['function'];
        $out = "\n".'<fieldset class="exception" onclick="document.getElementById(\'details_'.$time.'\').style.display = \'block\';"><legend class="title">';
        $out .= '<span class="icon"> ✖ </span>';
        if (self::isError($exception)) {
            $title_string = self::getErrorString($exception);
            $source_string = _($title_string.' found in %s line %d');
        } else {
            $title_string = _("%s thrown an exception");
            $source_string = _('exception thrown from %s line %d');
        }
        $out .= ucfirst(sprintf($title_string, $src)).'</legend>'."\n";

        $out .= "\n".'<span class="message">'.self::getMessage($exception)."</span>\n";
        $out .= "\n".'<br /><span class="summary">';
        $out .= ucfirst(sprintf($source_string, $exception->getFile(), $exception->getLine()));
        $out .= "</span>\n";
            $out .= "\n".'<div class="details" id="details_'.$time.'"><hr />';
            $out .= '<fieldset><legend>'.ucfirst(_("backtrace:")).'</legend>';
            $out .= "\n".'<div class="backtrace">'.self::getBacktrace($exception)."</div>\n";
            $out .= "\n".'</fieldset>';
            $out .= "\n".'</div>';
        $out .= "\n".'</fieldset>';

        $styles = '<style type="text/css" media="screen">
        <!--
            .exception
            {
                border: 2px solid #cc4500;
                border-radius: .8em;
                -opera-border-radius: .8em;
                -webkit-border-radius: .8em;
                -moz-border-radius: .8em;
            }
            .exception legend.title
            {
                font-size: 1.2em;
            }
            .exception .icon
            {
                color: #cc4500;
                font-weight: bolder;
                font-size: 1.2em;
                vertical-align: middle;
            }
            .exception span.message strong
            {
                color: #cc4500;
                font-weight: bolder;
            }
            .exception .summary
            {
                font-size: .8em;
                color: grey;
            }
            .exception hr
            {
                border: 1px solid #cc4500;
            }
            .exception .details
            {
                display: none;
                font-size: .7em;
                font-family: courier;
            }
            .exception .details fieldset
            {
                margin: 0;
                padding: 0;
                border: none;
            }
            .exception .details fieldset legend
            {
                font-weight: bolder
            }
        -->
        </style>';

        echo $styles;
        echo $out;
    }

    /**
     * Error handler, parse error and return a human readable reprt
     *
     * @return string
     * @param integer error level number
     * @param string error message
     * @param string error source file
     * @param integer error line number
     **/
    public static function getError($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, $errno, $errno, $errfile, $errline);

        return true;
    }

    /**
     * Check if the exception is an error
     *
     * @return boolean
     * @param object exception
     **/
    public static function isError($exception)
    {
        if (array_key_exists($exception->getCode(), self::$errors)) {
            return true;
        }

        return false;
    }

    /**
     * Return error name
     *
     * @return string
     * @param object exception
     **/
    public static function getErrorString($errorException)
    {
        if (array_key_exists($errorException->getCode(), self::$errors)) {
            return self::$errors[$errorException->getCode()]['string'];
        }

        return self::$errors[E_ALL]['string'];
    }

    /**
     * Return an highlighted message
     *
     * @return string
     * @param object exception
     **/
    public static function getMessage($exception)
    {
        $trace = $exception->getTrace();
        if (self::isError($exception)) {
            array_shift($trace);
        }
        if (count($trace) && array_key_exists('function', $trace[0])) {
            $function = trim($trace[0]['function']);
            $message = ucFirst($exception->getMessage());

            return preg_replace('/'.$function.'/iU', '<strong>'.$function.'</strong>', $message, 1);
        }

        return ucFirst($exception->getMessage());

    }

    /**
     * backtrace parser
     *
     * @return string
     * @param  Exception $exception
     **/
    public static function getBacktrace($exception)
    {
        $string = $exception->getTraceAsString();

        if (self::isError($exception)) {
            $array = explode("\n", $string);
            array_shift($array);
            foreach ($array as $key=>$line) {
                $array[$key] = htmlentities($line);
            }
            $string = implode("\n", $array);
        }

        $string = preg_replace('/\#\d+ {main}/i', '⇧ '.$_SERVER['PHP_SELF'].' {main}', $string);

        $string = preg_replace('/^\#\d+ /i', '✖ ', $string);

        $string = preg_replace('/^\#\d+ /im', '⬆ ', $string);

        return nl2br($string);
    }

}

