<?php

require_once('testSuiteFrontend.php');
echo "<h1>IMPORTANT - For tests is needed a native installation</h1>";
$test = &new AllFrontEndTests();
$test->run(new HtmlReporter());

?>



