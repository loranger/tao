<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestElements extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Adding elements');
	}
	
	function testSingleElements()
	{
		$this->document = null;
		$tao = new tao('div');

		$b = tao('<b />')->addContent('Bla 1');
		$b2 = tao('<b />')->addContent('Bla 2');
		$b3 = tao('<b />')->addContent('Bla 3');

		$tao->addContent(array($b, $b2, $b3));

		$tao->find('b')->setStyle('border', '1px solid blue');
			
		ob_start();
		echo $tao;
		$this->output = trim(ob_get_contents());
		ob_end_clean();
		
		$this->assertNbLinesEqual(1);
		$this->assertWantedPattern('/<b style="border:1px solid blue;">/', $this->output, 'Correct tag found');
		$this->assertWantedPattern('/Bla 1/', $this->output, 'Correct content found');
		$this->assertWantedPattern('/Bla 2/', $this->output, 'Multiple tags found');
		$this->assertNoUnWantedPattern('/<b>/', $this->output, 'No empty tag found');
	}

}

?>