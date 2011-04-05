<?php

require_once('../../../../oc-load.php');
require_once('../../autorun.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_generalSettings.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_pages.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_category.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_users.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_language.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_emailsAndAlerts.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_administrators.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_tools.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_items.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_appearance.php');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/test_admin_plugins.php');
    }
}


?>
