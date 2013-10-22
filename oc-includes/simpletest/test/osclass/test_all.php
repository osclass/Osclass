<?php


define('OSC_MEMORY_LIMIT', '128M') ;


if(PHP_SAPI==='cli') {
    require_once(dirname(__FILE__).'/config_test.php');
    $_SERVER['HTTPS'] = $https;
    $_SERVER['HTTP_HOST'] = $host;
    $_SERVER['REQUEST_URI'] = '';
}
require_once(dirname(__FILE__).'/testSuiteAll.php');
require_once(dirname(__FILE__).'/InstallerTest.php');
require_once(dirname(__FILE__).'/FrontendTest.php');
require_once(dirname(__FILE__).'/OCadminTest.php');
require_once(dirname(__FILE__).'/MyReporter.php');
require_once(dirname(__FILE__).'/util_settings.php');


$test = new AllTests();
$test->run(new MyReporter());

?>