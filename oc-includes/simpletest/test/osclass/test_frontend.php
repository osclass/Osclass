<?php

require_once('testSuiteFrontend.php');
require_once('FrontendTest.php');
require_once('MyReporter.php');
require_once("util_settings.php");

$test = new AllFrontEndTests();
$test->run(new MyReporter());
?>