<?php

    error_reporting(E_ALL);

    define('TAO_PATH', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);

    require_once(TAO_PATH.'core/TaoSettings.php');
    require_once(TAO_PATH.'core/TaoExceptions.php');

    require_once(TAO_PATH.'core/Selector.php');
    require_once(TAO_PATH.'core/Document.php');
    require_once(TAO_PATH.'core/Element.php');
    require_once(TAO_PATH.'core/CodeImporter.php');

    require_once(TAO_PATH.'core/Page.php');
    require_once(TAO_PATH.'core/page/PageHtml.php');
    require_once(TAO_PATH.'core/page/PageXhtml.php');

    require_once(TAO_PATH.'core/Functions.php');
    require_once(TAO_PATH.'core/Tao.php');
	
	require_once(TAO_PATH.'singletons.php');
	tao\core\TaoExceptions::handle();

    // Enable or disable autoload feature
    // TaoSettings()->useAutoload(false);

    // Adding a path enable autoload
    // TaoSettings()->addAutoloadPath(TAO_PATH.'tao.*/');

    // TODO: Extend tao
    //TaoSettings()->setFeaturing(false);

    // TODO: l10n, i18n are not handled, yet.
    //TaoSettings()->setLocale('en_EN'); //(fr_FR, en_EN, en_US, es_ES, de_DE, pt_PT, pt_BR, it_IT, cn_CN...)
    //TaoSettings()->setLocalePath(TAO_PATH.'i18n/');

