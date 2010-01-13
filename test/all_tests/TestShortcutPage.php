<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestShortcutPage extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Shortuct PageX test');
	}

	function tearDown()
	{
	}

	function testCreatingNewEmptyPage()
	{
		$this->document = Page('xhtml');

		$this->assertNoErrors('Empty xhtml Page created');
	}

	function testCreatingHeaders()
	{
		Page()->setTitle('my new title');

		Page()->addMeta('indentifier-url', 'http://www.site.com');
		Page()->addMeta('keywords', 'java, html, unix, linux, php, mysql, php4');

		Page()->addHTTPMeta('expires', 'Sat, 01 Dec 2001 00:00:00 GMT');
		Page()->setBase('http://www.site.com', '_blank');

		$this->assertNoErrors('Headers added');
	}

	function testCheckAsserts()
	{
		$this->assertNbLinesEqual(13);
		$this->assertWantedPattern('/<!DOCTYPE HTML PUBLIC "-\/\/W3C\/\/DTD XHTML 1.0 Transitional\/\/EN" "http:\/\/www.w3.org\/TR\/xhtml1\/DTD\/xhtml1-transitional.dtd">/imU',
									$this->getOutput(),
									'XHTML 1.0 Transitional DocType found');
		$this->assertWantedPattern('/<html xmlns="http:\/\/www.w3.org\/1999\/xhtml">/', $this->getOutput(), 'Correct html root node found');
		$this->assertWantedPattern('/<head>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<meta http-equiv="Content-Type" content="text\/html; charset=UTF-8" \/>/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<body>/', $this->getOutput(), 'body node found');
	}

}


?>