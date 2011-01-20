<?php

    require_once('../../autorun.php');
    require_once('../../../../common.php');
    require_once('../../../../config.php');
    
    class AllTests extends TestSuite {
        function AllTests() {
            $this->TestSuite('All tests of OSClass');
            $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test.php');
            $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test2.php');
        }
    }
?>
