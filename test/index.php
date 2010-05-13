<?php

error_reporting(0);

	if (! defined('SIMPLE_TEST')) {
		define('SIMPLE_TEST', 'simpletest/');
	}
	require_once(SIMPLE_TEST . 'unit_tester.php');
	require_once(SIMPLE_TEST . 'reporter.php');
	require_once('ShowPasses.php');

	$tests = glob('./all_tests/*');
	foreach($tests as $test)
	{
		require_once($test);
	}

	$test = new GroupTest('All tests');
	foreach($tests as $file)
	{
		$filename = substr($file, strrpos($file, '/')+1);
		$testname = substr($filename, 0, strrpos($filename, '.'));
		$test->addTestCase(new $testname());
	}
	$test->run(new ShowPasses());
	//$test->run(new HtmlReporter());

?>
<pre id="phpinfo"><?php

function phpinfo_array( $return = false ){
	ob_start();
	phpinfo(-1);

	$pi = preg_replace(
		array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
			'#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
			"#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
			'#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
			.'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
			'#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
			'#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
			"# +#", '#<tr>#', '#</tr>#'),
		array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
			'<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
			"\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
			'<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
			'<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
			'<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
		ob_get_clean() );

	$sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
	unset($sections[0]);

	$pi = array();
	foreach($sections as $section)
	{
		$n = substr($section, 0, strpos($section, '</h2>'));
		preg_match_all(
			'#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
			$section, $askapache, PREG_SET_ORDER);
		foreach($askapache as $m)
		{
			if( isset($m[2]) )
			{
				$pi[$n][$m[1]] = ( !isset($m[3]) || $m[2]==$m[3] ) ? $m[2] : array_slice($m,2);
			}
		}
	}

	return ($return === false) ? print_r($pi) : $pi;
}

var_dump( phpinfo_array() );

?></pre>
<style type="text/css" media="screen">
	body {
		font-size: .8em;
	}
	#phpinfo {
		height: 400px;
		overflow: auto;
		display: none;
	}
</style>