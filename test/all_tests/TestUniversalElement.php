<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestUniversalElement extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Adding elements');
	}

	function testManipulatingUniversalElement()
	{
		$this->document = new PageXhtml('1 strict', 'utf-8', 'fr');
		
		$div = new Element('div');
		$this->document->addContent( $div );
		
		$div->setId('myDiv');
		$div->setDir('rtl');
		$div->setLang('en');
		$div->setTitle('My "title"');
		$div->setClass('simple');
		$div->setStyle('border: 1px solid red');
		$div->addContent('dummy content');
		
		$this->assertNbLinesEqual(10);
		$this->assertWantedPattern('/<div/', $this->getOutput(), 'Div tag found');
		$this->assertWantedPattern('/id="myDiv"/', $this->getOutput(), 'correct id attribute found');
		$this->assertWantedPattern('/dir="rtl"/', $this->getOutput(), 'correct dir attribute found');
		$this->assertWantedPattern('/lang="en"/', $this->getOutput(), 'correct lang attribute found');
		//$this->assertWantedPattern('/xml:lang="en"/', $this->getOutput(), 'correct xml:lang attribute found');
		$this->assertWantedPattern('/title="My &quot;title&quot;"/', $this->getOutput(), 'correct title attribute found');
		$this->assertWantedPattern('/class="simple"/', $this->getOutput(), 'correct class attribute found');
		$this->assertWantedPattern('/style="border: 1px solid red;"/', $this->getOutput(), 'correct style attribute found');	
	}
	
}

?>