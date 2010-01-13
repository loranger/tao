<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestPage extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Page class test');
	}

	function testCreatingNewEmptyPage()
	{
		$this->document = new Page();

		$this->assertNoErrors('Empty default Page created');
		$this->assertNbLinesEqual(6);
		$this->assertWantedPattern('/<!DOCTYPE XML>/imU', $this->getOutput(), 'XML DocType found');
		$this->assertWantedPattern('/<xml>/', $this->getOutput(), 'xml root node found');
		$this->assertWantedPattern('/<head\/>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<body\/>/', $this->getOutput(), 'body node found');
	}

	function testCreatingNewDummyPage()
	{
		$this->document = new Page('html', 'utf-8', 'html');

		$this->assertNoErrors('Empty dummy Page created');
		$this->assertNbLinesEqual(6);
		$this->assertWantedPattern('/<!DOCTYPE HTML/imU', $this->getOutput(), 'HTML DocType found');
		$this->assertWantedPattern('/<html>/', $this->getOutput(), 'html root node found');
		$this->assertWantedPattern('/<head\/>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<body\/>/', $this->getOutput(), 'body node found');
	}

	function testCreatingNewXhtmlPage()
	{
		$this->document = new Page('html',
									'utf-8',
									'-//W3C//DTD XHTML 1.0 Transitional//EN',
									'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd',
									'http://www.w3.org/1999/xhtml');

		$this->assertNoErrors('Empty xhtml Page created');
		$this->assertNbLinesEqual(8);
		$this->assertWantedPattern('/<!DOCTYPE HTML PUBLIC "-\/\/W3C\/\/DTD XHTML 1.0 Transitional\/\/EN" "http:\/\/www.w3.org\/TR\/xhtml1\/DTD\/xhtml1-transitional.dtd">/imU',
									$this->getOutput(),
									'XHTML 1.0 Transitional DocType found');
		$this->assertWantedPattern('/<html xmlns="http:\/\/www.w3.org\/1999\/xhtml">/',
									$this->getOutput(),
									'html root node with correct xmlns found');
		$this->assertWantedPattern('/<head>/',
									$this->getOutput(),
									'head node found');
		$this->assertWantedPattern('/<meta http-equiv="Content-Type" content="text\/html; charset=UTF-8" \/>/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<body>/',
									$this->getOutput(),
									'body node found');
	}

	function testTitle()
	{
		$this->document = new Page('html', 'utf-8', 'html');

		$this->document->setTitle('Dummy title');
		$this->document->setTitle('Real title');

		$this->assertNoErrors('Empty dummy Page created');
		$this->assertWantedPattern('/<title>Real title<\/title>/', $this->getOutput(), 'correct title node found');
	}

	function testMeta()
	{
		$this->document = new Page('html', 'utf-8', 'html');

		$this->document->addMeta('indentifier-url', 'http://www.site.com');
		$this->document->addMeta('keywords', 'asp');
		$this->document->addMeta('keywords', 'java, html, unix, linux, php, mysql, php4');
		$this->assertNoErrors('Meta dummy Page created');
		$this->assertWantedPattern('/<meta name="indentifier-url" content="http:\/\/www.site.com"\/>/', $this->getOutput(), 'correct meta node found');
		$this->assertWantedPattern('/<meta name="keywords" content="java, html, unix, linux, php, mysql, php4"\/>/', $this->getOutput(), 'correct meta node found');
		$this->assertNoUnWantedPattern('/<meta name="keywords" content="asp"\/>/', $this->getOutput(), 'incorrect meta node not found');
	}

}

?>