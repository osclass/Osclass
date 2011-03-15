<?php

require_once('testSuiteFrontend.php');

$test = &new AllFrontEndTests();
$test->run(new HtmlReporter());

?>



