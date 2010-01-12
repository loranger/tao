<?php

class PageDummy extends PageXhtml
{

	function __construct()
	{
		$language = false;
		parent::__construct('1.0', 'utf-8', $language);

		$this->setTitle( 'Dummy page' );

		$css = new Element('link');
		$css->setAttribute('rel', 'stylesheet')->setAttribute('type', 'text/css')->setAttribute('href', 'static/css/dummy.css');
		self::$head->addContent($css->getElement());

		$script = new Element('script');
		$script->setAttribute('type', 'text/javascript')->setAttribute('src', 'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
		self::$head->addContent($script->getElement());

	}

	function render()
	{
		$footer = tao('<div />');
		$footer->setId('footer');
		$footer->addContent('This is the footer rendered with every PageDummy');
		$this->addContent($footer);

		return parent::render();
	}

}

?>