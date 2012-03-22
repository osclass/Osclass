<?php

require_once(dirname(__FILE__).'/testSuiteInstaller.php');
require_once(dirname(__FILE__).'/InstallerTest.php');
require_once(dirname(__FILE__).'/MyReporter.php');
require_once(dirname(__FILE__).'/util_settings.php');

$test = new AllInstallerTests();
$test->run(new MyReporter());
?>