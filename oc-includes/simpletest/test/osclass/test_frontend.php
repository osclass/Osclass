<?php

require_once(dirname(__FILE__).'/testSuiteFrontend.php');
require_once(dirname(__FILE__).'/FrontendTest.php');
require_once(dirname(__FILE__).'/MyReporter.php');
require_once(dirname(__FILE__).'/util_settings.php');

$test = new AllFrontEndTests();
$test->run(new MyReporter());
?>