<?php

require_once('testSuiteInstaller.php');
require_once('InstallerTest.php');
require_once('MyReporter.php');
require_once("util_settings.php");

$test = new AllInstallerTests();
$test->run(new MyReporter());
?>