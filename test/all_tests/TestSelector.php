<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestSelector extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Testing css selector');
	}
	
	function testSimpleSelector()
	{
		$this->document = new PageXhtml('1 strict', 'utf-8', 'fr');
		
		$div = new Element('div');
		$div->addTo($this->document);
		
		$div = new Element('div');
		$div->setId('myDiv')->addTo($this->document);
		
		$child = new Element('div');
		$child->setId('myChild')->addTo($div);
		
		foreach($this->document->find('div') as $element)
		{
			$element->addStyle('font-weight', 'bolder')->addClass('dummy');
		}
		
		$myDiv = $this->document->find('#myDiv')->setStyle('color', '#FFCC00')->removeClass('dummy');
		
		$this->document->find('#myChild')->addContent('Children here');
		$myDiv->find('div')->addContent(' and there');
		
		$this->assertNbLinesEqual(13);
		$this->assertNoUnwantedPattern('/<div>/', $this->getOutput(), 'Un-styled div not found');
		$this->assertWantedPattern('/<div id="myDiv" style="color:#FFCC00;">/', $this->getOutput(), 'Correct #myDiv found');
		$this->assertWantedPattern('/<div id="myChild" style="font-weight:bolder;" class="dummy">Children here and there<\/div>/', $this->getOutput(), 'Correct #myChild found');
	}
	
	/*
	function toTest()
	{
		find('*');						// Matches any element.
		find('E');						// Matches any E element (i.e., an element of type E).
		find('E F');					// Matches any F element that is a descendant of an E element.
		find('E > F');					// Matches any F element that is a child of an element E.
		find('E:first-child');			// Matches element E when E is the first child of its parent. 
		find('E:link');					// Matches element E if E is the source anchor of a hyperlink of which the target is not yet visited (:link) or already visited (:visited). 
		find('E:visited');				// Matches element E if E is the source anchor of a hyperlink of which the target is not yet visited (:link) or already visited (:visited). 
		find('E:active');				// Matches E during certain user actions. 
		find('E:hover');				// Matches E during certain user actions. 
		find('E:focus');				// Matches E during certain user actions. 
		find('E:lang(c)');				// Matches element of type E if it is in (human) language c (the document language specifies how language is determined). 
		find('E + F');					// Matches any F element immediately preceded by an element E.
		find('E[foo]');					// Matches any E element with the "foo" attribute set (whatever the value). 
		find('E[foo="warning"]');		// Matches any E element whose "foo" attribute value is exactly equal to "warning". 
		find('E[foo~="warning"]');		// Matches any E element whose "foo" attribute value is a list of space-separated values, one of which is exactly equal to "warning". 
		find('E[lang|="en"]');			// Matches any E element whose "lang" attribute has a hyphen-separated list of values beginning (from the left) with "en". 
		find('DIV.warning');			// HTML only. The same as DIV[class~="warning"]. 
		find('E#myid');					// Matches any E element ID equal to "myid".

		find('.class');
		find('DIV.class1.class2 > .class3 ul');
	}
	*/

}

?>