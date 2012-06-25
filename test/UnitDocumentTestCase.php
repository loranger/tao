<?php

error_reporting(0);

require_once(dirname(__FILE__).'/simpletest/autorun.php');
require_once(dirname(__FILE__).'/../tao/require.php');
\tao\core\TaoExceptions::restore();

class UnitDocumentTestCase extends UnitTestCase {

	protected $document;
	private $output = false;

	function tearDown() {
		ob_start();
		if($this->document)
		{
			$this->document->__destruct();
		}
		unset($this->document);
		ob_end_clean();
		$this->resetOutput();
	}

	function resetOutput()
	{
		$this->output = false;
	}

	protected function getOutput()
	{
		if( !$this->output )
		{
			ob_start();
			echo $this->document;
			$this->output = trim(ob_get_contents());
			ob_end_clean();
		}
		return $this->output;
	}

	protected function getDebug()
	{
		echo '<pre>';
		echo htmlentities($this->getOutput());
		echo '</pre>';
	}

	function assertNbLinesEqual($number)
	{
		$this->assertEqual( count(explode("\n", $this->getOutput())), $number, $number.' lines found');
	}

}


?>