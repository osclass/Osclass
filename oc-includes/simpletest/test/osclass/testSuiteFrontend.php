<?php

require_once('../../../../oc-load.php');
require_once('../../test_case.php');

class AllFrontEndTests extends TestSuite {
    function AllFrontEndTests() {
        
        $this->TestSuite('All tests frontend');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-contactForm.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-login.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-register.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-search.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-items.php');


        
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-items.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_search.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_user.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_items.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_page.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_contact.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_login.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_frontend_register.php');        
    }
}
?>