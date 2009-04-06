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
		$xpath = preg_replace("/\[(?!\w+\()/", '[@', $xpath);
		$xpath = preg_replace("/(\#([a-z][a-z0-9]+))/i", '[@id="$2"]', $xpath);
		$xpath = preg_replace("/(\.([a-z][a-z0-9]+))/i", '[contains(@class,"$2")]', $xpath);
		$xpath = preg_replace("/\s(\+|\s)+/", '/following-sibling::', $xpath);
		$xpath = preg_replace("/\s(>|\s)*/", '/', $xpath);
		$xpath = preg_replace("/\/\[/", '/*[', $xpath);
		
		// TODO Implement missing css3 selectors
		/*
			// http://www.w3.org/TR/css3-selectors/
			// http://xmlfr.org/w3c/TR/xpath/
			:empty() -> not(* or text())
			:not(s) -> not(self::*[_s]) 
			
		*/
		return $xpath;
	}	
}

?>