<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestPageHtml extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('PageHtml class test');
	}

	function testCreatingNewEmptyPageHtml()
	{
		$this->document = new PageHtml();
		
		$this->assertNoErrors('Empty default PageHtml created');
		$this->assertNbLinesEqual(5);
		$this->assertWantedPattern('/<!DOCTYPE HTML PUBLIC "-\/\/W3C\/\/DTD HTML 4.01 Transitional\/\/EN" "http:\/\/www.w3.org\/TR\/html4\/loose.dtd">/',
									$this->getOutput(),
									'HTML 4.01 Transitional DocType found');
		$this->assertWantedPattern('/<html>/', $this->getOutput(), 'html root node found');
		$this->assertWantedPattern('/<head>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<meta http-equiv="Content-Type" content="text\/html; charset=UTF-8">/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<body>/', $this->getOutput(), 'body node found');
	}
	
	function testCreatingNewEmptyPageHtml4()
	{
		$this->document = new PageHtml('4.01', 'iso8859-1', 'fr');
		
		$this->assertNoErrors('Empty PageHtml4 created');
		$this->assertNbLinesEqual(5);
		$this->assertWantedPattern('/<!DOCTYPE HTML PUBLIC "-\/\/W3C\/\/DTD HTML 4.01 Transitional\/\/EN" "http:\/\/www.w3.org\/TR\/html4\/loose.dtd">/',
									$this->getOutput(),
									'HTML 4.01 Transitional DocType found');
		$this->assertWantedPattern('/<html lang="fr">/', $this->getOutput(), 'html root node found');
		$this->assertWantedPattern('/<head>/', $this->getOutput(), 'head node found');
		$this->assertWantedPattern('/<meta http-equiv="Content-Type" content="text\/html; charset=ISO8859-1">/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<body>/', $this->getOutput(), 'body node found');
	}
	
	function testCreatingNewPageHtml()
	{
		$this->document = new PageHtml('4.01', 'iso8859-1', 'fr');
		
		$this->document->setTitle('my default title');
		$this->document->addMeta('indentifier-url', 'http://www.site.com');
		$this->document->addMeta('keywords', 'asp');
		$this->document->addHTTPMeta('expires', 'Sat, 01 Dec 2001 00:00:00 GMT');
		$this->document->setBase('./');
		
		$this->assertNoErrors('Empty PageHtml4 created');
		$this->assertNbLinesEqual(12);
		$this->assertWantedPattern('/<title>my default title<\/title>/', $this->getOutput(), 'Correct title node found');
		$this->assertWantedPattern('/<meta name="indentifier-url" content="http:\/\/www.site.com">/',
									$this->getOutput(),
									'Correct meta node found');
		$this->assertWantedPattern('/<meta name="keywords" content="asp">/', $this->getOutput(), 'Correct meta node found');
		$this->assertWantedPattern('/<meta http-equiv="expires" content="Sat, 01 Dec 2001 00:00:00 GMT">/',
									$this->getOutput(),
									'Correct meta http-equiv node found');
		$this->assertWantedPattern('/<base href=".\/">/', $this->getOutput(), 'Correct base node found');
	}

}


?>