<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestTaoSelector extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Testing css selector using tao');
	}
	
	function testSimpleTaoSelector()
	{
		$this->document = new \tao\core\page\PageXhtml('1 strict', 'utf-8', 'fr');
		
		$div = tao('<div/>')->addTo($this->document);
		
		$div = tao('<div/>')->setId('myDiv')->addTo($this->document);
		
		$child = tao('<div/>')->setId('myChild')->addTo($div);
		
		foreach(tao('div') as $element)
		{
			$element->addStyle('font-weight', 'bolder')->addClass('dummy');
		}
		
		$myDiv = tao('#myDiv')->setStyle('color', '#FFCC00')->removeClass('dummy');
		
		tao('#myChild')->addContent('Children here');
		$myDiv->find('div')->addContent(' and there');
		tao('#myDiv')->find('div')->addContent(' and also there');
		
		$this->assertNbLinesEqual(13);
		$this->assertNoUnwantedPattern('/<div>/', $this->getOutput(), 'Un-styled div not found');
		$this->assertWantedPattern('/<div id="myDiv" style="color:#FFCC00;">/', $this->getOutput(), 'Correct #myDiv found');
		$this->assertWantedPattern('/<div id="myChild" style="font-weight:bolder;" class="dummy">Children here and there and also there<\/div>/', $this->getOutput(), 'Correct #myChild found');
	}
	
	/*
	function toTest()
	{
		tao('*');						// Matches any element.
		tao('E');						// Matches any E element (i.e., an element of type E).
		tao('E F');					// Matches any F element that is a descendant of an E element.
		tao('E > F');					// Matches any F element that is a child of an element E.
		tao('E:first-child');			// Matches element E when E is the first child of its parent. 
		tao('E:link');					// Matches element E if E is the source anchor of a hyperlink of which the target is not yet visited (:link) or already visited (:visited). 
		tao('E:visited');				// Matches element E if E is the source anchor of a hyperlink of which the target is not yet visited (:link) or already visited (:visited). 
		tao('E:active');				// Matches E during certain user actions. 
		tao('E:hover');				// Matches E during certain user actions. 
		tao('E:focus');				// Matches E during certain user actions. 
		tao('E:lang(c)');				// Matches element of type E if it is in (human) language c (the document language specifies how language is determined). 
		tao('E + F');					// Matches any F element immediately preceded by an element E.
		tao('E[foo]');					// Matches any E element with the "foo" attribute set (whatever the value). 
		tao('E[foo="warning"]');		// Matches any E element whose "foo" attribute value is exactly equal to "warning". 
		tao('E[foo~="warning"]');		// Matches any E element whose "foo" attribute value is a list of space-separated values, one of which is exactly equal to "warning". 
		tao('E[lang|="en"]');			// Matches any E element whose "lang" attribute has a hyphen-separated list of values beginning (from the left) with "en". 
		tao('DIV.warning');			// HTML only. The same as DIV[class~="warning"]. 
		tao('E#myid');					// Matches any E element ID equal to "myid".
        tao
		tao('.class');
		tao('DIV.class1.class2 > .class3 ul');
	}
	*/

}

?>