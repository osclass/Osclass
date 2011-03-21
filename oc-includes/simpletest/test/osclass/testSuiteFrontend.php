<?php

require_once('../../../../oc-load.php');
require_once('../../autorun.php');

class AllFrontEndTests extends TestSuite {
    function AllFrontEndTests() {
        
        $this->TestSuite('All tests frontend');

        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_register.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_user.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_items.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_page.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_contact.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_login.php');
        
    }
}


?>
