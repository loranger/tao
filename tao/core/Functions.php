<?php

function tao($expression)
{
	if( preg_match('/<((\S+).*)>/', $expression, $match) )
	{
		return new CodeImporter($expression);
	}
	else
	{
		return Page()->find($expression);
	}
}

function €($expression)
{
	return tao($expression);
}

?>