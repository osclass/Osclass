<?php

//    require_once('../../autorun.php');
//    require_once('../../../../common.php');
    require_once('../../../../oc-load.php');
//
//    require_once('../../unit_tester.php');
//    require_once('../../reporter.php');
//
//
//    class AllTests extends TestSuite {
//        function AllTests()
//        {
//            $this->TestSuite('All tests of OSClass');
////            $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test.php');
//            $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_pages.php');
//        }
//    }

require_once('../../autorun.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_pages.php');
    }
}


?>
