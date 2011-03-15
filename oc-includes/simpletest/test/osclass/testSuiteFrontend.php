<?php

require_once('../../../../oc-load.php');
require_once('../../autorun.php');

class AllFrontEndTests extends TestSuite {
    function AllFrontEndTests() {
        $this->TestSuite('All tests');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_page.php');
        echo "added test_frontend_page<br>";
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_contact.php');
        echo "added test_frontend_contact<br>";
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_login.php');
        echo "added test_frontend_login<br>";
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_register.php');
        echo "added test_frontend_register<br>";
    }
}


?>
