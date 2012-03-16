<?php
require_once('../../../../oc-load.php');
require_once('../../test_case.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        
        // OC-CONTENT / LANGUAGES & PLUGINS need to be writable

        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-generalSettings.php');    // OK
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-administrators.php');    // NEED DOC     
        
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-customfields.php');    // NOT FINISHED LOT OF FAILS
        
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-emailsAndAlerts.php');    // NEED DOC
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-users.php');    // NEED DOC HAS BUGS
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-languages.php'); // CURRENCY FORMAT HAS CHANGED, NEED TO UPDATE TEST, ALSO NEED TO UPDATE PACKAGE .ZIP
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-tools.php');              // OK NEED TO TEST LOCATION STATS, MAINTENANCE AND UPGRADE?
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-pages.php');              // OK MAYBE NEED TO TEST MULTI-LOCALE ...
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-plugins.php');            // OK - TAKE CARE OF FILES (oc-content/plugins should be writable)
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-appearance.php');         // OK
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-items.php');     // necesita limpiar código     
        
//        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_category.php');           // TODO 
            // OK


    }
}
?>
