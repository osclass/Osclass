<?php
require_once('../../../../oc-load.php');
require_once('../../test_case.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-generalSettings.php');    // OK
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-administrators.php');    // NEED DOC
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-customfields.php');    // NEED DOC
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-emailsAndAlerts.php');    // NEED DOC
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-users.php');    // NEED DOC
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-languages.php');
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-tools.php');              // OK
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-pages.php');              // OK
        

//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_category.php');           // TODO 
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_items.php');              // OK
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_appearance.php');         // OK
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_plugins.php');            // OK
    }
}
?>
