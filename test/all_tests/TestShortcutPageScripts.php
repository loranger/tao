<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestShortcutPageScripts extends UnitDocumentTestCase {

	function __construct()
	{
	}

	function tearDown()
	{
	}

	function testCreatingNewEmptyPage()
	{
		$this->document = new \tao\core\page\PageXhtml();

		$this->assertNoErrors('Empty xhtml Page created');
	}

	function testAddingScriptSrc()
	{
		Page()->addScriptSrc('http://site.com/functions.js', 'top');
		$this->assertNoErrors('Headers added');
	}

	function testCheckAsserts()
	{
		$this->assertNbLinesEqual(9);

		$this->assertWantedPattern('/<head>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<\/script>/', $this->getOutput(), 'script node found');
		$this->assertWantedPattern('/type="text\/javascript"/', $this->getOutput(), 'Correct type attribute found');
		$this->assertWantedPattern('/src="http:\/\/site.com\/functions.js"/', $this->getOutput(), 'Correct src attribute found');
	}

}


?>