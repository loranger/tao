<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestPageXhtml extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('PageXhtml class test');
	}

	function testCreatingNewEmptyPageXhtml()
	{
		$this->document = new PageXhtml();
		
		$this->assertNoErrors('Empty default PageXhtml created');
		$this->assertNbLinesEqual(8);
		$this->assertWantedPattern('/<!DOCTYPE HTML PUBLIC "-\/\/W3C\/\/DTD XHTML 1.0 Transitional\/\/EN" "http:\/\/www.w3.org\/TR\/xhtml1\/DTD\/xhtml1-transitional.dtd">/',
									$this->getOutput(),
									'XHTML 1.0 Transitional DocType found');
		$this->assertWantedPattern('/<html xmlns="http:\/\/www.w3.org\/1999\/xhtml">/', $this->getOutput(), 'Correct html root node found');
		$this->assertWantedPattern('/<head>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<meta http-equiv="Content-Type" content="text\/html; charset=UTF-8" \/>/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<body>/', $this->getOutput(), 'body node found');
	}
	
	function testCreatingNewEmptyPageXhtmlStrict()
	{
		$this->document = new PageXhtml('1 strict', 'utf-8', 'fr');
		
		$this->assertNoErrors('Empty PageXhtml 1.0 Strict created');
		$this->assertNbLinesEqual(8);
		$this->assertWantedPattern('/<!DOCTYPE HTML PUBLIC "-\/\/W3C\/\/DTD XHTML 1 Strict\/\/EN" "http:\/\/www.w3.org\/TR\/xhtml1\/DTD\/xhtml1-strict.dtd">/',
									$this->getOutput(),
									'XHTML 1.0 Transitional DocType found');
		$this->assertWantedPattern('/<html xmlns="http:\/\/www.w3.org\/1999\/xhtml" xml:lang="fr" lang="fr">/', $this->getOutput(), 'Correct html root node found');
		$this->assertWantedPattern('/<head>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<meta http-equiv="Content-Type" content="text\/html; charset=UTF-8" \/>/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<body>/', $this->getOutput(), 'body node found');
	}
	
	function testCreatingNewPageXhtmlStrict()
	{
		$this->document = new PageXhtml('1 strict', 'utf-8', 'fr');
		
		$this->document->setTitle('my default title');
		$this->document->addMeta('indentifier-url', 'http://www.site.com');
		$this->document->addMeta('keywords', 'java, html, unix, linux, php, mysql, php4');
		$this->document->addHTTPMeta('expires', 'Sat, 01 Dec 2001 00:00:00 GMT');
		$this->document->setBase('http://www.site.com', '_blank');
		
		$this->assertNoErrors('PageXhtml 1.0 Strict created');
		$this->assertNbLinesEqual(13);
		$this->assertWantedPattern('/<title>my default title<\/title>/', $this->getOutput(), 'Correct title node found');
		$this->assertWantedPattern('/<meta name="indentifier-url" content="http:\/\/www.site.com" \/>/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<meta name="keywords" content="java, html, unix, linux, php, mysql, php4" \/>/', $this->getOutput(), 'Correct meta node found');
		$this->assertWantedPattern('/<meta http-equiv="expires" content="Sat, 01 Dec 2001 00:00:00 GMT" \/>/',
									$this->getOutput(),
									'Correct meta http-equiv node found');
		$this->assertWantedPattern('/<base href="http:\/\/www.site.com" target="_blank" \/>/', $this->getOutput(), 'Correct base node found');
	}

}


?>