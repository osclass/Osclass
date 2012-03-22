<?php

require_once('testSuiteAll.php');
require_once('InstallerTest.php');
require_once('FrontendTest.php');
require_once('OCadminTest.php');
require_once('MyReporter.php');
require_once("util_settings.php");

$test = new AllTests();
$test->run(new MyReporter());

?>
