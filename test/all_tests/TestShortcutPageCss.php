<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestShortcutPageCss extends UnitDocumentTestCase {

	function __construct()
	{
	}

	function tearDown()
	{
	}

	function testCreatingNewEmptyPage()
	{
		$this->document = new PageXhtml();

		$this->assertNoErrors('Empty xhtml Page created');
	}

	function testAddingScriptSrc()
	{
		Page()->addCss('static/css/dummy.css');
		$this->assertNoErrors('Headers added');
	}

	function testCheckAsserts()
	{
		$this->assertNbLinesEqual(9);

		$this->assertWantedPattern('/<head>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<link /', $this->getOutput(), 'link node found');
		$this->assertWantedPattern('/type="text\/css"/', $this->getOutput(), 'Correct type attribute found');
		$this->assertWantedPattern('/rel="stylesheet"/', $this->getOutput(), 'Correct rel attribute found');
		$this->assertWantedPattern('/href="static\/css\/dummy.css"/', $this->getOutput(), 'Correct href attribute found');
	}

}


?>