<?php

require_once(dirname(__FILE__).'/../UnitDocumentTestCase.php');

class TestTaoElement extends UnitDocumentTestCase {

	function __construct()
	{
		//$this->UnitTestCase('Adding elements using tao');
	}
	
	function testSingleNewTaoElement()
	{
		$this->document = null;
		$tao = new \tao\core\tao('b');
		$tao->addContent('Bla ');
			
		ob_start();
		echo $tao;
		$this->output = trim(ob_get_contents());
		ob_end_clean();

		$this->assertNbLinesEqual(1);
		$this->assertWantedPattern('/<b>Bla <\/b>/', $this->output, 'Single tag found');
	}
	
	function testSingleNewTaoTagElement()
	{
		$this->document = null;
		$tao = new \tao\core\tao('<b />');
		$tao->addContent('Bla ');
			
		ob_start();
		echo $tao;
		$this->output = trim(ob_get_contents());
		ob_end_clean();

		$this->assertNbLinesEqual(1);
		$this->assertWantedPattern('/<b>Bla <\/b>/', $this->output, 'Single tag found');
	}
	
	function testSingleTaoElement()
	{
		$this->document = null;
		$tao = tao('<b />')
				->addContent('Bla ')
				->addContent( 
					tao('<em />')->addContent('emphase')
				)
				->addContent(' blabla');
			
		ob_start();
		echo $tao;
		$this->output = trim(ob_get_contents());
		ob_end_clean();
		$this->assertNbLinesEqual(1);
		$this->assertWantedPattern('/<b>Bla <em>emphase<\/em> blabla<\/b>/', $this->output, 'Single tag found');
	}
	
	function testManipulatingTaoElements()
	{
		$this->document = new \tao\core\page\PageXhtml('1 strict', 'utf-8', 'fr');
		
		$div = tao('<div />')->addTo($this->document);

		$b = tao('<b />')->addTo($div)->addContent('Bold ');
		
		tao('<em />')->addTo($b)->addContent('emphase');
		

		$div->setClass( array('myClass', 'another ', ' class') )
			->addClass( array('dummy', 'fake ', 'foo') )
			->removeClass('fake');

		$div->setStyle('border: 1px solid red')
			->addStyle('color', 'brown')
			->addStyle( array('text-decoration: underline', 'background-color: #FFCC00') )
			->addStyle( array('background-color'=>'grey', 'color'=>'pink', 'yellow') );

		$div->addStyle( 'padding-top: 12px; padding-left: 50px;' )
			->removeStyle('padding');
		
		$this->assertNbLinesEqual(12);
		$this->assertWantedPattern('/<div/', $this->getOutput(), 'Div tag found');
		$this->assertWantedPattern('/class="myClass another class dummy foo"/', $this->getOutput(), 'Correct classes found');
		$this->assertWantedPattern('/style="border: 1px solid red;color:brown;text-decoration: underline;background-color: #FFCC00;background-color:grey;color:pink;"/', $this->getOutput(), 'Correct inline styles found');
		$this->assertWantedPattern('/<em>emphase<\/em>/', $this->getOutput(), 'Emphase tag found');
		$this->assertWantedPattern('/<b>Bold <em>emphase<\/em><\/b>/', $this->getOutput(), 'Correct b node found');
	}

}

?>