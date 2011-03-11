<?php

require_once('testSuite.php');

$test = &new AllTests();
$test->run(new HtmlReporter());

?>
