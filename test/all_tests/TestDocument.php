<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestDocument extends UnitDocumentTestCase {
	
	function __construct() {
		//$this->UnitTestCase('Document class test');
	}

	function testCreatingNewEmptyDocument()
	{
		$this->document = new Document();
		
		$this->assertNoErrors('Empty Document created');
		$this->assertNbLinesEqual(3);
		$this->assertWantedPattern('/<!DOCTYPE XML>/', $this->getOutput(), 'XML DocType found');
		$this->assertWantedPattern('/<xml\/>/', $this->getOutput(), 'XML root node found');
	}

	function testCreatingNewAtomDocument()
	{
		$this->document = new Document('xml', 'utf-8', 'atom 2.0');
		
		$this->assertNoErrors('Empty Document created');
		$this->assertNbLinesEqual(3);
		$this->assertWantedPattern('/<!DOCTYPE XML PUBLIC "atom 2.0" "">/', $this->getOutput(), 'XML DocType found');
		$this->assertWantedPattern('/<xml\/>/', $this->getOutput(), 'XML root node found');

	}

}


?>