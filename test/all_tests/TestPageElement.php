<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestPageElement extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Adding elements');
	}

	function testAddingSimpleElement()
	{
		$this->document = new \tao\core\page\PageXhtml('1 strict', 'utf-8', 'fr');
		
		$this->document->addContent( new \tao\core\Element('hr') );
		
		$this->assertNoErrors('Xhtml Page created');
		$this->assertNbLinesEqual(10);
		$this->assertWantedPattern('/<hr \/>/', $this->getOutput(), 'hr found');
	}
	
	function testAddingRemovingSimpleElement()
	{
		$this->document = new \tao\core\page\PageXhtml('1 strict', 'utf-8', 'fr');
		
		$div = new \tao\core\Element('div');
		$this->document->addContent( $div );
		$div->remove();
		
		$this->assertNoErrors('Xhtml Page created');
		$this->assertNbLinesEqual(8);
		$this->assertNoUnwantedPattern('/<div><\/div>/', $this->getOutput(), 'div not found');
	}

}

?>