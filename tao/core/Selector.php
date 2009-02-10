<?php

// http://ejohn.org/blog/xpath-css-selectors/
// http://www.css.su/#selectors

/**
 * Selector class
 *
 * @package tao
 * @author loranger
 **/
class Selector
{
	
	private $expression;
	
	function __construct($expression)
	{
		$this->expression = $expression;
	}
	
	/**
	 * Convert Selector to XPath expression
	 *
	 * @return string xpath expression
	 * @author loranger
	 **/
	function toXpath()
	{
		$xpath = './/'.$this->expression;
		$xpath = preg_replace("/\[/", '[@', $xpath);
		$xpath = preg_replace("/(\#([a-z][a-z0-9]+))/i", '[@id="$2"]', $xpath);
		$xpath = preg_replace("/(\.([a-z][a-z0-9]+))/i", '[contains(@class,"$2")]', $xpath);
		$xpath = preg_replace("/\s(\+|\s)+/", '/following-sibling::', $xpath);
		$xpath = preg_replace("/\s(>|\s)*/", '/', $xpath);
		$xpath = preg_replace("/\/\[/", '/*[', $xpath);
		return $xpath;
	}	
}

?>