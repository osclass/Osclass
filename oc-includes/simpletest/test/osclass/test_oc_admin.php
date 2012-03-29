<?php

require_once(dirname(__FILE__).'/testSuite.php');
require_once(dirname(__FILE__).'/OCadminTest.php');
require_once(dirname(__FILE__).'/MyReporter.php');
require_once(dirname(__FILE__).'/util_settings.php');

$test = new AllAdminTests();
$test->run(new MyReporter());

?>
