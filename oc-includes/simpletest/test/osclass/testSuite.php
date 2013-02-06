<?php
require_once(dirname(__FILE__).'/../../../../oc-load.php');
require_once(dirname(__FILE__).'/../../test_case.php');

class AllAdminTests extends TestSuite {
    function AllAdminTests() {
        $this->TestSuite('All tests');
        
        // OC-CONTENT / LANGUAGES & PLUGINS need to be writable

        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-settings.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-administrators.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-emailsAndAlerts.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-users.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-languages.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-tools.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-pages.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-plugins.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-appearance.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-items.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-moderator.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-market.php');

        // TO DO
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-customfields.php');    // NOT FINISHED LOT OF FAILS
        //$this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_category.php');           // TODO 

        

    }
}
?>
