<?php

require_once(dirname(__FILE__).'/../../../../oc-load.php');
require_once(dirname(__FILE__).'/../../test_case.php');

class AllInstallerTests extends TestSuite {
    function AllInstallerTests() {
        
        $this->TestSuite('All tests installer');
        $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Installer-installer.php');

    }
}
?>
