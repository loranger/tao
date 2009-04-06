<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestPageStyles extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Adding inline styles test');
	}

	function tearDown()
	{
	}

	function testAddingXhtmlStyles()
	{
		$this->document = new PageXhtml('1 strict', 'utf-8', 'fr');
		
$style = <<<CSS
a {
	text-decoration: underline;
	color: #FFCC00;
}
CSS;
		$this->document->addStyle($style);
		
		$this->document->addStyle('div {border: 1px solid black;}');
		
		$this->assertNoErrors('Empty xhtml Page created');
		
		$this->assertNbLinesEqual(16);
		$this->assertWantedPattern('/<style type="text\/css">/', $this->getOutput(), 'Style tag found');
		$this->assertWantedPattern('/a {/', $this->getOutput(), 'Anchor style definition found');
		$this->assertWantedPattern('/div {border: 1px solid black;}/', $this->getOutput(), 'Div style definition found');
	}
	
	function testAddingXhtmlMediaStyles()
	{
		$this->document = new PageXhtml('1 strict', 'utf-8', 'fr');
		
$style = <<<CSS
a {
	text-decoration: underline;
	color: #FFCC00;
}
CSS;
		$this->document->addStyle($style, 'screen');
		
		$this->document->addStyle('div {border: 1px solid black;}');
		
		$this->document->addStyle('div {border: 1px solid blue;}', 'screen');
		
		$this->assertNoErrors('Empty xhtml Page created');

		$this->assertNbLinesEqual(16);
		$this->assertWantedPattern('/<style type="text\/css">/', $this->getOutput(), 'Style tag found');
		$this->assertWantedPattern('/a {/', $this->getOutput(), 'Anchor style definition found');
		$this->assertWantedPattern('/div {border: 1px solid black;}/', $this->getOutput(), 'Div style definition found');
	}

}

?>