<?php

	error_reporting(E_ALL);

	define('TAO_PATH', realpath(dirname(__FILE__)).'/');

	require_once(TAO_PATH.'core/TaoSettings.php');
	require_once(TAO_PATH.'core/TaoExceptions.php');
	TaoExceptions::handle();

	require_once(TAO_PATH.'core/Selector.php');
	require_once(TAO_PATH.'core/Document.php');
	require_once(TAO_PATH.'core/Element.php');
	require_once(TAO_PATH.'core/CodeImporter.php');
                      
	require_once(TAO_PATH.'core/Page.php');
	require_once(TAO_PATH.'core/pages/PageHtml.php');
	require_once(TAO_PATH.'core/pages/PageXhtml.php');

	require_once(TAO_PATH.'core/Functions.php');
	require_once(TAO_PATH.'core/Tao.php');
	
	// Enable or disable autoload feature
	TaoSettings()->useAutoload(true);
	
	// This is useless if you did disable autoload
	TaoSettings()->addAutoloadPath(TAO_PATH.'extend/pages/');
	TaoSettings()->addAutoloadPath(TAO_PATH.'extend/elements/');
	TaoSettings()->addAutoloadPath(TAO_PATH.'extend/featuring/');
	
	// TODO: Extend tao
	//TaoSettings()->setFeaturing(false);
	
	// TODO: l10n, i18n are not handled, yet.
	//TaoSettings()->setLocale('en_EN'); //(fr_FR, en_EN, en_US, es_ES, de_DE, pt_PT, pt_BR, it_IT, cn_CN...)
	//TaoSettings()->setLocalePath(TAO_PATH.'i18n/');

?>