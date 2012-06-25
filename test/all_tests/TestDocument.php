<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestDocument extends UnitDocumentTestCase {

	function __construct() {
		//$this->UnitTestCase('Document class test');
	}

	function testCreatingNewEmptyDocument()
	{
		$this->document = new \tao\core\Document();

		$this->assertNoErrors('Empty Document created');
		$this->assertNbLinesEqual(3);
		$this->assertWantedPattern('/<!DOCTYPE XML>/imU', $this->getOutput(), 'XML DocType found');
		$this->assertWantedPattern('/<xml\/>/', $this->getOutput(), 'XML root node found');
	}

	function testCreatingNewAtomDocument()
	{
		$this->document = new \tao\core\Document('xml', 'utf-8', 'atom 2.0');

		$this->assertNoErrors('Empty Document created');
		$this->assertNbLinesEqual(3);
		$this->assertWantedPattern('/<!DOCTYPE XML PUBLIC "atom 2.0" "">/imU', $this->getOutput(), 'XML DocType found');
		$this->assertWantedPattern('/<xml\/>/', $this->getOutput(), 'XML root node found');

	}

}


?>