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
<style type="text/css" media="screen">
	body {
		font-size: .8em;
	}
</style>