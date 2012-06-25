<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestElement extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Adding elements');
	}
	
	function testSingleElement()
	{
		$b = new \tao\core\Element('b');
			$b->addContent('Bla ');
			$em = new \tao\core\Element('em');
				$em->addContent('emphase');
			$b->addContent($em);
			$b->addContent(' blabla');
			
		ob_start();
		echo $b;
		$this->output = trim(ob_get_contents());
		ob_end_clean();

		$this->assertNbLinesEqual(1);
		$this->assertWantedPattern('/<b>Bla <em>emphase<\/em> blabla<\/b>/', $this->output, 'Single tag found');
	}

	function testManipulatingElement()
	{
		$this->document = new \tao\core\page\PageXhtml('1 strict', 'utf-8', 'fr');
		
		$div = new \tao\core\Element('div');
		$this->document->addContent( $div );

		$b = new \tao\core\Element('b');
		$div->addContent($b);
		$b->addContent('Bold ');
		
		$em = new \tao\core\Element('em');
		$em->addTo($b);
		$em->addContent('emphase');

		$div->setClass( array('myClass', 'another ', ' class') );

		$div->addClass( array('dummy', 'fake ', 'foo') );
		$div->removeClass('fake');

		$div->setStyle('border: 1px solid red');
		$div->addStyle('color', 'brown');
		$div->addStyle( array('text-decoration: underline', 'background-color: #FFCC00') );
		$div->addStyle( array('background-color'=>'grey', 'color'=>'pink', 'yellow') );

		$div->addStyle( 'padding-top: 12px; padding-left: 50px;' );
		$div->removeStyle('padding');
		
		$this->assertNbLinesEqual(12);
		$this->assertWantedPattern('/<div/', $this->getOutput(), 'Div tag found');
		$this->assertWantedPattern('/class="myClass another class dummy foo"/', $this->getOutput(), 'Correct classes found');
		$this->assertWantedPattern('/style="border: 1px solid red;color:brown;text-decoration: underline;background-color: #FFCC00;background-color:grey;color:pink;"/', $this->getOutput(), 'Correct inline styles found');
		$this->assertWantedPattern('/<em>emphase<\/em>/', $this->getOutput(), 'Emphase tag found');
		$this->assertWantedPattern('/<b>Bold <em>emphase<\/em><\/b>/', $this->getOutput(), 'Correct b node found');
	}
}

?>