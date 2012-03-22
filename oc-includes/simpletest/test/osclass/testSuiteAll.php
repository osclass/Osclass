<?php
require_once('../../../../oc-load.php');
require_once('../../test_case.php');

class AllTests extends TestSuite {
    function AllTests() {
        $this->TestSuite('All tests');
        
        $tests = array();
        foreach($_REQUEST as $k => $v) {
            if($k=='installer' || $k=='frontend' || $k=='admin') {
                $tests[$k] = explode(",", $v);
            }
        }
        if(empty($tests)) {
            $tests['installer'] = '';
            $tests['frontend'] = '';
            $tests['admin'] = '';
        }
        
        // INSTALLER
        if(isset($tests['installer'])) {
            $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Installer-installer.php');
        }


        // FRONTEND
        if(isset($tests['frontend'])) {
            if(isset($tests['frontend']['contact']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-contactForm.php');
            }
            if(isset($tests['frontend']['login']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-login.php');
            }
            if(isset($tests['frontend']['register']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-register.php');
            }
            if(isset($tests['frontend']['search']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-search.php');
            }
            if(isset($tests['frontend']['items']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-items.php');
            }
            if(isset($tests['frontend']['page']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-page.php');      
            }
            if(isset($tests['frontend']['users']) || $tests['frontend']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/Frontend-users.php');
            }
        }

        
        // ADMIN
        if(isset($tests['admin'])) {
            if(isset($tests['admin']['settings']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-generalSettings.php');    // OK
            }
            if(isset($tests['admin']['administrators']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-administrators.php');    // NEED DOC     
            }
            if(isset($tests['admin']['emailandalerts']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-emailsAndAlerts.php');    // NEED DOC
            }
            if(isset($tests['admin']['users']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-users.php');    // NEED DOC HAS BUGS
            }
            if(isset($tests['admin']['languages']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-languages.php'); // CURRENCY FORMAT HAS CHANGED, NEED TO UPDATE TEST, ALSO NEED TO UPDATE PACKAGE .ZIP
            }
            if(isset($tests['admin']['tools']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-tools.php');              // OK NEED TO TEST LOCATION STATS, MAINTENANCE AND UPGRADE?
            }
            if(isset($tests['admin']['pages']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-pages.php');              // OK MAYBE NEED TO TEST MULTI-LOCALE ...
            }
            if(isset($tests['admin']['plugins']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-plugins.php');            // OK - TAKE CARE OF FILES (oc-content/plugins should be writable)
            }
            if(isset($tests['admin']['appearance']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-appearance.php');         // OK
            }
            if(isset($tests['admin']['items']) || $tests['admin']=='') {
                $this->addFile(ABS_PATH . 'oc-includes/simpletest/test/osclass/OCadmin-items.php');     // necesita limpiar código     
            }
        }


    }
}
?>
