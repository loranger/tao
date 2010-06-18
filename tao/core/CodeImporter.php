<?php

/**
 * Import existing code and convert it as an Element
 *
 * @package tao
 * @author loranger
 **/
class CodeImporter extends Element
{

	/**
	 * Fragment imported
	 *
	 * @var DocumentFragment
	 **/
	private $fragment;

	/**
	 * CodeImporter constructor
	 *
	 * @return object $this
	 * @param string $code
	 **/
	function __construct($code)
	{
		$this->checkDOM();

		if( preg_match('/<((\S+).*)>/', $code, $match) )
		{
			$this->fragment = Document::$dom->createDocumentFragment();
			if( Document::$charset )
			{
				$code = html_entity_decode($code, ENT_COMPAT, Document::$charset);
			}
			$this->fragment->appendXML( $code );
			parent::__construct($this->fragment);

			if($this->element->childNodes->length == 1)
			{
				$this->element = $this->element->firstChild;
			}
		}
		else
		{
			$this->element = Document::$dom->createTextNode($code);
		}

	}

}

?>