<?php

class tao extends CodeImporter
{
	function __construct($expression)
	{
		if( preg_match('/<((\S+).*)>/', $expression, $match) )
		{
			parent::__construct($expression);
		}
		else
		{
			Element::__construct($expression);
		}
	}
}

function tao($expression)
{
	if( preg_match('/<((\S+).*)>/', $expression, $match) )
	{
		return new tao($expression);
	}
	else
	{
		return Page()->find($expression);
	}
}

?>